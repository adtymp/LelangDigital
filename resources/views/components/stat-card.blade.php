@props([
'title',
'value',
'color' => 'gray',
'bg' => 'white',
'brdr' => 'gray'
])

<div class="bg-{{ $bg }} rounded-xl p-6 border border-{{ $brdr }}-200 hover:border-b-8 hover:border-brand-200 hover:shadow-2xl transition">

@isset($icon)
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
            <div class="text-{{ $color }}-600">
                {{ $icon }}
            </div>
        </div>
    </div>
@endisset

<p class="text-gray-500 text-sm mb-2">
    {{ $title }}
</p>

<p class="text-3xl text-{{ $color }}-600">
    {{ $value }}
</p>

</div>
