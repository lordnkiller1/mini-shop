<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ورود | Mini Shop</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

    <main class="auth-page">

        <div class="auth-grid"></div>

        <div class="auth-glow glow-1"></div>
        <div class="auth-glow glow-2"></div>
        <div class="auth-glow glow-3"></div>

        <div class="login-wrapper">

            {{ $slot }}

        </div>

    </main>

</body>
</html>