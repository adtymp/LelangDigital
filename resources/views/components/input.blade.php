@props([
'namaLabel' => '',
'type' => 'text',
'namaInput' => '',
'value' => '',
'slang' => '',
])

<div class="space-y-2">

    @if($namaLabel)
    <label for="{{ $namaInput }}"
        class="text-sm font-medium text-slate-700">
        {{ $namaLabel }}
    </label>
    @endif

    <input
        id="{{ $attributes->get('id', $namaInput) }}"
        type="{{ $type }}"
        name="{{ $namaInput }}"
        value="{{ old($namaInput, $value) }}"
        placeholder="{{ $slang }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-3 border border-gray-200 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-brand-500
                        transition-all
                        placeholder:text-slate-400
                        file:mr-3 file:py-1 file:px-2
                        file:rounded-md file:border-0
                        file:text-xs file:font-semibold
                        file:bg-brand-50 file:text-brand-700
                        hover:file:bg-brand-100'
        ]) }}>

</div>