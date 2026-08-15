<div class="card">

    @isset($title)
        <div class="card-header">

            <h2>
                {{ $title }}
            </h2>

            @isset($description)
                <p>
                    {{ $description }}
                </p>
            @endisset

        </div>
    @endisset


    <div class="card-body">

        {{ $slot }}

    </div>

</div>