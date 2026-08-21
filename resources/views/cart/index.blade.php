@extends('layouts.panel')

@section('title', 'سبد خرید')

@section('page-title', 'سبد خرید')

@section('page-description', 'لیست محصولات انتخاب شده')


@section('content')

<x-card>

    <x-slot:title>
        سبد خرید
    </x-slot:title>


    @if(!$cart || $cart->cartItems->isEmpty())

        <div style="padding:20px;">
            سبد خرید شما خالی است.
        </div>

    @else


        <table width="100%" style="border-collapse:collapse;">

            <thead>

                <tr style="border-bottom:1px solid #ddd;">

                    <th style="padding:12px;">
                        محصول
                    </th>

                    <th style="padding:12px;">
                        تعداد
                    </th>

                    <th style="padding:12px;">
                        قیمت
                    </th>

                    <th style="padding:12px;">
                        مجموع
                    </th>

                    <th style="padding:12px;">
                        عملیات
                    </th>

                </tr>

            </thead>


            <tbody>


            @php
                $total = 0;
            @endphp


            @foreach($cart->cartItems as $item)


                @php
                    $subtotal = $item->price * $item->quantity;
                    $total += $subtotal;
                @endphp


                <tr style="border-bottom:1px solid #eee;">


                    <td style="padding:12px;">

                        {{ $item->product->title }}

                    </td>



                    <td style="padding:12px;">

                        <form
                            action="{{ route('cart.update', $item) }}"
                            method="POST"
                            style="display:flex; gap:8px; align-items:center;"
                        >

                            @csrf
                            @method('PUT')


                            <input
                                type="number"
                                name="quantity"
                                value="{{ $item->quantity }}"
                                min="1"
                                max="{{ $item->product->stock }}"
                                style="
                                    width:70px;
                                    padding:6px;
                                "
                            >


                            <button type="submit">
                                بروزرسانی
                            </button>


                        </form>

                    </td>



                    <td style="padding:12px;">

                        {{ number_format($item->price) }}

                    </td>



                    <td style="padding:12px;">

                        {{ number_format($subtotal) }}

                    </td>



                    <td style="padding:12px;">


                        <form
                            action="{{ route('cart.remove', $item) }}"
                            method="POST"
                        >

                            @csrf
                            @method('DELETE')


                            <button type="submit">
                                حذف از سبد
                            </button>


                        </form>


                    </td>


                </tr>


            @endforeach


            </tbody>


        </table>



        <div style="margin-top:20px;">


            <h3>
                مجموع کل:
                {{ number_format($total) }}
            </h3>



            <form
                action="{{ route('cart.clear') }}"
                method="POST"
            >

                @csrf
                @method('DELETE')


                <button type="submit">
                    حذف کامل سبد خرید
                </button>


            </form>


        </div>


    @endif


</x-card>


@endsection