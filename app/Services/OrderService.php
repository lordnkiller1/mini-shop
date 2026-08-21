<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createFromCart(User $user): Order
    {
        return DB::transaction(function () use ($user) {

            $cart = $user->cart;

            $totalPrice = $cart->cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });


            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => $totalPrice,
                'address' => $user->profile->address ?? null,
            ]);


            foreach ($cart->cartItems as $item) {

                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

            }


            $cart->cartItems()->delete();


            return $order;
        });
    }
}