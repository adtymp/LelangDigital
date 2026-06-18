<?php

return [

    'admin' => [

        [
            'label' => 'Dashboard',
            'route' => 'dashboard.admin',
            'icon' => 'dashboard',
            'badge' => null
        ],

        [
            'label' => 'Tambah Proyek',
            'route' => 'proyek.halaman',
            'icon' => 'tambahproyek',
            'badge' => null
        ],

        [
            'label' => 'Riwayat Proyek',
            'route' => 'riwayat.proyek',
            'icon' => 'riwayat',
            'badge' => null
        ],

        [
            'label' => 'Penilaian',
            'route' => 'penilaian.view',
            'icon' => 'penilaian',
            'badge' => 'penilaian'
        ],

        [
            'label' => 'Pembayaran',
            'route' => 'pembayaran.lihat',
            'icon' => 'pembayaran',
            'badge' => 'pembayaran'
        ],

        [
            'label' => 'Freelancer',
            'route' => 'freelancer.halaman',
            'icon' => 'freelancer',
            'badge' => 'freelancer'
        ],

        [
            'label' => 'Pengaturan',
            'icon' => 'pengaturan',
            'badge' => null,
            'children' => [
                [
                    'label' => 'Poin',
                    'route' => 'poin.view',
                ],
                [
                    'label' => 'Level',
                    'route' => 'level.lihat',
                ],
                [
                    'label' => 'Simulasi',
                    'route' => 'simulasi.lihat',
                ],
            ]
        ],

        [
            'label' => 'Chat',
            'route' => 'chat.index',
            'icon' => 'chat',
            'badge' => null
       
        ],

    ],

    'freelancer' => [

        [
            'label' => 'Dashboard',
            'route' => 'dashboard.freelance',
            'icon' => 'dashboard',
            'badge' => 'proyekAktif'
        ],

        [
            'label' => 'Upload Tugas',
            'route' => 'freelancer.uploadTugas',
            'icon' => 'upload',
            'badge' => 'upload'
        ],

        [
            'label' => 'Riwayat',
            'route' => 'riwayat.lihat',
            'icon' => 'riwayat',
            'badge' => null
        ],

        [
            'label' => 'Profil Saya',
            'route' => 'profil.freelance',
            'icon' => 'profil',
            'badge' => null
        ],

        [
            'label' => 'Sistem Level',
            'route' => 'level.sistem',
            'icon' => 'level',
            'badge' => null
        ],

        [
            'label' => 'Chat',
            'route' => 'chat.index',
            'icon' => 'chat',
            'badge' => 'chat'
        ],

    ],

];