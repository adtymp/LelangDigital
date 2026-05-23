@props([
    'type' => 'button',
    'full' => false,
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center',
        'rounded-xl px-5 py-3',
        'font-semibold text-white',
        'transition-colors shadow-sm',

        'bg-red-600',

        'hover:bg-red-700',

        'disabled:cursor-not-allowed',

        'w-full' => $full,
    ]) }}
>
    {{ $slot }}
</button>