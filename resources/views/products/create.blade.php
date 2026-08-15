@extends('layouts.panel')

@section('content')


<x-card>

    <x-slot:title>
        ایجاد محصول جدید
    </x-slot:title>


    <form
        action="{{ route('products.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <x-input
            type="file"
            label="تصویر محصول"
            name="image"
            class="form-input"
        />


        <x-input
            name="title"
            label="عنوان محصول"
            placeholder="مثلا آیفون 15"
            class="form-input"
        />


        <x-input
            type="number"
            name="price"
            label="قیمت"
            placeholder="قیمت محصول"
            class="form-input"
        />


        <x-select
            name="category_id"
            label="دسته بندی"
            placeholder="انتخاب دسته بندی"
            :options="$categories->pluck('title', 'id')"
        />


        <x-select
            name="status"
            label="وضعیت"
            :options="[
                1 => 'فعال',
                0 => 'غیرفعال',
            ]"
            :value="1"
        />


        <x-button
            type="submit"
            class="submit-button"
        >
            ذخیره محصول
        </x-button>


    </form>

</x-card>


@endsection