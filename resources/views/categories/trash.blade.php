@extends('layouts.panel')

@section('title', 'سطل زباله دسته‌بندی‌ها')

@section('page-title', 'سطل زباله')

@section('page-description', 'دسته‌بندی‌های حذف شده')


@section('content')

    @php
        $canRestore = auth()->user()->hasPermission('restore_category');

        $headers = ['#', 'عنوان', 'تاریخ حذف'];

        if ($canRestore) {
            $headers[] = 'عملیات';
        }
    @endphp


    <x-card>

        <x-slot:title>
            دسته‌بندی‌های حذف شده
        </x-slot:title>


        <x-slot:description>
            تعداد موارد حذف شده: {{ $categories->count() }}
        </x-slot:description>


        @if ($categories->count())

            <x-table :headers="$headers">

                @foreach ($categories as $category)
                    <tr style="border-bottom:1px solid #eee;">

                        <td style="padding:12px;">
                            {{ $categories->firstItem() + $loop->index }}
                        </td>


                        <td style="padding:12px;">
                            {{ $category->title }}
                        </td>


                        <td style="padding:12px;">
                            {{ $category->deleted_at }}
                        </td>


                        @if ($canRestore)
                            <td style="padding:12px;">

                                <x-can permission="restore_category">

                                    <form action="{{ route('categories.restore', $category) }}" method="POST"
                                        style="margin:0;">

                                        @csrf
                                        @method('PATCH')


                                        <x-button type="submit">
                                            بازیابی
                                        </x-button>

                                    </form>

                                </x-can>

                            </td>
                        @endif

                    </tr>
                @endforeach

            </x-table>


            <div style="margin-top:20px;">
                {{ $categories->links() }}
            </div>
        @else
            <p>
                سطل زباله خالی است.
            </p>

        @endif

    </x-card>

@endsection
