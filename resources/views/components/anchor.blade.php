@props([
    'link' => '#',
    'full' => false,
])

<a
    href="{{ $link }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center',
        'rounded-lg px-4 py-2 text-white font-semibold transition-colors',
        'bg-linear-to-r from-brand-500 to-brand-700 hover:rounded-lg hover:from-brand-800 hover:to-brand-900',
        'disabled:bg-gray-300 disabled:cursor-not-allowed',
        'w-full' => $full,
    ]) }}
>
    {{ $slot }}
</a>