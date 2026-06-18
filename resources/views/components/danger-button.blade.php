@props([
    'type' => 'button',
    'full' => false,
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center',
        'rounded-2xl px-4 py-2 border border-red-600',
        'font-semibold text-white',
        'transition-colors shadow-sm',

        'bg-red-600',

        'hover:bg-red-800',

        'disabled:cursor-not-allowed',

        'w-full' => $full,
    ]) }}
>
    {{ $slot }}
</button>