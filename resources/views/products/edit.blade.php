@extends('layouts.panel')

@section('content')


<x-card>

    <x-slot:title>
        ویرایش محصول
    </x-slot:title>


    <form
        action="{{ route('products.update', $product) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PATCH')


        <div class="form-group">

            <label class="form-label">
                تصویر فعلی محصول
            </label>


            @php
            $hasImage =
            $product->image &&
            $product->image->path &&
            Storage::disk('public')->exists($product->image->path);
            @endphp


            @if ($hasImage)

            <img
                src="{{ asset('storage/' . $product->image->path) }}"
                alt="{{ $product->title }}"
                width="70"
                height="70"
                style="object-fit:cover; border-radius:8px;">

            @else

            <span style="color:red;">
                بدون تصویر
            </span>

            @endif

        </div>


        <x-input
            name="image"
            label="تصویر جدید"
            type="file"
            class="form-input" />


        <x-input
            name="title"
            label="عنوان محصول"
            placeholder="مثلا آیفون 15"
            :value="$product->title"
            class="form-input" />


        <x-input
            type="number"
            name="price"
            label="قیمت"
            placeholder="قیمت محصول"
            :value="$product->price"
            class="form-input" />


        <x-input
            name="stock"
            label="موجودی"
            type="number"
            :value="$product->stock" />

        <x-select
            name="category_id"
            label="دسته بندی"
            placeholder="انتخاب دسته بندی"
            :options="$categories->pluck('title', 'id')"
            :value="$product->category_id" />


        <x-select
            name="status"
            label="وضعیت"
            :options="[
                1 => 'فعال',
                0 => 'غیرفعال',
            ]"
            :value="$product->status" />


        <x-button
            type="submit"
            class="submit-button">
            بروزرسانی محصول
        </x-button>


    </form>

</x-card>


@endsection