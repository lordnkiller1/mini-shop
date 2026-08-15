@extends('layouts.panel')

@section('title', 'ایجاد دسته‌بندی')

@section('page-title', 'ایجاد دسته‌بندی')

@section('page-description', 'یک دسته‌بندی جدید برای محصولات فروشگاه بسازید')

@section('content')


<x-card>

    <x-slot:title>
        اطلاعات دسته‌بندی
    </x-slot:title>

    <x-slot:description>
        عنوان و وضعیت دسته‌بندی را وارد کنید.
    </x-slot:description>


    <form
        action="{{ route('categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <x-input
            name="image"
            type="file"
            label="تصویر دسته‌بندی"
            class="form-input"
        />


        <x-input
            name="title"
            label="عنوان دسته‌بندی"
            placeholder="مثلاً موبایل و تبلت"
            class="form-input"
        />


        <x-select
            name="status"
            label="وضعیت دسته‌بندی"
            :options="[
                1 => 'فعال',
                0 => 'غیرفعال',
            ]"
            :value="1"
        />


        <div class="form-actions">

            <x-button
                type="submit"
                class="submit-button"
            >
                ذخیره دسته‌بندی
            </x-button>


            <a
                href="{{ route('categories.index') }}"
                class="cancel-button"
            >
                انصراف
            </a>

        </div>


    </form>

</x-card>


@endsection