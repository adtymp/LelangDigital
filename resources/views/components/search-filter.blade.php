@props([
'action' => '',
'searchName' => 'search',
'searchPlaceholder' => 'Cari data...',
'filters' => [],
])

<form method="GET" action="{{ $action }}"
    class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm mb-6">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- SEARCH --}}
        <div class="relative xl:col-span-2">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                viewBox="0 0 640 640"
                fill="currentColor">
                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272z" />
            </svg>

            <input
                type="text"
                name="{{ $searchName }}"
                value="{{ request($searchName) }}"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-transparent">
        </div>

        {{-- DYNAMIC FILTER --}}
        @foreach($filters as $filter)

        <div class="relative">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                viewBox="0 0 640 640"
                fill="currentColor">
                <path d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
            </svg>

            <select
                name="{{ $filter['name'] }}"
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-transparent appearance-none bg-white">

                <option value="">
                    {{ $filter['placeholder'] }}
                </option>

                @foreach($filter['options'] as $value => $label)

                <option
                    value="{{ $value }}"
                    @selected(request($filter['name'])==$value)>

                    {{ $label }}

                </option>

                @endforeach

            </select>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7" />

            </svg>

        </div>

        @endforeach

    </div>

    {{-- BUTTON --}}
    <div class="flex flex-wrap gap-3 mt-5">

        <x-primary-button type="submit">Terapkan Filter</x-primary-button>

        <a href="{{ url()->current() }}"
            class="px-5 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition">

            Reset

        </a>

    </div>

</form>