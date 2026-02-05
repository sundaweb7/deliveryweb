<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\DistanceService;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function listMitras(Request $request)
    {
        $request->validate(['lat'=>'required|numeric','lng'=>'required|numeric','radius'=>'nullable|numeric']);
        $lat = $request->lat; $lng = $request->lng; $radius = $request->radius ?? (float)Setting::getValue('max_radius_km', env('MAX_RADIUS_KM',20));
        $ds = app(DistanceService::class);
        $drivers = $ds->driversWithinRadius($lat,$lng,$radius,50); // reuse for mitra search? better: use SQL on mitras

        // Simple mitra search by haversine (custom raw)
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(mitras.lat)) * cos(radians(mitras.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(mitras.lat))))";
        $mitras = \DB::table('mitras')->select('mitras.*', DB::raw("{$haversine} as distance_km"))
            ->whereNotNull('lat')->whereNotNull('lng')->having('distance_km','<=',$radius)->orderBy('distance_km')->get();

        return response()->json($mitras);
    }

    public function products($mitraId)
    {
        $products = Product::where('mitra_id',$mitraId)->get()->map(function($p){
            return [
                'id'=>$p->id,
                'name'=>$p->name,
                'description'=>$p->description,
                'price'=>$p->price,
                'stock'=>$p->stock,
                'image_url'=>$p->image ? asset('storage/products/'.$p->image) : null
            ];
        });
        return response()->json($products);
    }

    public function createOrder(\App\Http\Requests\Api\Mobile\CreateOrderRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        return DB::transaction(function() use ($user, $data) {
            $distanceService = app(DistanceService::class);
            $distanceKm = $distanceService->calculateDistance($data['pickup_lat'],$data['pickup_lng'],$data['drop_lat'],$data['drop_lng']);
            $pricePerKm = (int)Setting::getValue('price_per_km', env('PRICE_PER_KM',3000));
            $deliveryFee = (int)round($distanceKm * $pricePerKm);
            $adminPercent = (int)Setting::getValue('admin_fee_percent', env('ADMIN_FEE_PERCENT',10));
            $adminFee = (int)round($deliveryFee * ($adminPercent/100));

            $order = Order::create([
                'order_no'=>'ORD'.time().rand(100,999),
                'customer_id'=>$user->id,
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
                $product = Product::findOrFail($it['product_id']);
                OrderItem::create(['order_id'=>$order->id,'product_id'=>$product->id,'qty'=>$it['qty'],'price'=>$product->price]);
                $subtotal += $product->price * $it['qty'];
            }
            $order->update(['subtotal'=>$subtotal]);

            // Promo handling
            if (! empty($data['promo_code'])) {
                $promoService = app(\App\Services\PromoService::class);
                $pRes = $promoService->validatePromoForUser($data['promo_code'], $user, $subtotal);
                if (! $pRes['ok']) {
                    return response()->json(['message'=>$pRes['message']], 400);
                }

                // apply promo (creates usage and increments counter)
                $promoService->applyPromoToOrder($pRes['promo'], $order, $user);
                // Optionally we could store discount info in order meta or separate field
            }

            // Notify mitra
            $wa = app(\App\Services\WaService::class);
            $fcm = app(\App\Services\FcmService::class);
            $fcm->sendToUser($order->mitra->user_id, 'New Order', "Order {$order->order_no} masuk", ['order_id'=>$order->id]);
            $wa->send($order->mitra->phone ?? null, "Order {$order->order_no} masuk");

            return response()->json($order->load('items'));
        });
    }
}
