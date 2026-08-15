@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'multiple' => false,
])

@php
    $field = str_replace('[]', '', $name);

    $selectedValue = old($field, $value);

    if ($multiple) {
        $selectedValue = (array) $selectedValue;
    }
@endphp


<div class="form-group">

    @if ($label)

        <label for="{{ $field }}" class="form-label">
            {{ $label }}
        </label>

    @endif


    <select
        id="{{ $field }}"
        name="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->merge(['class' => 'form-input']) }}
    >

        @if ($placeholder && !$multiple)

            <option value="">
                {{ $placeholder }}
            </option>

        @endif


        @foreach ($options as $optionValue => $optionLabel)

            <option
                value="{{ $optionValue }}"

                @if ($multiple)
                    @selected(in_array($optionValue, $selectedValue))
                @else
                    @selected($selectedValue == $optionValue)
                @endif
            >
                {{ $optionLabel }}
            </option>

        @endforeach

    </select>


    @error($field)

        <span class="validation-error">
            {{ $message }}
        </span>

    @enderror


    @error($field . '.*')

        <span class="validation-error">
            {{ $message }}
        </span>

    @enderror

</div>