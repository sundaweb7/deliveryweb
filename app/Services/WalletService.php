<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function ensureWalletForOwner($owner)
    {
        if (! $owner->wallet) {
            $wallet = new Wallet(['balance'=>0]);
            $owner->wallet()->save($wallet);
            return $wallet;
        }
        return $owner->wallet;
    }

    public function credit(Wallet $wallet, int $amount, string $reference = null, array $meta = [])
    {
        return DB::transaction(function () use ($wallet, $amount, $reference, $meta) {
            $wallet->balance += $amount;
            $wallet->save();
            $history = WalletHistory::create(['wallet_id'=>$wallet->id,'type'=>'credit','amount'=>$amount,'reference'=>$reference,'meta'=>$meta]);
            // log system
            $ownerUserId = $wallet->owner->user_id ?? $wallet->owner->id ?? null;
            app(\App\Services\SystemLogService::class)->log($ownerUserId, 'wallet_credit', "Credit {$amount} to wallet {$wallet->id}");
            return $history;
        });
    }

    public function debit(Wallet $wallet, int $amount, string $reference = null, array $meta = [])
    {
        return DB::transaction(function () use ($wallet, $amount, $reference, $meta) {
            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }
            $wallet->balance -= $amount;
            $wallet->save();
            $history = WalletHistory::create(['wallet_id'=>$wallet->id,'type'=>'debit','amount'=>$amount,'reference'=>$reference,'meta'=>$meta]);
            // log system
            $ownerUserId = $wallet->owner->user_id ?? $wallet->owner->id ?? null;
            app(\App\Services\SystemLogService::class)->log($ownerUserId, 'wallet_debit', "Debit {$amount} from wallet {$wallet->id}");
            return $history;
        });
    }

    /**
     * Distribusi earnings setelah order delivered:
     * - mitra mendapat subtotal
     * - admin mendapat admin_fee (ditampung di admin wallet - user role admin)
     * - driver mendapat delivery_fee - admin_fee
     */
    public function distributeEarnings(Order $order)
    {
        return DB::transaction(function () use ($order) {
            // Mitra
            $mitraUser = $order->mitra->user;
            $mitraWallet = $this->ensureWalletForOwner($mitraUser);
            if ($order->subtotal > 0) {
                $this->credit($mitraWallet, (int)$order->subtotal, 'order_subtotal_'.$order->order_no, ['order_id'=>$order->id]);
            }

            // Admin (credit admin_fee)
            $adminFee = (int)$order->admin_fee;
            if ($adminFee > 0) {
                $adminUser = User::where('role','admin')->first();
                if ($adminUser) {
                    $adminWallet = $this->ensureWalletForOwner($adminUser);
                    $this->credit($adminWallet, $adminFee, 'admin_fee_'.$order->order_no, ['order_id'=>$order->id]);
                }
            }

            // Driver
            if ($order->driver) {
                $driverUser = $order->driver->user;
                $driverWallet = $this->ensureWalletForOwner($driverUser);
                $driverAmount = max(0, (int)$order->delivery_fee - (int)$order->admin_fee);
                if ($driverAmount > 0) {
                    $this->credit($driverWallet, $driverAmount, 'driver_fee_'.$order->order_no, ['order_id'=>$order->id]);
                }
            }
        });
    }
}
