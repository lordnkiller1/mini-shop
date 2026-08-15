@extends('layouts.panel')

@section('title', 'کاربر جدید')

@section('page-title', 'ایجاد کاربر')

@section('page-description', 'ثبت کاربر جدید و تعیین نقش')


@section('content')

    <x-card>

        <x-slot:title>
            ایجاد کاربر جدید
        </x-slot:title>


        <form
            action="{{ route('users.store') }}"
            method="POST"
        >

            @csrf


            <x-input
                name="name"
                label="نام"
                placeholder="نام کاربر"
            />


            <x-input
                type="email"
                name="email"
                label="ایمیل"
                placeholder="example@email.com"
            />


            <x-input
                type="password"
                name="password"
                label="رمز عبور"
                placeholder="حداقل 8 کاراکتر"
            />


            <x-input
                type="password"
                name="password_confirmation"
                label="تکرار رمز عبور"
                placeholder="رمز عبور را دوباره وارد کنید"
            />


            <x-select
                name="roles[]"
                label="نقش"
                :options="$roles->pluck('name', 'id')"
                :value="old('roles', [])"
                multiple
            />


            <x-button type="submit">
                ایجاد کاربر
            </x-button>

        </form>

    </x-card>

@endsection