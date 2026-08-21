<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'محصول حذف شد');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);


        // امنیت: آیتم متعلق به کاربر باشد
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }


        // بررسی موجودی
        if ($request->quantity > $cartItem->product->stock) {

            return back()->with(
                'error',
                'تعداد وارد شده بیشتر از موجودی محصول است'
            );
        }


        $cartItem->update([
            'quantity' => $request->quantity,
        ]);


        return back()->with(
            'success',
            'سبد خرید بروزرسانی شد'
        );
    }

    public function clear()
    {
        $cart = auth()->user()->cart;

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return back()->with('success', 'سبد خرید خالی شد');
    }
    public function index()
    {
        $cart = auth()->user()
            ->cart()
            ->with('cartItems.product')
            ->first();

        return view('cart.index', compact('cart'));
    }
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);


        if ($request->quantity > $product->stock) {
            return back()->with('error', 'موجودی محصول کافی نیست');
        }


        $cart = auth()->user()->cart;


        if (!$cart) {
            $cart = auth()->user()->cart()->create();
        }


        $cartItem = $cart->cartItems()
            ->where('product_id', $product->id)
            ->first();


        if ($cartItem) {

            $quantity = $cartItem->quantity + $request->quantity;


            if ($quantity > $product->stock) {
                return back()->with('error', 'موجودی محصول کافی نیست');
            }


            $cartItem->update([
                'quantity' => $quantity,
            ]);
        } else {

            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }


        return back()->with('success', 'محصول به سبد خرید اضافه شد');
    }
}
