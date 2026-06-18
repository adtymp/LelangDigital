@props([
'title' => null,
'subtitle' => null
])

<div class="col-span-full bg-white border border-gray-200 rounded-2xl p-14 text-center">

    @isset($icon)
    <div class="w-20 h-20 rounded-full bg-gray-100 mx-auto mb-5 flex items-center justify-center">
        {{ $icon }}
    </div>
    @endisset

    <h2 class="text-lg font-semibold text-gray-800 mb-2">
        {{ $title }}
    </h2>

    <p class="text-gray-500">
        {{ $subtitle }}
    </p>

</div>