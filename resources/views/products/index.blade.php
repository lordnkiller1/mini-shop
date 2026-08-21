@extends('layouts.panel')

@section('title', 'محصولات')

@section('page-title', 'مدیریت محصولات')

@section('page-description', 'لیست محصولات ثبت‌شده در فروشگاه')


@section('actions')

<x-can permission="create_product">

    <x-button href="{{ route('products.create') }}" class="btn btn-primary">
        محصول جدید
    </x-button>

</x-can>

@endsection


@section('content')


@php
$canManage = auth()->user()->hasPermission('edit_product') || auth()->user()->hasPermission('delete_product');

$headers = ['ردیف', 'تصویر محصول', 'نام محصول', 'دسته‌بندی','تعداد', 'قیمت', 'وضعیت','سفارش'];

if ($canManage) {
$headers[] = 'عملیات';
}
@endphp


<x-card>

    <x-slot:title>
        لیست محصولات
    </x-slot:title>


    <x-slot:description>
        تعداد محصولات: {{ $products->count() }}
    </x-slot:description>

    <form action="{{ route('products.index') }}" method="GET"
        style="
        display:flex;
        gap:12px;
        align-items:flex-end;
        flex-wrap:wrap;
        margin-bottom:20px;
    ">

        <div style="min-width:220px; flex:1;">

            <x-input name="search" label="جستجوی محصول" placeholder="مثلاً آیفون" :value="request('search')" />

        </div>


        <div style="min-width:180px;">

            <x-select name="category_id" label="دسته‌بندی" placeholder="همه دسته‌بندی‌ها" :options="$categories->pluck('title', 'id')"
                :value="request('category_id')" />

        </div>


        <div style="min-width:160px;">

            <x-select name="status" label="وضعیت" placeholder="همه وضعیت‌ها" :options="[
                    1 => 'فعال',
                    0 => 'غیرفعال',
                ]" :value="request('status')" />

        </div>


        <div style="display:flex; gap:8px; margin-bottom:15px;">

            <x-button type="submit">
                اعمال فیلتر
            </x-button>


            <a href="{{ route('products.index') }}" class="cancel-button btn btn-primary">
                پاک کردن
            </a>

        </div>

    </form>

    <x-table :headers="$headers">


        @foreach ($products as $product)
        <tr style="border-bottom:1px solid #eee;">


            <td style="padding:12px;">
                {{ $products->firstItem() + $loop->index }}
            </td>


            <td style="padding:12px;">

                @php
                $hasImage =
                $product->image &&
                $product->image->path &&
                Storage::disk('public')->exists($product->image->path);
                @endphp


                @if ($hasImage)
                <img src="{{ asset('storage/' . $product->image->path) }}" alt="{{ $product->title }}"
                    width="70" height="70" style="object-fit:cover; border-radius:8px;">
                @else
                <span style="color:red;">
                    بدون تصویر
                </span>
                @endif

            </td>


            <td style="padding:12px;">

                <a href="{{ route('products.comments.index', $product) }}">
                    {{ $product->title }}
                </a>

            </td>


            <td style="padding:12px;">
                {{ $product->category->title }}
            </td>
            
            <td style="padding:12px;">
                {{ $product->stock }}
            </td>

            <td style="padding:12px;">
                {{ number_format($product->price) }}
            </td>


            <td style="padding:12px;">
                <x-status :value="$product->status" />
            </td>


             <td style="padding:12px;">

                <form action="{{ route('cart.add', $product) }}" method="POST"
                    style="display:flex; gap:8px; align-items:center;">

                    @csrf

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="{{ $product->stock }}"
                        style="
                width:70px;
                padding:6px;
                border:1px solid #ddd;
                border-radius:6px;
            ">

                    <button type="submit" class="btn btn-primary">
                        افزودن به سبد خرید
                    </button>

                </form>

            </td>


            <x-can :any="['edit_product', 'delete_product']">

                <td style="padding:12px;">


                    <x-can permission="edit_product">

                        <x-button href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                            ویرایش
                        </x-button>

                    </x-can>


                    <x-can permission="delete_product">

                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                            style="display:inline-block;">

                            @csrf
                            @method('DELETE')


                            <x-button type="submit">
                                حذف
                            </x-button>

                        </form>

                    </x-can>


                </td>



            </x-can>

           


        </tr>
        @endforeach


    </x-table>

    <div style="margin-top:20px;">
        {{ $products->links() }}
    </div>
</x-card>


@endsection