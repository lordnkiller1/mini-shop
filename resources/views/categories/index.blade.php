@extends('layouts.panel')

@section('title', 'دسته‌بندی‌ها')

@section('page-title', 'مدیریت دسته‌بندی‌ها')

@section('page-description', 'لیست دسته‌بندی‌های فروشگاه')


@section('actions')

    <x-can permission="create_category">

        <x-button href="{{ route('categories.create') }}">
            دسته‌بندی جدید
        </x-button>

    </x-can>

@endsection


@section('content')

    @php
        $canManage = auth()->user()->hasPermission('edit_category') || auth()->user()->hasPermission('delete_category');

        $headers = ['ردیف', 'تصویر', 'عنوان', 'وضعیت'];

        if ($canManage) {
            $headers[] = 'عملیات';
        }
    @endphp


    <x-card>

        <x-slot:title>
            لیست دسته‌بندی‌ها
        </x-slot:title>


        <x-slot:description>
            تعداد دسته‌ها: {{ $categories->total() }}
        </x-slot:description>


        {{-- Search & Filter --}}

        <form action="{{ route('categories.index') }}" method="GET"
            style="
                display:flex;
                gap:12px;
                align-items:flex-end;
                flex-wrap:wrap;
                margin-bottom:20px;
            ">

            <div style="min-width:220px; flex:1;">

                <x-input name="search" label="جستجوی دسته‌بندی" placeholder="مثلاً موبایل" :value="request('search')" />

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

                <a href="{{ route('categories.index') }}" class="cancel-button btn btn-primary">
                    پاک کردن
                </a>

            </div>

        </form>


        @if ($categories->count())

            <x-table :headers="$headers">

                @foreach ($categories as $category)
                    @php
                        $hasImage =
                            $category->image &&
                            $category->image->path &&
                            Storage::disk('public')->exists($category->image->path);
                    @endphp


                    <tr style="border-bottom:1px solid #eee;">

                        <td style="padding:12px;">
                            {{ $categories->firstItem() + $loop->index }}
                        </td>


                        <td style="padding:12px;">

                            @if ($hasImage)
                                <img src="{{ asset('storage/' . $category->image->path) }}" alt="{{ $category->title }}"
                                    width="70" height="70" style="object-fit:cover; border-radius:8px;">
                            @else
                                <span style="color:red;">
                                    بدون تصویر
                                </span>
                            @endif

                        </td>


                        <td style="padding:12px;">
                            {{ $category->title }}
                        </td>


                        <td style="padding:12px;">
                            <x-status :value="$category->status" />
                        </td>


                        <x-can :any="['edit_category', 'delete_category']">

                            <td style="padding:12px;">

                                <x-can permission="edit_category">

                                    <x-button href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                                        ویرایش
                                    </x-button>

                                </x-can>


                                <x-can permission="delete_category">

                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
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
        @else
            <p>
                دسته‌بندی‌ای پیدا نشد.
            </p>

        @endif


        <div style="margin-top:20px;">
            {{ $categories->links() }}
        </div>

    </x-card>

@endsection
