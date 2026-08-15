@extends('layouts.panel')

@section('title', 'ویرایش دسته‌بندی')

@section('page-title', 'ویرایش دسته‌بندی')

@section('content')


<x-card>

    <x-slot:title>
        ویرایش دسته‌بندی
    </x-slot:title>


    <form
        action="{{ route('categories.update', $category->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PATCH')


        <div class="form-group">

            <label class="form-label">
                تصویر فعلی دسته‌بندی
            </label>


            @php
                $hasImage =
                    $category->image &&
                    $category->image->path &&
                    Storage::disk('public')->exists($category->image->path);
            @endphp


            @if ($hasImage)

                <img
                    src="{{ asset('storage/' . $category->image->path) }}"
                    alt="{{ $category->title }}"
                    width="70"
                    height="70"
                    style="object-fit:cover; border-radius:8px;"
                >

            @else

                <span style="color:red;">
                    بدون تصویر
                </span>

            @endif

        </div>


        <x-input
            name="image"
            type="file"
            label="تصویر جدید"
            class="form-input"
        />


        <x-input
            name="title"
            label="عنوان دسته‌بندی"
            :value="$category->title"
            class="form-input"
        />


        <x-select
            name="status"
            label="وضعیت"
            :options="[
                1 => 'فعال',
                0 => 'غیرفعال',
            ]"
            :value="$category->status"
        />


        <x-button
            type="submit"
            class="submit-button"
        >
            بروزرسانی
        </x-button>


    </form>

</x-card>


@endsection