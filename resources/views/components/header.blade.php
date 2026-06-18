@props([
    'judul',
    'subjudul'
])
<div class="mb-8 mt-12 bg-linear-to-r from-brand-500 to-brand-700 rounded-3xl p-8">
    <p class="text-white text-2xl font-bold mb-2">{{ $judul }}</p>
    <p class="text-gray-200">{{ $subjudul }}</p>
</div>