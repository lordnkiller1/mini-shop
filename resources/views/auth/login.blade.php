<x-guest-layout>

    <div class="login-card">

        <div class="login-logo">
            MS
        </div>

        <div class="login-header">

            <h1>
                ورود به پنل مدیریت
            </h1>

            <span>
                Mini Shop
            </span>

        </div>


        <x-auth-session-status
            class="session-status"
            :status="session('status')"
        />


        <form method="POST" action="{{ route('login') }}">

            @csrf


            {{-- Email --}}

            <div class="form-group">

                <label for="email">
                    ایمیل
                </label>

                <div class="input-box">

                    <span class="input-icon">
                        @
                    </span>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        autocomplete="username"
                        autofocus
                        required
                        dir="ltr"
                    >

                </div>

                @error('email')

                    <span class="form-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Password --}}

            <div class="form-group">

                <label for="password">
                    رمز عبور
                </label>

                <div class="input-box">

                    <span class="input-icon">
                        ●
                    </span>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="رمز عبور"
                        autocomplete="current-password"
                        required
                    >

                </div>

                @error('password')

                    <span class="form-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Options --}}

            <div class="form-options">

                <label class="remember">

                    <input
                        type="checkbox"
                        name="remember"
                        @checked(old('remember'))
                    >

                    <span>
                        مرا به خاطر بسپار
                    </span>

                </label>


                @if (Route::has('password.request'))

                    <a href="{{ route('password.request') }}">
                        فراموشی رمز عبور
                    </a>

                @endif

            </div>


            <button type="submit" class="login-button">

                <span>
                    ورود
                </span>

                <span class="button-arrow">
                    ←
                </span>

            </button>

        </form>


        <div class="login-bottom">

            <span></span>

            <small>
                Mini Shop
            </small>

            <span></span>

        </div>

    </div>

</x-guest-layout>