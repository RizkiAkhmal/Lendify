<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')">
                            {{ __('Kategori') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.alat.index')" :active="request()->routeIs('admin.alat.*')">
                            {{ __('Alat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.peminjaman.index')" :active="request()->routeIs('admin.peminjaman.*')">
                            {{ __('Peminjaman') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pengembalian.index')" :active="request()->routeIs('admin.pengembalian.*')">
                            {{ __('Pengembalian') }}
                        </x-nav-link>
                         <x-nav-link :href="route('admin.log.index')" :active="request()->routeIs('admin.log.*')">
                            {{ __('Log') }}
                        </x-nav-link>
                    @endif

                     @if(Auth::user()->role === 'petugas')
                        <x-nav-link :href="route('petugas.katalog.index')" :active="request()->routeIs('petugas.katalog.*')">
                            {{ __('Katalog') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.approval.index')" :active="request()->routeIs('petugas.approval.*')">
                            {{ __('Approval') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.monitoring.index')" :active="request()->routeIs('petugas.monitoring.*')">
                            {{ __('Monitoring') }}
                        </x-nav-link>
                        {{-- <x-nav-link :href="route('petugas.laporan.index')" :active="request()->routeIs('petugas.laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link> --}}
                    @endif

                    @if(Auth::user()->role === 'peminjam')
                        <x-nav-link :href="route('peminjam.katalog.index')" :active="request()->routeIs('peminjam.katalog.*')">
                            {{ __('Katalog') }}
                        </x-nav-link>
                        <x-nav-link :href="route('peminjam.peminjaman.index')" :active="request()->routeIs('peminjam.peminjaman.*')">
                            {{ __('Riwayat') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right side elements -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(Auth::user()->role === 'peminjam')
                    <a href="{{ route('peminjam.cart.index') }}" class="relative inline-flex items-center p-2 mr-4 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @php 
                            $cartCount = 0;
                            if(session()->has('cart')) {
                                foreach(session('cart') as $item) $cartCount += $item['jumlah'];
                            }
                        @endphp
                        @if($cartCount > 0)
                        <span class="cart-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-white transform translate-x-0 -translate-y-0 bg-red-600 border-2 border-white rounded-full">{{ $cartCount }}</span>
                    @else
                        <span class="cart-badge hidden absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-white transform translate-x-0 -translate-y-0 bg-red-600 border-2 border-white rounded-full">0</span>
                        @endif
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')">
                    {{ __('Kategori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.alat.index')" :active="request()->routeIs('admin.alat.*')">
                    {{ __('Alat') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'petugas')
                <x-responsive-nav-link :href="route('petugas.katalog.index')" :active="request()->routeIs('petugas.katalog.*')">
                    {{ __('Katalog') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.approval.index')" :active="request()->routeIs('petugas.approval.*')">
                    {{ __('Approval') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.monitoring.index')" :active="request()->routeIs('petugas.monitoring.*')">
                    {{ __('Monitoring') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.laporan.index')" :active="request()->routeIs('petugas.laporan.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'peminjam')
                <x-responsive-nav-link :href="route('peminjam.katalog.index')" :active="request()->routeIs('peminjam.katalog.*')">
                    {{ __('Katalog') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peminjam.cart.index')" :active="request()->routeIs('peminjam.cart.*')">
                    {{ __('Keranjang') }}
                    @php 
                        $cartCountResp = 0;
                        if(session()->has('cart')) {
                            foreach(session('cart') as $item) $cartCountResp += $item['jumlah'];
                        }
                    @endphp
                    @if($cartCountResp > 0)
                        <span class="cart-badge ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $cartCountResp }}</span>
                    @else
                        <span class="cart-badge hidden ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">0</span>
                    @endif
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peminjam.peminjaman.index')" :active="request()->routeIs('peminjam.peminjaman.*')">
                    {{ __('Riwayat') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
