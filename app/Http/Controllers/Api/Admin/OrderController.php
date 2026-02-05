<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\FcmService;
use App\Services\WaService;

class OrderController extends Controller
{
    protected $fcm;
    protected $wa;

    public function __construct(FcmService $fcm, WaService $wa)
    {
        $this->fcm = $fcm;
        $this->wa = $wa;
    }

    public function index()
    {
        return Order::with(['customer','mitra','items.product'])->paginate(20);
    }

    public function store(Request $request)
    {
        // Simplified: receive customer_id, mitra_id, items[], pickup, drop lat/lng
        $data = $request->validate([
            'customer_id'=>'required|exists:users,id',
            'mitra_id'=>'required|exists:mitras,id',
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.qty'=>'required|integer|min:1',
            'pickup_lat'=>'required|numeric',
            'pickup_lng'=>'required|numeric',
            'drop_lat'=>'required|numeric',
            'drop_lng'=>'required|numeric',
        ]);

        // Calculate distance (Haversine simplified) - placeholder
        $distanceKm = $this->calculateDistance($data['pickup_lat'],$data['pickup_lng'],$data['drop_lat'],$data['drop_lng']);
        $pricePerKm = (int)Setting::getValue('price_per_km', env('PRICE_PER_KM',3000));
        $deliveryFee = (int)round($distanceKm * $pricePerKm);
        $adminPercent = (int)Setting::getValue('admin_fee_percent', env('ADMIN_FEE_PERCENT',10));
        $adminFee = (int)round($deliveryFee * ($adminPercent/100));

        $order = Order::create([
            'order_no' => 'ORD'.time(),
            'customer_id'=>$data['customer_id'],
            'mitra_id'=>$data['mitra_id'],
            'pickup_lat'=>$data['pickup_lat'],
            'pickup_lng'=>$data['pickup_lng'],
            'drop_lat'=>$data['drop_lat'],
            'drop_lng'=>$data['drop_lng'],
            'distance_km'=>$distanceKm,
            'delivery_fee'=>$deliveryFee,
            'admin_fee'=>$adminFee,
            'subtotal'=>0,
            'status'=>'pending'
        ]);

        $subtotal = 0;
        foreach ($data['items'] as $it) {
            $product = \App\Models\Product::findOrFail($it['product_id']);
            $price = $product->price;
            OrderItem::create(['order_id'=>$order->id,'product_id'=>$product->id,'qty'=>$it['qty'],'price'=>$price]);
            $subtotal += $price * $it['qty'];
        }

        $order->update(['subtotal'=>$subtotal]);

        // Push notif to mitra and admin
        $this->fcm->sendToUser($order->mitra->user_id, 'New Order', "Order {$order->order_no} masuk");
        $this->wa->send($order->mitra->phone ?? null, "New Order {$order->order_no}");

        return response()->json($order->load('items'));
    }

    public function show($id)
    {
        return Order::with(['items.product','customer','mitra','driver'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'status'=>'in:pending,paid,accepted,on_delivery,delivered,cancelled',
            'driver_id'=>'nullable|exists:drivers,id'
        ]);

        \DB::transaction(function() use ($order, $data) {
            $prev = $order->status;

            if (isset($data['driver_id'])) {
                $order->driver_id = $data['driver_id'];
            }

            if (isset($data['status'])) {
                $new = $data['status'];

                // Transition rules (basic)
                $allowed = [
                    'pending' => ['paid','cancelled'],
                    'paid' => ['accepted','cancelled'],
                    'accepted' => ['on_delivery','cancelled'],
                    'on_delivery' => ['delivered','cancelled'],
                    'delivered' => [],
                    'cancelled' => []
                ];

                if ($prev !== $new) {
                    if (! in_array($new, $allowed[$prev] ?? [])) {
                        throw new \Exception('Invalid status transition from '.$prev.' to '.$new);
                    }
                    $order->status = $new;
                }
            }

            $order->save();

            // When delivered -> distribute earnings
            if ($order->status === 'delivered' && $prev !== 'delivered') {
                $ws = app(\App\Services\WalletService::class);
                $ws->distributeEarnings($order);

                // notify customer & mitra & driver
                $fcm = app(\App\Services\FcmService::class);
                $wa = app(\App\Services\WaService::class);
                $fcm->sendToUser($order->customer_id, 'Order Delivered', "Order {$order->order_no} telah selesai", ['order_id'=>$order->id]);
                $fcm->sendToUser($order->mitra->user_id, 'Order Delivered', "Order {$order->order_no} telah selesai", ['order_id'=>$order->id]);
                if ($order->driver) {
                    $fcm->sendToUser($order->driver->user_id, 'Delivery Completed', "Terima kasih, pengiriman untuk {$order->order_no} selesai", ['order_id'=>$order->id]);
                }

                $wa->send($order->customer->phone ?? null, "Order {$order->order_no} telah diterima. Terima kasih.");
            }
        });

        return response()->json($order->fresh());
    }

    // Admin manual assign driver
    public function assignDriver(Request $request, $id)
    {
        $data = $request->validate(['driver_id'=>'required|exists:drivers,id']);
        $order = Order::findOrFail($id);
        $order->driver_id = $data['driver_id'];
        $order->status = 'accepted';
        $order->save();

        $fcm = app(\App\Services\FcmService::class);
        $fcm->sendToUser($order->driver->user_id, 'Order Assigned', "Order {$order->order_no} ditugaskan secara manual", ['order_id'=>$order->id]);

        return response()->json(['message'=>'driver assigned','order'=>$order]);
    }

    protected function calculateDistance($lat1,$lng1,$lat2,$lng2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }
}
