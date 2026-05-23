@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Sistem Level'"
    :subjudul="'Pahami sistem level dan dapatkan keuntungan lebih'" />

<div class="bg-linear-to-r from-brand-500 via-brand-700 to-brand-500 rounded-xl p-8 mb-8 text-white">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-brand-500" viewBox="0 0 640 640" fill="currentColor">
                <path d="M320.3 192L235.7 51.1C229.2 40.3 215.6 36.4 204.4 42L117.8 85.3C105.9 91.2 101.1 105.6 107 117.5L176.6 256.6C146.5 290.5 128.3 335.1 128.3 384C128.3 490 214.3 576 320.3 576C426.3 576 512.3 490 512.3 384C512.3 335.1 494 290.5 464 256.6L533.6 117.5C539.5 105.6 534.7 91.2 522.9 85.3L436.2 41.9C425 36.3 411.3 40.3 404.9 51L320.3 192zM351.1 334.5C352.5 337.3 355.1 339.2 358.1 339.6L408.2 346.9C415.9 348 418.9 357.4 413.4 362.9L377.1 398.3C374.9 400.5 373.9 403.5 374.4 406.6L383 456.5C384.3 464.1 376.3 470 369.4 466.4L324.6 442.8C321.9 441.4 318.6 441.4 315.9 442.8L271.1 466.4C264.2 470 256.2 464.2 257.5 456.5L266.1 406.6C266.6 403.6 265.6 400.5 263.4 398.3L227.1 362.9C221.5 357.5 224.6 348.1 232.3 346.9L282.4 339.6C285.4 339.2 288.1 337.2 289.4 334.5L311.8 289.1C315.2 282.1 325.1 282.1 328.6 289.1L351 334.5z" />
            </svg>
        </div>
        <div>
            <h2 class="text-white mb-1">Level Anda Saat Ini</h2>
            <p class="text-blue-100">Terus tingkatkan untuk mendapat benefit lebih!</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="text-blue-100 text-sm mb-1">Current Level</p>
            <p class="text-4xl">Level {{ $user->level->nama_level }}</p>
        </div>
        <div>
            <p class="text-blue-100 text-sm mb-1">Total Poin</p>
            <p class="text-4xl">{{ $user->poin_level }}</p>
        </div>
        <div>
            <p class="text-blue-100 text-sm mb-1">Waktu Notifikasi</p>
            <p class="text-4xl">{{ $user->level->delay_notifikasi }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
    <div class="flex items-center gap-2 mb-6 text-brand-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
            <path fill="currentColor" d="M320.1 32C329.1 32 337.4 37.1 341.5 45.1L415 189.3L574.9 214.7C583.8 216.1 591.2 222.4 594 231C596.8 239.6 594.5 249 588.2 255.4L473.7 369.9L499 529.8C500.4 538.7 496.7 547.7 489.4 553C482.1 558.3 472.4 559.1 464.4 555L320.1 481.6L175.8 555C167.8 559.1 158.1 558.3 150.8 553C143.5 547.7 139.8 538.8 141.2 529.8L166.4 369.9L52 255.4C45.6 249 43.4 239.6 46.2 231C49 222.4 56.3 216.1 65.3 214.7L225.2 189.3L298.8 45.1C302.9 37.1 311.2 32 320.2 32zM320.1 108.8L262.3 222C258.8 228.8 252.3 233.6 244.7 234.8L119.2 254.8L209 344.7C214.4 350.1 216.9 357.8 215.7 365.4L195.9 490.9L309.2 433.3C316 429.8 324.1 429.8 331 433.3L444.3 490.9L424.5 365.4C423.3 357.8 425.8 350.1 431.2 344.7L521 254.8L395.5 234.8C387.9 233.6 381.4 228.8 377.9 222L320.1 108.8z" />
        </svg>
        <h2>Detail Level</h2>
    </div>

    <div class="space-y-4">
        @forelse ($levels as $level)

        @php
        $isCurrentLevel = $user->level_id == $level->id;
        $isUnlocked = $user->level_id > $level->id;
        @endphp

        <div
            @class([ 'p-6 rounded-xl border-2 transition-all' , 'border-brand-600 bg-brand-50'=> $isCurrentLevel,
            'border-green-200 bg-green-50' => $isUnlocked && !$isCurrentLevel,
            'border-gray-200 bg-gray-50' => !$isUnlocked && !$isCurrentLevel,
            ])
            >

            <div class="flex items-center justify-between mb-4">

                <div class="flex items-center gap-4">
                    <div>
                        <p class="px-4 py-2 items-center rounded-full bg-brand-600 text-xl text-white">{{ $level->nama_level }}</p>
                    </div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl">
                        <p class="text-gray-900 mb-1">
                            Level {{ $level->nama_level }}
                        </p>

                        <p class="text-gray-500 text-sm">
                            {{ $level->benefit }}
                        </p>
                    </div>
                </div>

                {{-- Current Level --}}
                @if ($isCurrentLevel)
                <span class="px-4 py-2 bg-brand-600 text-white rounded-lg">
                    Level Saat Ini
                </span>

                {{-- Unlocked --}}
                @elseif ($isUnlocked)
                <span class="px-4 py-2 bg-green-600 text-white rounded-lg">
                    Unlocked
                </span>
                @endif

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-gray-500 text-sm">Minimal Poin</p>
                        <p class="text-gray-900">{{ $level->min_poin }} poin</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-gray-500 text-sm">Waktu Notifikasi</p>
                        <p class="text-gray-900">{{ $level->delay_notifikasi }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-gray-500 text-sm">Potensi Pendapatan</p>
                        <p class="text-green-600">
                            +Rp {{ number_format($level->nama_level * 5000000, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
        @empty
        <div class="bg-white p-8 text-center">
            <FileText class="w-16 h-16 text-gray-500 mx-auto mb-4" />
            <p class="text-gray-500">Detail Level Tidak Ada</p>
        </div>
        @endforelse
    </div>
</div>
@endsection