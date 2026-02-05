<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order)
    {
        return $user->role === 'admin' || $user->id === $order->customer_id || ($order->driver && $order->driver->user_id === $user->id) || ($order->mitra && $order->mitra->user_id === $user->id);
    }

    public function update(User $user, Order $order)
    {
        // Admin or mitra or driver assigned can update under certain role
        if ($user->role === 'admin') return true;
        if ($user->role === 'mitra' && $order->mitra && $order->mitra->user_id === $user->id) return true;
        if ($user->role === 'driver' && $order->driver && $order->driver->user_id === $user->id) return true;
        return false;
    }

    public function assignDriver(User $user)
    {
        return $user->role === 'admin';
    }

    public function accept(User $user, Order $order)
    {
        return $user->role === 'driver' && $order->driver && $order->driver->user_id === $user->id;
    }

    public function cancel(User $user, Order $order)
    {
        return $user->role === 'admin' || $user->id === $order->customer_id;
    }
}
