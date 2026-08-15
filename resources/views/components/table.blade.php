@props([
    'headers' => []
])


<table {{ $attributes->merge([
    'style' => 'width:100%; border-collapse: collapse;'
]) }}>

    <thead>

        <tr style="border-bottom:1px solid #ddd;">

            @foreach($headers as $header)

                <th style="padding:12px; text-align:right;">
                    {{ $header }}
                </th>

            @endforeach

        </tr>

    </thead>


    <tbody>

        {{ $slot }}

    </tbody>


</table>