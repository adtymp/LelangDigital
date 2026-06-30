<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pranala GigPortal - PT. Pranala Digital Transmaritim</title>
  <link rel="icon" type="image/png" href="{{ asset('image/logo-pranala.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    html {
      scroll-behavior: smooth;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-15px) rotate(3deg);
      }
    }

    @keyframes float-delayed {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(15px) rotate(-3deg);
      }
    }

    .animate-float {
      animation: float 6s ease-in-out infinite;
    }

    .animate-float-delayed {
      animation: float-delayed 8s ease-in-out infinite;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased selection:bg-brand-500 selection:text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

  <!-- Floating Navigation Bar -->
  <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-4"
    :class="scrolled ? 'bg-white/85 backdrop-blur-lg shadow-md border-b border-gray-100/50 py-3' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
      <!-- Logo + Brand -->
      <a href="#" class="flex items-center gap-3 group">
        <div class="bg-white p-2 rounded-xl shadow-md border border-gray-100 group-hover:scale-105 transition-transform">
          <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo" class="w-8 h-8 object-contain">
        </div>
        <div class="leading-tight">
          <span class="text-lg font-bold bg-linear-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent block">Pranala GigPortal</span>
          <span class="text-xs text-gray-500 font-medium">PT. Pranala Digital Transmaritim</span>
        </div>
      </a>

      <!-- Menu Navigation -->
      <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600">
        <a href="#alur" class="hover:text-brand-500 transition-colors">Alur Sistem</a>
        <a href="#fitur" class="hover:text-brand-500 transition-colors">Keunggulan</a>
        <a href="#peran" class="hover:text-brand-500 transition-colors">Peran</a>
      </nav>

      <!-- Auth Action Button -->
      <div>
        @auth
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('dashboard.admin') }}"
          class="inline-flex items-center gap-2 bg-linear-to-r from-brand-500 to-brand-700 hover:from-brand-600 hover:to-brand-800 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30 transition-all duration-300 hover:-translate-y-0.5">
          <span class="text-sm">Dashboard Admin</span>
          <i class="fas fa-arrow-right text-xs"></i>
        </a>
        @else
        <a href="{{ route('dashboard.freelance') }}"
          class="inline-flex items-center gap-2 bg-linear-to-r from-brand-500 to-brand-700 hover:from-brand-600 hover:to-brand-800 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30 transition-all duration-300 hover:-translate-y-0.5">
          <span class="text-sm">Dashboard Freelancer</span>
          <i class="fas fa-arrow-right text-xs"></i>
        </a>
        @endif
        @else
        <x-anchor link="{{ route('login') }}">
          <span class="text-sm">Masuk / Daftar</span>
          <i class="fas fa-sign-in-alt text-xs ml-2"></i>
        </x-anchor>
        @endauth
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="relative min-h-screen pt-32 pb-20 flex items-center overflow-hidden bg-linear-to-br from-brand-50 via-white to-purple-50">
    <!-- Decorative Floating Orbs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-brand-200/40 blur-3xl animate-float"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-purple-200/40 blur-3xl animate-float-delayed"></div>

    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
      <!-- Hero Left: Text & CTA -->
      <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 border border-brand-100 text-brand-700 text-xs font-semibold uppercase tracking-wider">
          <span class="flex h-2 w-2 rounded-full bg-brand-500 animate-pulse"></span>
          Portal Kolaborasi & Distribusi Proyek Lembaran
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-gray-900 leading-[1.1]">
          Kelola Proyek Dokumen Lebih Cepat di <br>
          <span class="bg-linear-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent">Pranala GigPortal</span>
        </h1>

        <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
          Temukan kemudahan dalam pemrosesan data, digitalisasi dokumen, dan entri data lembar per lembar secara aman, cepat, dan transparan.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
          @auth
          <a href="{{ auth()->user()->hasRole('admin') ? route('dashboard.admin') : route('dashboard.freelance') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-linear-to-r from-brand-500 to-brand-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300 hover:-translate-y-0.5">
            <span>Buka Dashboard</span>
            <i class="fas fa-chevron-right text-xs"></i>
          </a>
          @else
          <a href="{{ route('login') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-linear-to-r from-brand-500 to-brand-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300 hover:-translate-y-0.5">
            <span>Mulai Bekerja</span>
            <i class="fas fa-chevron-right text-xs"></i>
          </a>
          @endauth
          <a href="#alur"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-bold px-8 py-4 rounded-xl shadow-md border border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
            <span>Pelajari Alur Kerja</span>
            <i class="fas fa-play text-xs text-brand-500"></i>
          </a>
        </div>
      </div>

      <!-- Hero Right: Interactive Mini Panel Mockup -->
      <div class="lg:col-span-5 relative">
        <div class="relative mx-auto max-w-95 sm:max-w-md bg-white/70 backdrop-blur-xl p-6 rounded-3xl shadow-2xl border border-white/60 animate-float">
          <!-- Window controls -->
          <div class="flex gap-1.5 mb-4">
            <span class="w-3 h-3 rounded-full bg-red-400"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
            <span class="w-3 h-3 rounded-full bg-green-400"></span>
          </div>

          <div class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
              <span class="font-bold text-gray-800 text-sm">Aktifitas Tugas Real-Time</span>
              <span class="text-xs text-brand-500 font-semibold px-2 py-0.5 rounded-full bg-brand-50">Aktif</span>
            </div>

            <!-- Fake Notification Items -->
            <div class="flex items-start gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-50">
              <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                <i class="fas fa-plus-circle"></i>
              </div>
              <div class="text-xs">
                <p class="font-bold text-gray-800">Proyek Baru Ditambahkan</p>
                <p class="text-gray-500">Tugas digitalisasi halaman dokumen baru siap diambil.</p>
                <p class="text-[10px] text-gray-400 mt-1">Baru saja</p>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-50">
              <div class="p-2 bg-brand-50 text-brand-500 rounded-lg">
                <i class="fas fa-bell"></i>
              </div>
              <div class="text-xs">
                <p class="font-bold text-gray-800">Notifikasi Tugas</p>
                <p class="text-gray-500">Notifikasi tugas diteruskan ke dasbor freelancer.</p>
                <p class="text-[10px] text-gray-400 mt-1">1 menit yang lalu</p>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-50">
              <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <i class="fas fa-check-double"></i>
              </div>
              <div class="text-xs">
                <p class="font-bold text-gray-800">Tugas Divalidasi</p>
                <p class="text-gray-500">Pengerjaan tugas telah selesai diverifikasi oleh Admin.</p>
                <p class="text-[10px] text-gray-400 mt-1">5 menit yang lalu</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Interactive System Workflow (Alur Sistem) -->
  <section id="alur" class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
        <h2 class="text-xs uppercase tracking-widest text-brand-600 font-bold">Langkah Kerja</h2>
        <h3 class="text-3xl sm:text-4xl font-black text-gray-900">Bagaimana Alur Kerja Sistem?</h3>
        <p class="text-gray-500">Pelajari alur lengkap pengerjaan proyek dari penambahan tugas hingga proses pembayaran upah secara transparan.</p>
      </div>

      <!-- Alpine-driven Interactive Slider -->
      <div x-data="{ 
            activeStep: 1, 
            autoplay: true,
            totalSteps: 6,
            steps: [
              {
                id: 1,
                title: 'Admin Menambah Proyek',
                role: 'Admin',
                routeInfo: 'Tambah Proyek',
                desc: 'Admin mengunggah proyek utama baru beserta file dokumen dan template pengerjaan.',
                longDesc: 'Melalui form ini, Admin membagi proyek besar menjadi bagian tugas dengan rincian total halaman, kualitas yang diinginkan, serta harga tetap per lembar halaman yang akan dibayarkan.',
                icon: 'fa-folder-plus'
              },
              {
                id: 2,
                title: 'Notifikasi Tugas Proyek',
                role: 'Freelancer',
                routeInfo: 'Notifikasi Real-Time',
                desc: 'Sistem menyebarkan pemberitahuan tugas proyek secara instan ke semua akun freelancer yang terverifikasi.',
                longDesc: 'Notifikasi ini dikirimkan agar freelancer dapat mengetahui secara langsung jika terdapat proyek baru yang aktif dan siap dikerjakan tanpa kehilangan waktu.',
                icon: 'fa-bell'
              },
              {
                id: 3,
                title: 'Memilih Proyek Sesuai Harga',
                role: 'Freelancer',
                routeInfo: 'Detail Proyek',
                desc: 'Freelancer memantau proyek aktif di dasbor mereka dan memilih tugas yang sesuai dengan kriteria yang mereka inginkan.',
                longDesc: 'Dasbor ini menampilkan detail proyek aktif, total halaman yang tersisa, tingkat kualitas tugas, serta harga per lembar halaman yang ditawarkan oleh Admin.',
                icon: 'fa-search-dollar'
              },
              {
                id: 4,
                title: 'Mengambil Halaman Tugas',
                role: 'Freelancer',
                routeInfo: 'Ambil Tugas Proyek',
                desc: 'Freelancer mengambil tugas spesifik dari dokumen proyek yang mereka pilih sesuai harga.',
                longDesc: 'Sistem secara otomatis akan menyimpan tugas yang diklaim freelancer tersebut dan membungkusnya ke dalam file ZIP bersama template Excel.',
                icon: 'fa-tasks'
              },
              {
                id: 5,
                title: 'Menyelesaikan & Upload Tugas',
                role: 'Freelancer',
                routeInfo: 'Upload Tugas Proyek',
                desc: 'Freelancer mengunduh file tugas, menyelesaikan pekerjaannya, dan mengunggah dokumen hasil pekerjaan kembali ke sistem sebelum batas waktu.',
                longDesc: 'Freelancer wajib mengunggah dokumen pengerjaan sesuai dengan rentang halaman yang telah diklaim. Sistem akan memvalidasi tipe dokumen sebelum masuk ke proses peninjauan.',
                icon: 'fa-file-upload'
              },
              {
                id: 6,
                title: 'Penilaian & Pembayaran Upah',
                role: 'Admin',
                routeInfo: 'Nilai Tugas dan Bayar',
                desc: 'Admin melakukan penilaian hasil kerja dan mentransfer pembayaran upah langsung ke rekening freelancer.',
                longDesc: 'Admin mengevaluasi kesesuaian tugas. Hasil nilai menentukan pengali pembayaran upah akhir. Setelah divalidasi, Admin mengunggah bukti bayar dan mengirim upah langsung ke rekening bank terdaftar milik freelancer.',
                icon: 'fa-hand-holding-usd'
              }
            ],
            init() {
              setInterval(() => {
                if (this.autoplay) {
                  this.activeStep = this.activeStep === this.totalSteps ? 1 : this.activeStep + 1;
                }
              }, 7000);
            }
          }"
        class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-stretch">

        <!-- Left: Interactive Timeline Selectors -->
        <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
          <div class="space-y-3">
            <template x-for="step in steps" :key="step.id">
              <button @click="activeStep = step.id; autoplay = false"
                class="w-full text-left p-4 rounded-2xl transition-all duration-300 flex items-center gap-4 border"
                :class="activeStep === step.id 
                              ? 'bg-linear-to-r from-brand-500 to-brand-700 text-white shadow-xl shadow-brand-500/20 border-transparent scale-102 -translate-x-1' 
                              : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-gray-800 border-gray-100'">

                <!-- Step Number Bubble -->
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm transition-colors duration-300"
                  :class="activeStep === step.id ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">
                  <span x-text="step.id"></span>
                </div>

                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold opacity-75 uppercase tracking-wider" x-text="step.role"></p>
                    <span class="text-[10px] opacity-80 font-mono" x-text="step.routeInfo"></span>
                  </div>
                  <p class="font-bold text-sm sm:text-base truncate" x-text="step.title"></p>
                </div>

                <div>
                  <i class="fas fa-chevron-right text-xs opacity-50" :class="activeStep === step.id ? 'translate-x-0.5 opacity-100' : ''"></i>
                </div>
              </button>
            </template>
          </div>

          <!-- Play / Pause Autoplay -->
          <div class="flex items-center gap-2 text-xs text-gray-500 px-3 pt-2">
            <button @click="autoplay = !autoplay" class="p-2 rounded-full hover:bg-gray-100 text-gray-700 transition">
              <i class="fas" :class="autoplay ? 'fa-pause' : 'fa-play'"></i>
            </button>
            <span x-text="autoplay ? 'Pergantian otomatis aktif (7 dtk)' : 'Pergantian otomatis dijeda'"></span>
          </div>
        </div>

        <!-- Right: Focused Step Details Card -->
        <div class="lg:col-span-7 flex">
          <div class="w-full bg-linear-to-br from-gray-50 to-gray-100 rounded-3xl p-8 border border-gray-200/50 flex flex-col justify-between relative overflow-hidden shadow-sm">
            <!-- Background Decorative Symbol -->
            <div class="absolute -right-10 -bottom-10 text-[200px] text-gray-200/30 font-black pointer-events-none select-none">
              <i class="fas" :class="steps[activeStep-1].icon"></i>
            </div>

            <!-- Content Area -->
            <div class="space-y-6 relative z-10">
              <!-- Badge Role & Step Number -->
              <div class="flex items-center justify-between">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white shadow-sm border border-gray-200 text-brand-600"
                  x-text="`Modul Sistem: ${steps[activeStep-1].routeInfo}`"></span>

                <span class="text-sm font-bold text-gray-400" x-text="`Langkah ${activeStep} dari 6`"></span>
              </div>

              <!-- Icon & Title -->
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-brand-500 text-white flex items-center justify-center text-2xl shadow-lg shadow-brand-500/20">
                  <i class="fas" :class="steps[activeStep-1].icon"></i>
                </div>
                <h4 class="text-2xl sm:text-3xl font-black text-gray-900" x-text="steps[activeStep-1].title"></h4>
              </div>

              <hr class="border-gray-200">

              <div class="space-y-4">
                <p class="text-base text-gray-700 font-medium leading-relaxed" x-text="steps[activeStep-1].desc"></p>
                <p class="text-sm text-gray-500 leading-relaxed" x-text="steps[activeStep-1].longDesc"></p>
              </div>
            </div>

            <!-- Visual Flow Connection Indicator -->
            <div class="pt-8 flex items-center justify-between relative z-10">
              <div class="flex gap-2">
                <template x-for="n in 6">
                  <span class="h-2 rounded-full transition-all duration-300"
                    :class="n === activeStep ? 'w-8 bg-brand-500' : 'w-2 bg-gray-300'"
                    @click="activeStep = n; autoplay = false"
                    class="cursor-pointer"></span>
                </template>
              </div>

              <div class="flex gap-2">
                <button @click="activeStep = activeStep === 1 ? 6 : activeStep - 1; autoplay = false"
                  class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
                  <i class="fas fa-arrow-left text-xs"></i>
                </button>
                <button @click="activeStep = activeStep === 6 ? 1 : activeStep + 1; autoplay = false"
                  class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
                  <i class="fas fa-arrow-right text-xs"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Key Features Section (Fitur Unggulan) -->
  <section id="fitur" class="py-24 bg-gray-50 border-y border-gray-200/50">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
        <h2 class="text-xs uppercase tracking-widest text-brand-600 font-bold">Fitur Utama</h2>
        <h3 class="text-3xl sm:text-4xl font-black text-gray-900">Mengapa Memilih Pranala GigPortal?</h3>
        <p class="text-gray-500">Platform terintegrasi yang menghadirkan efisiensi dalam distribusi pekerjaan data entry dan digitalisasi dokumen.</p>
      </div>

      <!-- Feature Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl mb-6">
            <i class="fas fa-laptop-house"></i>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-3">Fleksibilitas & Kemudahan</h4>
          <p class="text-gray-600 text-sm leading-relaxed">Akses portal dan kerjakan tugas yang Anda ambil dari mana saja dan kapan saja sesuai dengan kenyamanan serta jadwal Anda.</p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl mb-6">
            <i class="fas fa-chart-line"></i>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-3">Sistem Leveling Adil</h4>
          <p class="text-gray-600 text-sm leading-relaxed">Kumpulkan poin kontribusi dari setiap halaman yang berhasil divalidasi untuk menaikkan level akun guna meningkatkan batas klaim upah.</p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl mb-6">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-3">Verifikasi & Transparansi</h4>
          <p class="text-gray-600 text-sm leading-relaxed">Validasi file output yang ketat dan transparansi penilaian dari Admin menjamin keadilan penilaian kualitas dan kepastian upah pekerjaan.</p>
        </div>
      </div>

    </div>
  </section>

  <!-- User Roles Comparison Section (Peran Pengguna) -->
  <section id="peran" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
        <h2 class="text-xs uppercase tracking-widest text-brand-600 font-bold">Peran Aktor</h2>
        <h3 class="text-3xl sm:text-4xl font-black text-gray-900">Dua Sisi Kolaborasi Efisien</h3>
        <p class="text-gray-500">Masing-masing aktor memiliki antarmuka khusus yang dirancang untuk mempercepat pencapaian tujuan proyek.</p>
      </div>

      <!-- Roles Comparison Cards -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Admin Role Card -->
        <div class="bg-linear-to-br from-gray-50 to-gray-100 p-8 sm:p-10 rounded-3xl border border-gray-200/50 space-y-6 flex flex-col justify-between">
          <div class="space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-50 text-brand-700 text-xs font-semibold rounded-full">
              Pemberi Tugas
            </div>
            <h4 class="text-2xl font-bold text-gray-900">Peran Admin (Management)</h4>
            <p class="text-gray-600 text-sm leading-relaxed">Bertanggung jawab dalam membagi proyek dokumen, memonitor ketersediaan tugas, melakukan penilaian berkas freelancer, dan mentransfer upah kerja.</p>
            <ul class="space-y-3 pt-4">
              <li class="flex items-center gap-3 text-sm text-gray-700">
                <i class="fas fa-check-circle text-brand-500"></i>
                <span>Menambah & membagi proyek (`Tambah Proyek`)</span>
              </li>
              <li class="flex items-center gap-3 text-sm text-gray-700">
                <i class="fas fa-check-circle text-brand-500"></i>
                <span>Menilai kualitas hasil tugas (`Penilaian`)</span>
              </li>
              <li class="flex items-center gap-3 text-sm text-gray-700">
                <i class="fas fa-check-circle text-brand-500"></i>
                <span>Mengelola dan membayar upah (`Pembayaran`)</span>
              </li>
            </ul>
          </div>

          <div class="pt-6">
            @auth
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-2 font-bold text-brand-600 hover:text-brand-700 text-sm">
              <span>Masuk Dasbor Admin</span>
              <i class="fas fa-arrow-right"></i>
            </a>
            @endif
            @endauth
          </div>
        </div>

        <!-- Freelancer Role Card -->
        <div class="bg-linear-to-br from-brand-700 to-brand-900 p-8 sm:p-10 rounded-3xl text-white space-y-6 flex flex-col justify-between shadow-xl shadow-brand-950/20">
          <div class="space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 text-brand-200 text-xs font-semibold rounded-full">
              Pelaksana Tugas
            </div>
            <h4 class="text-2xl font-bold">Peran Freelancer (Kreator)</h4>
            <p class="text-brand-200/80 text-sm leading-relaxed">Fokus memilah proyek aktif di dasbor berdasarkan harga per lembar halaman yang diinginkan, mengklaim halaman tugas, mengunggah pengerjaan, dan mencairkan upah.</p>
            <ul class="space-y-3 pt-4">
              <li class="flex items-center gap-3 text-sm text-brand-200">
                <i class="fas fa-check-circle text-brand-400"></i>
                <span>Memilih rentang harga tugas (`Pilih Tugas Proyek`)</span>
              </li>
              <li class="flex items-center gap-3 text-sm text-brand-200">
                <i class="fas fa-check-circle text-brand-400"></i>
                <span>Mengklaim halaman tugas (`Detail Proyek`)</span>
              </li>
              <li class="flex items-center gap-3 text-sm text-brand-200">
                <i class="fas fa-check-circle text-brand-400"></i>
                <span>Mengunggah hasil pengerjaan (`Upload Tugas`)</span>
              </li>
            </ul>
          </div>

          <div class="pt-6">
            @auth
            @if(auth()->user()->hasRole('freelancer'))
            <a href="{{ route('dashboard.freelance') }}" class="inline-flex items-center gap-2 font-bold text-brand-300 hover:text-brand-200 text-sm">
              <span>Masuk Dasbor Freelancer</span>
              <i class="fas fa-arrow-right"></i>
            </a>
            @endif
            @endauth
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- CTA Promotion Banner -->
  <section class="py-20 bg-linear-to-br from-brand-600 via-brand-700 to-brand-800 text-white relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-150 h-150 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-6 text-center space-y-8 relative z-10">
      <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black">Siap Memulai Kolaborasi di Pranala GigPortal?</h3>
      <p class="text-brand-100 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed">
        Gabung sekarang juga bersama ekosistem portal tugas PT. Pranala Digital Transmaritim. Rasakan kemudahan manajemen pemrosesan dokumen yang optimal dan transparan.
      </p>

      <div class="flex flex-wrap items-center justify-center gap-4">
        @auth
        <a href="{{ auth()->user()->hasRole('admin') ? route('dashboard.admin') : route('dashboard.freelance') }}"
          class="bg-white text-brand-700 hover:bg-brand-50 font-bold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-0.5">
          Buka Dasbor
        </a>
        @else
        <a href="{{ route('login') }}"
          class="bg-white text-brand-700 hover:bg-brand-50 font-bold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-0.5">
          Daftar Sekarang
        </a>
        <a href="#alur"
          class="bg-transparent border border-white/40 hover:bg-white/10 hover:border-white text-white font-semibold px-8 py-4 rounded-xl transition-all duration-300">
          Pelajari Selengkapnya
        </a>
        @endauth
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">

      <!-- Brand Left -->
      <div class="flex items-center gap-3">
        <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo" class="w-8 h-8 object-contain bg-white p-1 rounded-lg">
        <div class="text-left leading-tight">
          <p class="text-white font-bold text-sm">PT. Pranala Digital Transmaritim</p>
          <p class="text-xs text-gray-500">Pranala GigPortal</p>
        </div>
      </div>

      <!-- Links Middle -->
      <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
        <a href="#alur" class="hover:text-white transition">Alur Kerja</a>
        <a href="#fitur" class="hover:text-white transition">Fitur Utama</a>
        <a href="#peran" class="hover:text-white transition">Peran Pengguna</a>
      </div>

      <!-- Right: Copyright -->
      <div class="text-xs text-gray-500">
        &copy; {{ date('Y') }} PT. Pranala Digital Transmaritim. All rights reserved.
      </div>
    </div>
  </footer>

</body>

</html>