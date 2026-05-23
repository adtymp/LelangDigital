<div class="fixed top-4 right-4 z-50 space-y-4">
    {{-- Success Toast --}}
    @if (session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition
        class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded shadow-lg relative w-80"
        role="alert">
        <strong class="font-bold">Sukses! </strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <button
            @click="show = false"
            class="absolute top-1 right-2 text-green-700">
            <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
            </svg>
        </button>
    </div>
    @endif

    {{-- Error Toast --}}
    @if ($errors->any())
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 6000)"
        x-show="show"
        x-transition
        class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded shadow-lg relative w-80"
        role="alert">
        <strong class="font-bold">Terjadi kesalahan:</strong>
        <ul class="list-disc pl-5 text-sm mt-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button
            @click="show = false"
            class="absolute top-1 right-2 text-red-700">
            <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
            </svg>
        </button>
    </div>
    @endif
</div>