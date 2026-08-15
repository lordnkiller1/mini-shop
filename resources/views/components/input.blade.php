@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
])


<div class="form-group">

    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>


    <input
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"

        @if ($type !== 'file')
            value="{{ old($name, $value) }}"
        @endif

        {{ $attributes->merge([
            'class' => 'form-input'
        ]) }}
    >


    @error($name)

        <span class="validation-error">
            {{ $message }}
        </span>

    @enderror

</div>