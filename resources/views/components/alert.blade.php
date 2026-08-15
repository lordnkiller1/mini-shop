@if(session('success'))

<div id="session-alert" class="alert-success">
    {{ session('success') }}
</div>

@endif


@if(session('error'))

<div id="session-alert" class="alert-error">
    {{ session('error') }}
</div>

@endif


<script>
    setTimeout(() => {
        const alert = document.getElementById('session-alert');

        if (alert) {
            alert.style.opacity = '0';

            setTimeout(() => {
                alert.remove();
            }, 500);
        }
    }, 2000);
</script>