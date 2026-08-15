@props(['value'])

@if($value)

    <span style="color:green;">
        فعال
    </span>

@else

    <span style="color:red;">
        غیرفعال
    </span>

@endif