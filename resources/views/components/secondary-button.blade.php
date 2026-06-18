@props([
    'type' => 'button',
    'full' => false,
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center',
        'rounded-2xl px-4 py-2 border border-brand-500',
        'font-semibold text-brand-500 hover:text-white',
        'transition-colors shadow-sm',

        'bg-gradient-to-r',

        'from-white to-white',
        'hover:from-brand-500 hover:to-brand-700',

        'w-full' => $full,
    ]) }}
>
    {{ $slot }}
</button>