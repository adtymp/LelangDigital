@props([
'show' => false,
'title' => 'Modal Title',
'titleAlpine' => null,
'subtitle' => null,
'maxWidth' => 'max-w-lg',
])
<div x-show="{{ $show }}"
    x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;">
    <!-- BACKDROP -->
    <div class="absolute inset-0 bg-black/10 backdrop-blur-sm" @click="{{ $show }} = false">

    </div>
    <!-- CONTENT -->
    <div class="relative bg-white w-full {{ $maxWidth }} rounded-3xl overflow-hidden">
        <!-- HEADER -->
        <div class="bg-linear-to-r from-brand-500 to-brand-700 px-6 py-5 text-white flex items-start justify-between gap-4">
            @if (isset($header))
            {{ $header }}
            @else
            <div>
                <h2 class="text-xl font-bold">
                    @if($titleAlpine)
                    <span x-text="{{ $titleAlpine }}"></span>
                    @else
                    {{ $title }}
                    @endif
                </h2>

                @if ($subtitle)
                <p class="text-sm text-white/80 mt-1">
                    {{ $subtitle }}
                </p>
                @endif
            </div>
            @endif
            <!-- CLOSE -->
            <button @click="{{ $show }} = false" class="h-10 w-10 shrink-0 rounded-full hover:bg-white/20 flex items-center justify-center transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- BODY -->
        <div class="p-6 overflow-y-auto max-h-[85vh]"> {{ $slot }} </div>
    </div>
</div>