<nav :class="{ 'bg-white/90 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent text-white': !scrolled }"
    class="fixed w-full z-50 transition-all duration-300 top-0 left-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <img class="h-10 w-auto transition-transform duration-300 hover:scale-105"
                    src="{{ asset('assets/img/logo.png') }}" alt="BPS Logo">
                <div>
                    <h1 :class="{ 'text-gray-900': scrolled, 'text-white': !scrolled }"
                        class="font-heading font-bold text-lg leading-tight uppercase tracking-wider">BPS Batang Hari
                    </h1>
                    <p :class="{ 'text-brand-600': scrolled, 'text-brand-200': !scrolled }"
                        class="text-xs font-medium tracking-widest">Portal Layanan Statistik</p>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home"
                    :class="{ 'text-gray-700 hover:text-brand-600': scrolled, 'text-white/90 hover:text-white': !scrolled }"
                    class="text-sm font-semibold uppercase tracking-wide transition-colors">Beranda</a>
                <a href="#about"
                    :class="{ 'text-gray-700 hover:text-brand-600': scrolled, 'text-white/90 hover:text-white': !scrolled }"
                    class="text-sm font-semibold uppercase tracking-wide transition-colors">Tentang Kami</a>
                <a href="#apps"
                    :class="{ 'text-gray-700 hover:text-brand-600': scrolled, 'text-white/90 hover:text-white': !scrolled }"
                    class="text-sm font-semibold uppercase tracking-wide transition-colors">Aplikasi</a>
                <a href="#services"
                    :class="{ 'text-gray-700 hover:text-brand-600': scrolled, 'text-white/90 hover:text-white': !scrolled }"
                    class="text-sm font-semibold uppercase tracking-wide transition-colors">Layanan</a>
                <a href="#contact"
                    :class="{ 'bg-brand-600 text-white hover:bg-brand-700': scrolled, 'bg-white text-brand-700 hover:bg-gray-100': !scrolled }"
                    class="px-5 py-2 rounded-full font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5">Kontak</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    :class="{ 'text-gray-900': scrolled, 'text-white': !scrolled }" class="focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition
        class="md:hidden bg-white shadow-xl absolute w-full left-0 top-20 border-t border-gray-100">
        <div class="px-4 pt-4 pb-6 space-y-2">
            <a href="#home" @click="mobileMenuOpen = false"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50">Beranda</a>
            <a href="#about" @click="mobileMenuOpen = false"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50">Tentang
                Kami</a>
            <a href="#apps" @click="mobileMenuOpen = false"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50">Aplikasi</a>
            <a href="#services" @click="mobileMenuOpen = false"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50">Layanan</a>
            <a href="#contact" @click="mobileMenuOpen = false"
                class="block px-3 py-2 rounded-md text-base font-medium bg-brand-50 text-brand-700 hover:bg-brand-100">Kontak</a>
        </div>
    </div>
</nav>