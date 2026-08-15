@extends('layouts.panel')

@section('title', 'ویرایش کاربر')

@section('page-title', 'ویرایش کاربر')

@section('page-description', 'مدیریت نقش‌های کاربر')

@section('content')


<x-card>

    <x-slot:title>
        ویرایش {{ $user->name }}
    </x-slot:title>


    <form
        action="{{ route('users.update', $user) }}"
        method="POST"
    >

        @csrf
        @method('PATCH')


        <x-input
            type="text"
            name="name"
            label="نام"
            :value="$user->name"
            class="form-input"
        />


        <x-input
            type="email"
            name="email"
            label="ایمیل"
            :value="$user->email"
            class="form-input"
        />


        <x-select
            name="roles[]"
            label="نقش‌ها"
            :options="$roles->pluck('name', 'id')"
            :value="$user->roles->pluck('id')->toArray()"
            multiple
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