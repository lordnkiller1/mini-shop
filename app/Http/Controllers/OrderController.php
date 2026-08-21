<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(OrderService $orderService)
    {
        $order = $orderService->createFromCart(auth()->user());

        return redirect()
            ->route('orders.show', $order);
    }
}
