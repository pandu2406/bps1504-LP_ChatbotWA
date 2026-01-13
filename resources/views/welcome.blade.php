@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/new20220909_062617.jpg') }}" alt="BPS Office" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 to-gray-900/40 backdrop-blur-[2px]"></div>
        </div>

        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left h-full flex flex-col justify-center">

            <div class="sm:max-w-2xl transform transition-all duration-1000 translate-y-0 opacity-100">
                <span
                    class="inline-block px-4 py-1 mb-6 text-xs font-bold tracking-widest text-brand-300 uppercase bg-white/10 rounded-full backdrop-blur-sm border border-white/20">
                    Portal Resmi
                </span>
                <h1
                    class="text-4xl sm:text-6xl md:text-7xl font-heading font-extrabold text-white leading-tight mb-6 drop-shadow-lg">
                    Data Mencerdaskan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-white">Bangsa</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-300 mb-10 leading-relaxed font-light max-w-xl drop-shadow-md">
                    Badan Pusat Statistik Kabupaten Batang Hari. Menyediakan data berkualitas untuk Indonesia Maju.
                    Profesional, Integritas, Amanah.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#apps"
                        class="px-8 py-4 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold text-lg transition-all shadow-lg hover:shadow-brand-500/50 active:scale-95 flex items-center justify-center gap-2 group">
                        <span>Jelajahi Aplikasi</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="#about"
                        class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/30 rounded-xl font-bold text-lg transition-all backdrop-blur-md active:scale-95 flex items-center justify-center">
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce cursor-pointer"
            onclick="document.getElementById('about').scrollIntoView({behavior: 'smooth'})">
            <svg class="w-8 h-8 text-white/50 hover:text-white transition-colors" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Tentang Kami</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-2">Mengenal BPS Batang Hari</h2>
                <div class="w-24 h-1 bg-brand-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12" x-data="{ activeTab: 'profil' }">
                <!-- Sidebar / Tabs -->
                <div class="lg:w-1/4">
                    <div class="bg-gray-50 rounded-2xl p-2 sticky top-24 shadow-sm border border-gray-100">
                        <nav class="space-y-1">
                            <button @click="activeTab = 'profil'"
                                :class="{ 'bg-brand-600 text-white shadow-lg shadow-brand-500/30': activeTab === 'profil', 'text-gray-600 hover:bg-gray-100': activeTab !== 'profil' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 active:scale-95 group">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/20">
                                    <i class="fas fa-building text-sm"></i>
                                </span>
                                Profil BPS
                            </button>
                            <button @click="activeTab = 'visi'"
                                :class="{ 'bg-brand-600 text-white shadow-lg shadow-brand-500/30': activeTab === 'visi', 'text-gray-600 hover:bg-gray-100': activeTab !== 'visi' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 active:scale-95 group">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/20">
                                    <i class="fas fa-bullseye text-sm"></i>
                                </span>
                                Visi & Misi
                            </button>
                            <button @click="activeTab = 'struktur'"
                                :class="{ 'bg-brand-600 text-white shadow-lg shadow-brand-500/30': activeTab === 'struktur', 'text-gray-600 hover:bg-gray-100': activeTab !== 'struktur' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 active:scale-95 group">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/20">
                                    <i class="fas fa-sitemap text-sm"></i>
                                </span>
                                Struktur
                            </button>
                            <button @click="activeTab = 'tugas'"
                                :class="{ 'bg-brand-600 text-white shadow-lg shadow-brand-500/30': activeTab === 'tugas', 'text-gray-600 hover:bg-gray-100': activeTab !== 'tugas' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 active:scale-95 group">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/20">
                                    <i class="fas fa-tasks text-sm"></i>
                                </span>
                                Tugas & Fungsi
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="lg:w-3/4 min-h-[400px]">
                    <!-- Profil Content -->
                    <div x-show="activeTab === 'profil'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="text-brand-600">—</span> Informasi Umum
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-8 text-lg">
                            Badan Pusat Statistik adalah Lembaga Pemerintah Non Kementerian yang bertanggung jawab langsung
                            kepada Presiden.
                            BPS berkomitmen menyediakan data statistik berkualitas untuk Indonesia Maju, melalui sensus dan
                            survei yang
                            terstandarisasi secara nasional maupun internasional.
                        </p>
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200 aspect-video">
                            <iframe class="w-full h-full"
                                src="https://www.youtube.com/embed/aj7wICO2HTo?si=Gaz9pVqKZQluXhhN"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div>
                    </div>

                    <!-- Visi Content -->
                    <div x-show="activeTab === 'visi'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-6">Visi & Misi 2025-2029</h3>

                        <div class="bg-brand-50 rounded-2xl p-8 mb-8 border-l-4 border-brand-500">
                            <h4 class="font-bold text-brand-800 mb-2 uppercase text-sm tracking-wider">Visi</h4>
                            <p class="text-xl md:text-2xl font-heading font-bold text-gray-900 italic">
                                "Lembaga yang Independen, Tepercaya, dan Berperan Aktif dalam Mendukung Perumusan
                                Kebijakan Berbasis Data"
                            </p>
                        </div>

                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-check-circle text-brand-500"></i> Misi Kami
                            </h4>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-4">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">1</span>
                                    <p class="text-gray-600">Menyediakan statistik berkualitas yang berstandar
                                        nasional dan internasional.</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">2</span>
                                    <p class="text-gray-600">Membina K/L/D/I melalui Sistem Statistik Nasional yang
                                        berkesinambungan.</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">3</span>
                                    <p class="text-gray-600">Mewujudkan pelayanan prima di bidang statistik dan
                                        membangun SDM yang unggul.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Struktur Content -->
                    <div x-show="activeTab === 'struktur'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-6">Struktur Organisasi</h3>
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 text-center mb-6">
                            <img src="{{ asset('assets/img/struktur.png') }}" alt="Struktur Organisasi"
                                class="max-w-full h-auto mx-auto rounded-lg shadow-sm">
                        </div>
                        <p class="text-gray-600 mb-4">
                            Berdasarkan Peraturan Kepala Badan Pusat Statistik Nomor 8 Tahun 2020. Susunan
                            organisasi terdiri dari Kepala, Subbagian Umum, dan Kelompok Jabatan Fungsional.
                        </p>
                        <a href="https://ppid.bps.go.id/upload/doc/Peraturan_BPS_Nomor_8_Tahun_2020_1674630366.pdf"
                            target="_blank" class="inline-flex items-center gap-2 text-brand-600 font-bold hover:underline">
                            Lihat Peraturan Lengkap <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>

                    <!-- Tugas Content -->
                    <div x-show="activeTab === 'tugas'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-6">Tugas & Fungsi</h3>
                        <p class="text-gray-600 mb-6">
                            BPS Kabupaten mempunyai tugas melaksanakan penyelenggaraan statistik dasar di tingkat
                            kabupaten sesuai dengan ketentuan peraturan perundang-undangan.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h5 class="font-bold text-gray-900 mb-2">Statistik Dasar</h5>
                                <p class="text-sm text-gray-500">Penyelenggaraan statistik dasar di kabupaten.</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h5 class="font-bold text-gray-900 mb-2">Koordinasi</h5>
                                <p class="text-sm text-gray-500">Koordinasi kegiatan fungsional dalam pelaksanaan
                                    tugas.</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h5 class="font-bold text-gray-900 mb-2">Pembinaan</h5>
                                <p class="text-sm text-gray-500">Pembinaan terhadap kegiatan instansi pemerintah di
                                    bidang statistik.</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h5 class="font-bold text-gray-900 mb-2">Administrasi</h5>
                                <p class="text-sm text-gray-500">Pelayanan administrasi umum di bidang perencanaan,
                                    keuangan, dan SDM.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Apps Section -->
    <section id="apps" class="py-24 bg-gray-50 relative overflow-hidden">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-brand-200 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-accent-200 rounded-full blur-3xl opacity-30"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Portfolio Digital</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-2">Daftar Aplikasi</h2>
                <p class="mt-4 text-gray-400 max-w-2xl mx-auto">Inovasi digital untuk pelayanan statistik yang lebih baik,
                    efisien, dan transparan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- SiMabar -->
                <a href="/simabar" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/app1.png') }}" alt="SiMabar"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        01. SiMabar</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Sistem Informasi Matriks Beban Kerja.
                        Monitoring beban kerja pegawai secara real-time.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>

                <!-- Temfora -->
                <a href="/temfora" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/app2.png') }}" alt="Temfora"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        02. Temfora</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Aplikasi Persuratan BPS Kabupaten Batang
                        Hari. Manajemen surat menyurat digital.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>

                <!-- Cerdas -->
                <a href="/cerdas" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/app3.png') }}" alt="Cerdas"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        03. Cerdas</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Portal Cakap Memahami Ragam Data
                        Statistik. Edukasi literasi statistik.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>

                <!-- Kabar Kito -->
                <a href="https://sites.google.com/view/kabarkito" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/app4.png') }}" alt="Kabar Kito"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        04. Kabar Kito</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Kerjo Bersamo Kito Biso. Media
                        komunikasi internal dan kolaborasi.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>

                <!-- PST-1504 -->
                <div
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/app5.png') }}" alt="PST 1504"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        05. PST-1504</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Pelayanan Statistik Terpadu Kabupaten
                        Batang Hari.</p>
                    <div class="flex flex-wrap gap-2 mt-auto">
                        <a href="https://pst-1504.vercel.app" target="_blank"
                            class="px-3 py-1 bg-gray-100 hover:bg-brand-600 hover:text-white rounded-lg text-xs font-bold transition-colors">Petugas</a>
                        <a href="https://pst-1504.vercel.app/queue-display" target="_blank"
                            class="px-3 py-1 bg-gray-100 hover:bg-brand-600 hover:text-white rounded-lg text-xs font-bold transition-colors">Antrian</a>
                        <a href="https://pst-1504.vercel.app/visitor-form/" target="_blank"
                            class="px-3 py-1 bg-gray-100 hover:bg-brand-600 hover:text-white rounded-lg text-xs font-bold transition-colors">Pengunjung</a>
                    </div>
                </div>

                <!-- SPANENG -->
                <a href="/spaneng" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/spaneng.png') }}" alt="SPANENG"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        06. SPANENG</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Sistem Penilaian dan Evaluasi Beban
                        Kerja Mitra Terintegrasi.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>

                <!-- SiMadu -->
                <a href="https://simadu.bps-batanghari.com" target="_blank"
                    class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-2 active:scale-95 flex flex-col h-full overflow-hidden">
                    <div
                        class="relative h-48 rounded-2xl overflow-hidden bg-gray-100 mb-6 group-hover:ring-4 ring-brand-100 transition-all">
                        <img src="{{ asset('assets/img/simadu.png') }}" alt="SiMadu"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white font-bold text-sm">Buka Aplikasi <i
                                    class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                    <h3
                        class="text-xl font-heading font-bold text-gray-900 group-hover:text-brand-600 transition-colors mb-2">
                        07. SiMadu</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-grow">Sistem Monitoring untuk Kegiatan
                        Distribusi Terpadu.</p>
                    <div class="w-full h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-brand-500 group-hover:w-full transition-all duration-700 delay-100"></div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Akses Cepat</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-2">Layanan Publik</h2>
                <p class="mt-4 text-gray-400 max-w-2xl mx-auto">Akses mudah ke berbagai kanal informasi dan data statistik
                    resmi Badan Pusat Statistik.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Service Item -->
                <a href="https://batangharikab.bps.go.id" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-brand-600 text-2xl">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-brand-600 transition-colors">
                        Website Resmi</h4>
                    <p class="text-xs text-gray-500">batangharikab.bps.go.id</p>
                </a>

                <a href="https://indah.bps.go.id" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-green-600 text-2xl">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">Indah
                    </h4>
                    <p class="text-xs text-gray-500">Indonesia Data Hub</p>
                </a>

                <a href="https://batangharikab.bps.go.id/publication.html" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-purple-600 text-2xl">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                        Publikasi</h4>
                    <p class="text-xs text-gray-500">Unduh Publikasi BPS</p>
                </a>

                <a href="https://batangharikab.bps.go.id/pressrelease.html" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-red-600 text-2xl">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">Berita
                        Resmi</h4>
                    <p class="text-xs text-gray-500">Berita Resmi Statistik</p>
                </a>

                <a href="https://batangharikab.bps.go.id/subject.html" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-orange-600 text-2xl">
                        <i class="fas fa-table"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">
                        Tabel Dinamis</h4>
                    <p class="text-xs text-gray-500">Data Sektoral</p>
                </a>

                <a href="https://romantik.bps.go.id" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-pink-600 text-2xl">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-pink-600 transition-colors">
                        Romantik</h4>
                    <p class="text-xs text-gray-500">Rekomendasi Statistik</p>
                </a>



                <a href="https://sirusa.bps.go.id" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-teal-600 text-2xl">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">
                        Metadata</h4>
                    <p class="text-xs text-gray-500">Definisi & Konsep</p>
                </a>

                <a href="https://ppid.bps.go.id/?mfd=1504&_gl=1*4od6v2*_ga*MTYwMzIwNTIzOC4xNzY4MjA0MjAz*_ga_XXTTVXWHDB*czE3NjgyMDQyMDMkbzEkZzEkdDE3NjgyMDQ5NjAkajM4JGwwJGgw"
                    target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-gray-800 text-2xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-gray-800 transition-colors">PPID
                    </h4>
                    <p class="text-xs text-gray-500">Informasi Publik</p>
                </a>

                <a href="https://pst.bps.go.id" target="_blank"
                    class="group p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 active:scale-95 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300 text-blue-600 text-2xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4 class="font-heading font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                        Aplikasi PST
                    </h4>
                    <p class="text-xs text-gray-500">Layanan Statistik Terpadu</p>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-20 bg-gradient-to-br from-brand-900 to-brand-700 relative overflow-hidden text-white">
        <div
            class="absolute inset-0 opacity-10 bg-[url('{{ asset('assets/img/logo.png') }}')] bg-center bg-no-repeat bg-contain">
        </div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-heading font-bold mb-6">Butuh Bantuan Data?</h2>
            <p class="text-xl text-brand-100 mb-10">Hubungi kami atau datang langsung ke Pelayanan Statistik Terpadu (PST)
                BPS Kabupaten Batang Hari.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6282129660986') }}?text={{ urlencode(env('WHATSAPP_MESSAGE', 'Halo Admin BPS Batang Hari. Saya mengakses melalui Website untuk menggunakan Layanan Chatbot AI Statistik. Saya ingin bertanya mengenai data dan informasi BPS.')) }}"
                    target="_blank"
                    class="px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-green-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-2xl"></i> Chatbot Statistik
                </a>
                <a href="mailto:bps1504@bps.go.id"
                    class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/30 rounded-xl font-bold text-lg backdrop-blur-md transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-envelope text-xl"></i> Kirim Email
                </a>
            </div>
        </div>
    </section>
    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6282129660986') }}?text={{ urlencode(env('WHATSAPP_MESSAGE', 'Halo Admin BPS Batang Hari. Saya mengakses melalui Website untuk menggunakan Layanan Chatbot AI Statistik. Saya ingin bertanya mengenai data dan informasi BPS.')) }}"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 group flex items-center justify-center w-16 h-16 bg-green-500 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300 hover:scale-110 active:scale-95 animate-bounce-slow">
        <i class="fab fa-whatsapp text-3xl text-white"></i>
        <!-- Tooltip -->
        <span
            class="absolute right-20 bg-white px-4 py-2 rounded-lg shadow-xl text-gray-800 text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap hidden md:block">
            Chat dengan Kami 👋
        </span>
        <!-- Notification Dot -->
        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
    </a>

    <style>
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
    </style>
@endsection