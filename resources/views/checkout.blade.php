<form action="{{ route('checkout') }}" method="POST">
    @csrf

    <button type="submit">
        ثبت سفارش
    </button>
</form>