@props([
    'variant' => 'default',
    'type' => 'button',
])

@php

    $classes = match ($variant) {

        'view' =>
            'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300',

        'edit' =>
            'border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100',

        'delete' =>
            'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100',

        'primary' =>
            'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700',

        def:bg-slate-50',

    };

@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' =>
            'inline-flex h-9 min-w-[70px] items-center justify-center rounded-lg border px-3 text-xs font-semibold leading-none transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 ' .
            $classes
    ]) }}
>
    {{ $slot }}
</button>