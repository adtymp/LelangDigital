@props([
'namaLabel' => '',
'type' => 'text',
'namaInput',
'value' => '',
'slang' => '',
])

<div>
    @if($namaLabel)
    <label class="text-gray-500">{{ $namaLabel }}</label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $namaInput }}"
        value="{{ $value }}"
        placeholder="{{ $slang }}"
        {{ $attributes->class([
            'w-full px-4 py-3 border border-gray-200 rounded-lg',
            'focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent'
        ]) }}>
</div>