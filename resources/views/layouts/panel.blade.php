<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'پنل مدیریت') | Mini Shop</title>

    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
</head>

<body>

    <div class="panel-layout">


        <aside class="panel-sidebar">


            <div class="panel-brand">

                <h1>
                    Mini Shop
                </h1>

                <span>
                    پنل مدیریت فروشگاه
                </span>

            </div>



            <nav class="panel-menu">


                {{-- Dashboard --}}

                <x-can permission="view_dashboard">

                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        داشبورد
                    </a>

                </x-can>



                {{-- Users --}}

                <x-can permission="view_user">

                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        کاربران
                    </a>

                </x-can>



                {{-- Categories --}}

                <x-can permission="view_category">

                    <a href="{{ route('categories.index') }}"
                        class="{{ request()->routeIs('categories.*') && !request()->routeIs('categories.trash') ? 'active' : '' }}">
                        دسته‌بندی‌ها
                    </a>

                </x-can>



                {{-- Products --}}

                <x-can permission="view_product">

                    <a href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        محصولات
                    </a>

                </x-can>



                {{-- Trash --}}

                <x-can permission="restore_category">

                    <a href="{{ route('categories.trash') }}"
                        class="{{ request()->routeIs('categories.trash') ? 'active danger-active' : '' }}">
                        سطل زباله
                    </a>

                </x-can>


            </nav>




            <div class="panel-user">


                <div class="user-info">

                    <strong>
                        {{ auth()->user()?->name ?? 'کاربر' }}
                    </strong>


                    <span>
                        {{ auth()->user()?->email }}
                    </span>

                </div>




                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit" class="logout-button">
                        خروج از حساب
                    </button>

                </form>


            </div>


        </aside>





        <section class="panel-main">


            <header class="panel-header">


                <div>

                    <h2>
                        @yield('page-title', 'پنل مدیریت')
                    </h2>


                    <p>
                        @yield('page-description', 'مدیریت اطلاعات فروشگاه')
                    </p>

                </div>



                <div class="panel-actions">

                    @yield('actions')

                </div>


            </header>





            <main class="panel-content">


                <x-alert />

                @yield('content')


            </main>


        </section>


    </div>


</body>

</html>
