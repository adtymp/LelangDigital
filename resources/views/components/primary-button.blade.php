@props([
    'type' => 'button',
    'full' => false,
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center',
        'rounded-2xl px-5 py-3',
        'font-semibold text-white',
        'transition-colors shadow-sm',

        'bg-gradient-to-r',

        'from-brand-500 to-brand-700',
        'hover:from-brand-700 hover:to-brand-900',

        'disabled:from-brand-300 disabled:to-brand-300',
        'disabled:cursor-not-allowed',

        'w-full' => $full,
    ]) }}
>
    {{ $slot }}
</button>