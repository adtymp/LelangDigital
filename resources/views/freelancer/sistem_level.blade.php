@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Sistem Level'"
    :subjudul="'Pahami sistem level dan dapatkan keuntungan lebih'" />

<div class="space-y-6">
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-white">

        <div class="absolute top-0 right-0 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-yellow-300" viewBox="0 0 640 640" fill="currentColor">
                <path d="M320.3 192L235.7 51.1C229.2 40.3 215.6 36.4 204.4 42L117.8 85.3C105.9 91.2 101.1 105.6 107 117.5L176.6 256.6C146.5 290.5 128.3 335.1 128.3 384C128.3 490 214.3 576 320.3 576C426.3 576 512.3 490 512.3 384C512.3 335.1 494 290.5 464 256.6L533.6 117.5C539.5 105.6 534.7 91.2 522.9 85.3L436.2 41.9C425 36.3 411.3 40.3 404.9 51L320.3 192zM351.1 334.5C352.5 337.3 355.1 339.2 358.1 339.6L408.2 346.9C415.9 348 418.9 357.4 413.4 362.9L377.1 398.3C374.9 400.5 373.9 403.5 374.4 406.6L383 456.5C384.3 464.1 376.3 470 369.4 466.4L324.6 442.8C321.9 441.4 318.6 441.4 315.9 442.8L271.1 466.4C264.2 470 256.2 464.2 257.5 456.5L266.1 406.6C266.6 403.6 265.6 400.5 263.4 398.3L227.1 362.9C221.5 357.5 224.6 348.1 232.3 346.9L282.4 339.6C285.4 339.2 288.1 337.2 289.4 334.5L311.8 289.1C315.2 282.1 325.1 282.1 328.6 289.1L351 334.5z" />
            </svg>
        </div>

        <div class="relative z-10">

            <div class="flex items-center gap-4 mb-6">

                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-15 w-15 text-yellow-300" viewBox="0 0 640 640" fill="currentColor">
                        <path d="M320.3 192L235.7 51.1C229.2 40.3 215.6 36.4 204.4 42L117.8 85.3C105.9 91.2 101.1 105.6 107 117.5L176.6 256.6C146.5 290.5 128.3 335.1 128.3 384C128.3 490 214.3 576 320.3 576C426.3 576 512.3 490 512.3 384C512.3 335.1 494 290.5 464 256.6L533.6 117.5C539.5 105.6 534.7 91.2 522.9 85.3L436.2 41.9C425 36.3 411.3 40.3 404.9 51L320.3 192zM351.1 334.5C352.5 337.3 355.1 339.2 358.1 339.6L408.2 346.9C415.9 348 418.9 357.4 413.4 362.9L377.1 398.3C374.9 400.5 373.9 403.5 374.4 406.6L383 456.5C384.3 464.1 376.3 470 369.4 466.4L324.6 442.8C321.9 441.4 318.6 441.4 315.9 442.8L271.1 466.4C264.2 470 256.2 464.2 257.5 456.5L266.1 406.6C266.6 403.6 265.6 400.5 263.4 398.3L227.1 362.9C221.5 357.5 224.6 348.1 232.3 346.9L282.4 339.6C285.4 339.2 288.1 337.2 289.4 334.5L311.8 289.1C315.2 282.1 325.1 282.1 328.6 289.1L351 334.5z" />
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-white/80">
                        Level Saat Ini
                    </p>

                    <h2 class="text-3xl font-bold">
                        Level {{ $user->level?->nama_level ?? '-'}}
                    </h2>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-3">

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4">
                    <p class="text-xs text-white/70">
                        Poin
                    </p>

                    <p class="text-lg font-bold">
                        {{ number_format($user->poin_level) }}
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4">
                    <p class="text-xs text-white/70">
                        Level
                    </p>

                    <p class="text-lg font-bold">
                        {{ $user->level?->nama_level ?? '-'}}
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4">
                    <p class="text-xs text-white/70">
                        Notifikasi
                    </p>

                    <p class="text-lg font-bold">
                        {{ $user->level?->delay_notifikasi ?? '-'}}
                    </p>
                </div>

            </div>

        </div>

    </div>

    @php
    $currentIndex = $levels->search(fn($item) => $item->id == $user->level_id);
    $nextLevel = $levels[$currentIndex + 1] ?? null;
    @endphp

    @if($nextLevel)

    @php
    $progress = min(
    100,
    (($user->poin_level - $user->level->min_poin)
    /
    ($nextLevel->min_poin - $user->level->min_poin))
    * 100
    );
    @endphp

    <div class="bg-white rounded-3xl border border-gray-200 p-5 mb-6">

        <div class="flex justify-between mb-2">

            <span class="font-medium">
                Progress ke Level {{ $nextLevel->nama_level }}
            </span>

            <span>
                {{ round($progress) }}%
            </span>

        </div>

        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">

            <div
                class="h-full bg-linear-to-r from-brand-500 to-brand-700 rounded-full"
                style="width: {{ $progress }}%">
            </div>

        </div>

        <p class="mt-3 text-sm text-gray-500">

            Kurang
            <span class="font-semibold text-brand-600">
                {{ number_format($nextLevel->min_poin - $user->poin_level) }}
            </span>
            poin lagi untuk naik level.

        </p>

    </div>

    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <div class="flex items-center gap-2 mb-6 text-brand-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                <path fill="currentColor" d="M320.1 32C329.1 32 337.4 37.1 341.5 45.1L415 189.3L574.9 214.7C583.8 216.1 591.2 222.4 594 231C596.8 239.6 594.5 249 588.2 255.4L473.7 369.9L499 529.8C500.4 538.7 496.7 547.7 489.4 553C482.1 558.3 472.4 559.1 464.4 555L320.1 481.6L175.8 555C167.8 559.1 158.1 558.3 150.8 553C143.5 547.7 139.8 538.8 141.2 529.8L166.4 369.9L52 255.4C45.6 249 43.4 239.6 46.2 231C49 222.4 56.3 216.1 65.3 214.7L225.2 189.3L298.8 45.1C302.9 37.1 311.2 32 320.2 32zM320.1 108.8L262.3 222C258.8 228.8 252.3 233.6 244.7 234.8L119.2 254.8L209 344.7C214.4 350.1 216.9 357.8 215.7 365.4L195.9 490.9L309.2 433.3C316 429.8 324.1 429.8 331 433.3L444.3 490.9L424.5 365.4C423.3 357.8 425.8 350.1 431.2 344.7L521 254.8L395.5 234.8C387.9 233.6 381.4 228.8 377.9 222L320.1 108.8z" />
            </svg>
            <h2>Detail Level</h2>
        </div>

        <div class="space-y-5">

            @forelse($levels as $level)

            @php
            $isCurrent = $user->level_id == $level->id;
            $isUnlocked = $user->level_id > $level->id;
            @endphp

            <div class="relative">

                <div class="flex gap-4">

                    <div class="flex flex-col items-center">

                        <div
                            class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold
                                {{ $isCurrent ? 'bg-brand-600' : '' }}
                                {{ $isUnlocked ? 'bg-green-600' : '' }}
                                {{ !$isUnlocked && !$isCurrent ? 'bg-gray-300' : '' }}
                            ">

                            @if($isUnlocked)
                            ✓
                            @elseif($isCurrent)
                            ★
                            @else
                            🔒
                            @endif

                        </div>

                        @if(!$loop->last)
                        <div class="w-1 flex-1 bg-gray-200 min-h-12"></div>
                        @endif

                    </div>

                    <div
                        class="flex-1 rounded-3xl p-5 border
                            {{ $isCurrent ? 'border-brand-500 bg-brand-50' : '' }}
                            {{ $isUnlocked ? 'border-green-200 bg-green-50' : '' }}
                            {{ !$isUnlocked && !$isCurrent ? 'border-gray-200 bg-white' : '' }}
                        ">

                        <div class="flex justify-between items-start mb-3">

                            <div>
                                <h3 class="font-bold text-lg">
                                    Level {{ $level->nama_level }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    {{ $level->benefit }}
                                </p>
                            </div>

                            @if($isCurrent)
                            <span class="px-3 py-1 rounded-full bg-brand-600 text-white text-xs">
                                Saat Ini
                            </span>
                            @elseif($isUnlocked)
                            <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs">
                                Terbuka
                            </span>
                            @endif

                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Minimal Poin
                                </p>

                                <p class="font-semibold">
                                    {{ number_format($level->min_poin) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Notifikasi
                                </p>

                                @if($level->delay_notifikasi === 0)
                                <p class="font-semibold">
                                    Akses Proyek Instan! Notifikasi proyek baru secara real-time tanpa penundaan waktu.
                                </p>
                                @else
                                <p class="font-semibold">
                                    Notifikasi proyek tertunda {{ $level->delay_notifikasi }} menit.
                                </p>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            @empty
            <x-list-empty title="Tidak Ada Threshold Level" subtitle="Tidak ada level yang bisa ditampilkan">
                <x-slot:icon>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-list-empty>
            @endforelse

        </div>
    </div>
</div>
@endsection