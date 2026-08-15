@extends('layouts.panel')

@section('title', 'کاربران')

@section('page-title', 'مدیریت کاربران')

@section('page-description', 'لیست کاربران سیستم')

@section('actions')

    <x-can permission="create_user">

        <x-button href="{{ route('users.create') }}">
            کاربر جدید
        </x-button>

    </x-can>

@endsection

@section('content')


    @php
        $canManage = auth()->user()->hasPermission('edit_user') || auth()->user()->hasPermission('delete_user');

        $headers = ['ردیف', 'نام', 'ایمیل', 'نقش‌ها', 'تاریخ ثبت‌نام'];

        if ($canManage) {
            $headers[] = 'عملیات';
        }
    @endphp


    <x-card>

        <x-slot:title>
            لیست کاربران
        </x-slot:title>


        <x-slot:description>
            تعداد کاربران: {{ $users->total() }}
        </x-slot:description>


        {{-- Search & Filter --}}

        <form action="{{ route('users.index') }}" method="GET"
            style="
                display:flex;
                gap:12px;
                align-items:flex-end;
                flex-wrap:wrap;
                margin-bottom:20px;
            ">

            <div style="min-width:240px; flex:1;">

                <x-input name="search" label="جستجوی کاربر" placeholder="نام یا ایمیل" :value="request('search')" />

            </div>


            <div style="min-width:180px;">

                <x-select name="role_id" label="نقش" placeholder="همه نقش‌ها" :options="$roles->pluck('name', 'id')" :value="request('role_id')" />

            </div>


            <div style="display:flex; gap:8px; margin-bottom:15px;">

                <x-button type="submit">
                    اعمال فیلتر
                </x-button>

                <a href="{{ route('users.index') }}" class="cancel-button btn btn-primary">
                    پاک کردن
                </a>

            </div>

        </form>


        @if ($users->count())

            <x-table :headers="$headers">

                @foreach ($users as $user)
                    <tr style="border-bottom:1px solid #eee;">


                        <td style="padding:12px;">
                            {{ $users->firstItem() + $loop->index }}
                        </td>


                        <td style="padding:12px;">
                            {{ $user->name }}
                        </td>


                        <td style="padding:12px;">
                            {{ $user->email }}
                        </td>


                        <td style="padding:12px;">

                            @forelse ($user->roles as $role)
                                <span>
                                    {{ $role->name }}
                                </span>

                                @if (!$loop->last)
                                    ,
                                @endif

                            @empty

                                بدون نقش
                            @endforelse

                        </td>


                        <td style="padding:12px;">
                            {{ $user->created_at?->format('Y-m-d') }}
                        </td>


                        <x-can :any="['edit_user', 'delete_user']">

                            <td style="padding:12px;">


                                <x-can permission="edit_user">

                                    <x-button href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                                        ویرایش
                                    </x-button>

                                </x-can>


                                <x-can permission="delete_user">

                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        style="display:inline;">

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
                {{ $users->links() }}
            </div>
        @else
            <p>
                کاربری پیدا نشد.
            </p>

        @endif


    </x-card>

@endsection
