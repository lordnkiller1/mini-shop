@extends('layouts.panel')

@section('title', 'کامنت‌های محصول')

@section('page-title', 'کامنت‌های محصول')

@section('page-description', 'مدیریت نظرات ثبت‌شده برای محصول')


@section('content')


@php
    $canUpdateStatus = auth()->user()->hasPermission('update_comment_status');
    $canDeleteComment = auth()->user()->hasPermission('delete_comment');
    $canManage = $canUpdateStatus || $canDeleteComment;

    $headers = [
        '#',
        'کاربر',
        'متن کامنت',
        'وضعیت',
        'تاریخ',
    ];

    if ($canManage) {
        $headers[] = 'مدیریت';
    }
@endphp


<x-card>

    <x-slot:title>
        کامنت‌های {{ $product->title }}
    </x-slot:title>


    <x-slot:description>
        تعداد کامنت‌ها: {{ $comments->total() }}
    </x-slot:description>



    {{-- فرم ثبت کامنت --}}

    <form
        action="{{ route('products.comments.store', $product) }}"
        method="POST"
        style="margin-bottom:25px;"
    >

        @csrf


        <div class="form-group">

            <label class="form-label">
                متن کامنت
            </label>


            <textarea
                name="body"
                class="form-input"
                rows="4"
                placeholder="نظر خود را بنویسید..."
            >{{ old('body') }}</textarea>


            @error('body')

                <span class="validation-error">
                    {{ $message }}
                </span>

            @enderror

        </div>


        <x-button type="submit">
            ثبت کامنت
        </x-button>

    </form>



    {{-- Search & Filter --}}

    <form
        action="{{ route('products.comments.index', $product) }}"
        method="GET"
        style="
            display:flex;
            gap:12px;
            align-items:flex-end;
            flex-wrap:wrap;
            margin-bottom:25px;
        "
    >

        <div style="min-width:240px; flex:1;">

            <x-input
                name="search"
                label="جستجو در کامنت‌ها"
                placeholder="متن کامنت..."
                :value="request('search')"
            />

        </div>


        <div style="min-width:180px;">

            <x-select
                name="status"
                label="وضعیت"
                placeholder="همه وضعیت‌ها"
                :options="\App\Enums\CommentStatus::options()"
                :value="request('status')"
            />

        </div>


        <div style="display:flex; gap:8px; margin-bottom:15px;">

            <x-button type="submit">
                اعمال فیلتر
            </x-button>


            <a
                href="{{ route('products.comments.index', $product) }}"
                class="cancel-button btn btn-primary"
            >
                پاک کردن
            </a>

        </div>

    </form>



    {{-- لیست کامنت‌ها --}}

    @if ($comments->count())


        <x-table :headers="$headers">


            @foreach ($comments as $comment)

                <tr style="border-bottom:1px solid #eee;">


                    <td style="padding:12px;">
                        {{ $comment->id }}
                    </td>


                    <td style="padding:12px;">
                        {{ $comment->user->name }}
                    </td>


                    <td
                        style="
                            padding:12px;
                            max-width:350px;
                            line-height:1.8;
                        "
                    >
                        {{ $comment->body }}
                    </td>


                    <td style="padding:12px;">

                        @php
                            $statusStyle = match ($comment->status->value) {
                                1 => 'background:#dcfce7; color:#166534;',
                                2 => 'background:#fee2e2; color:#991b1b;',
                                default => 'background:#fef3c7; color:#92400e;',
                            };
                        @endphp


                        <span
                            style="
                                {{ $statusStyle }}
                                display:inline-block;
                                padding:5px 10px;
                                border-radius:20px;
                                font-size:12px;
                                font-weight:600;
                                white-space:nowrap;
                            "
                        >
                            {{ $comment->status->label() }}
                        </span>

                    </td>


                    <td
                        style="
                            padding:12px;
                            white-space:nowrap;
                        "
                    >
                        {{ $comment->created_at?->format('Y-m-d') }}
                    </td>



                    @if ($canManage)

                        <td style="padding:12px;">

                            <div
                                style="
                                    display:flex;
                                    align-items:center;
                                    gap:8px;
                                    flex-wrap:wrap;
                                "
                            >


                                {{-- تغییر وضعیت --}}

                                <x-can permission="update_comment_status">

                                    <form
                                        action="{{ route('products.comments.update', [$product, $comment]) }}"
                                        method="POST"
                                        style="
                                            display:flex;
                                            align-items:center;
                                            gap:8px;
                                            margin:0;
                                        "
                                    >

                                        @csrf
                                        @method('PATCH')


                                        <select
                                            name="status"
                                            class="form-input"
                                            style="
                                                width:auto;
                                                min-width:125px;
                                                margin:0;
                                            "
                                        >

                                            @foreach (\App\Enums\CommentStatus::cases() as $status)

                                                <option
                                                    value="{{ $status->value }}"
                                                    @selected($comment->status === $status)
                                                >
                                                    {{ $status->label() }}
                                                </option>

                                            @endforeach

                                        </select>


                                        <button
                                            type="submit"
                                            class="submit-button"
                                            style="
                                                margin:0;
                                                white-space:nowrap;
                                            "
                                        >
                                            ذخیره
                                        </button>

                                    </form>

                                </x-can>



                                {{-- حذف کامنت --}}

                                <x-can permission="delete_comment">

                                    <form
                                        action="{{ route('products.comments.destroy', [$product, $comment]) }}"
                                        method="POST"
                                        style="margin:0;"
                                        onsubmit="return confirm('آیا از حذف این کامنت مطمئن هستید؟')"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            style="
                                                background:#dc2626;
                                                color:white;
                                                border:none;
                                                border-radius:7px;
                                                padding:9px 14px;
                                                cursor:pointer;
                                                white-space:nowrap;
                                            "
                                        >
                                            حذف
                                        </button>

                                    </form>

                                </x-can>


                            </div>

                        </td>

                    @endif


                </tr>

            @endforeach


        </x-table>


        <div style="margin-top:20px;">
            {{ $comments->links() }}
        </div>


    @else

        <p style="padding:20px 0;">
            نتیجه‌ای برای جستجو یا فیلتر انتخاب‌شده پیدا نشد.
        </p>

    @endif


</x-card>


@endsection