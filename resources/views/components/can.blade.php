@props([
    'permission' => null,
    'any' => [],
])

@php
    $user = auth()->user();

    $allowed = false;

    if ($user) {

        if ($permission) {
            $allowed = $user->hasPermission($permission);
        }

        if (!$allowed && count($any)) {
            $allowed = collect($any)
                ->contains(fn ($item) => $user->hasPermission($item));
        }
    }
@endphp


@if ($allowed)
    {{ $slot }}
@endif