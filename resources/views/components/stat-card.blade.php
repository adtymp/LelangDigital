@props([
'title',
'value',
'text' => 'gray-600',
'color' => 'gray',
'shade' => '600',
'bg' => 'white',
'brdr' => 'gray'
])

<div class="relative overflow-hidden bg-{{ $bg }} rounded-3xl p-6 border-2 border-{{ $brdr }}-200 hover:border-2 transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-2xl">

    @isset($icon)
    <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
        <div class="text-{{ $color }}-{{ $shade }}">
            {{ $icon }}
        </div>
    </div>
    @endisset

    <p class="text-{{ $text }} font-medium text-sm mb-2">
        {{ $title }}
    </p>

    <p class="text-3xl text-{{ $color }}-{{ $shade }} font-semibold">
        {{ $value }}
    </p>
    <div class="absolute bottom-0 left-0 right-0 h-0 bg-brand-200 transition-all duration-200 group-hover:h-2"></div>
</div>