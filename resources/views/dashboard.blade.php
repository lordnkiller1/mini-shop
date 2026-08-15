@extends('layouts.panel')


@section('title', 'داشبورد')


@section('page-title', 'داشبورد')


@section('page-description', 'نمای کلی مدیریت فروشگاه')



@section('content')


{{-- Statistics Cards --}}

<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
">


@php

$cards = [

[
'title'=>'کاربران',
'value'=>$stats['users'],
'desc'=>'مدیریت کاربران سیستم',
'url'=>route('users.index')
],

[
'title'=>'محصولات',
'value'=>$stats['products'],
'desc'=>'مدیریت محصولات فروشگاه',
'url'=>route('products.index')
],

[
'title'=>'دسته‌بندی‌ها',
'value'=>$stats['categories'],
'desc'=>'مدیریت دسته‌بندی‌ها',
'url'=>route('categories.index')
],

[
'title'=>'کامنت‌ها',
'value'=>$stats['comments'],
'desc'=>'مدیریت نظرات',
'url'=>route('products.index')
],

];

@endphp



@foreach($cards as $card)


<a href="{{ $card['url'] }}"
style="
text-decoration:none;
color:inherit;
transition:.2s;
">


<x-card>


<x-slot:title>
{{ $card['title'] }}
</x-slot:title>


<div style="
font-size:42px;
font-weight:800;
margin-top:20px;
">


{{ $card['value'] }}


</div>


<p style="
margin-top:12px;
opacity:.7;
">


{{ $card['desc'] }}


</p>


</x-card>


</a>


@endforeach


</div>






{{-- Latest Products --}}


<x-card>


<x-slot:title>
آخرین محصولات
</x-slot:title>


<x-slot:description>
مشاهده سریع محصولات اخیر
</x-slot:description>



<x-table :headers="[

'عنوان',

'دسته‌بندی',

'قیمت',

'تاریخ'

]">


@forelse($latestProducts as $product)


<tr style="border-bottom:1px solid #eee;">


<td style="padding:12px;">


<a
href="{{ route('products.edit',$product) }}"
style="
color:inherit;
text-decoration:none;
font-weight:600;
"
>

{{ $product->title }}

</a>


</td>



<td style="padding:12px;">

{{ $product->category->title ?? '-' }}

</td>



<td style="padding:12px;">

{{ number_format($product->price) }}

</td>



<td style="padding:12px;">

{{ $product->created_at?->format('Y-m-d') }}

</td>


</tr>


@empty


<tr>

<td colspan="4">

محصولی وجود ندارد

</td>

</tr>


@endforelse


</x-table>


</x-card>







<div style="height:25px;"></div>







<div style="
display:grid;
grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
gap:20px;
">





{{-- Users --}}


<x-card>


<x-slot:title>
آخرین کاربران
</x-slot:title>



<x-table :headers="[

'نام',

'ایمیل'

]">


@forelse($latestUsers as $user)


<tr style="border-bottom:1px solid #eee;">


<td style="padding:12px;">


<a
href="{{ route('users.edit',$user) }}"
style="
color:inherit;
text-decoration:none;
font-weight:600;
"
>

{{ $user->name }}

</a>


</td>


<td style="padding:12px;">

{{ $user->email }}

</td>


</tr>



@empty


<tr>

<td colspan="2">

کاربری وجود ندارد

</td>

</tr>


@endforelse


</x-table>


</x-card>







{{-- Comments --}}


<x-card>


<x-slot:title>
آخرین کامنت‌ها
</x-slot:title>



<x-table :headers="[

'کاربر',

'محصول',

'متن'

]">


@forelse($latestComments as $comment)


<tr style="border-bottom:1px solid #eee;">


<td style="padding:12px;">

{{ $comment->user->name ?? '-' }}

</td>


<td style="padding:12px;">


<a
href="{{ route('products.comments.index',$comment->product) }}"
style="
color:inherit;
text-decoration:none;
font-weight:600;
"
>

{{ $comment->product->title ?? '-' }}

</a>


</td>


<td style="
padding:12px;
max-width:250px;
">


{{ Str::limit($comment->body,40) }}


</td>


</tr>



@empty


<tr>

<td colspan="3">

کامنتی وجود ندارد

</td>

</tr>


@endforelse


</x-table>


</x-card>




</div>



@endsection