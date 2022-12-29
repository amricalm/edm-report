<!--aside open-->
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="index.html">
            <img src="{{asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="BWA logo">
            <img src="{{asset('assets/images/brand/logo1.png')}}" class="header-brand-img dark-logo" alt="BWA logo">
            <img src="{{asset('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="BWA logo">
            <img src="{{asset('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="BWA logo">
        </a>
    </div>
    <ul class="side-menu app-sidebar3">
        <li class="slide">
            <a class="side-menu__item" href="{{ url('/')}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z" />
                </svg>
                <span class="side-menu__label">Dashboard</span></a>
        </li>
        <!-- Menu dengan sub-sub menu -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M5 22h14c1.103 0 2-.897 2-2V9a1 1 0 0 0-1-1h-3V7c0-2.757-2.243-5-5-5S7 4.243 7 7v1H4a1 1 0 0 0-1 1v11c0 1.103.897 2 2 2zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v1H9V7zm-4 3h2v2h2v-2h6v2h2v-2h2l.002 10H5V10z"></path></svg><span class="side-menu__label">Donasi</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('donasi') }}" class="slide-item">Input Donasi</a></li>
                <li><a href="{{ url('bukubank') }}" class="slide-item">Buku Bank</a></li>
                {{-- <li><a href="{{ url('bukuedc') }}" class="slide-item">Buku EDC</a></li> --}}
                {{-- <li><a href="{{ url('donasi/konfirmasi') }}" class="slide-item">Konfirmasi</a></li> --}}
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-1.209A4.92 4.92 0 0 0 19 5.5C19 3.57 17.43 2 15.5 2c-1.622 0-2.705 1.482-3.404 3.085C11.407 3.57 10.269 2 8.5 2 6.57 2 5 3.57 5 5.5c0 .596.079 1.089.209 1.5H4c-1.103 0-2 .897-2 2v2c0 1.103.897 2 2 2v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-4.5-3c.827 0 1.5.673 1.5 1.5C17 7 16.374 7 16 7h-2.478c.511-1.576 1.253-3 1.978-3zM7 5.5C7 4.673 7.673 4 8.5 4c.888 0 1.714 1.525 2.198 3H8c-.374 0-1 0-1-1.5zM4 9h7v2H4V9zm2 11v-7h5v7H6zm12 0h-5v-7h5v7zm-5-9V9.085L13.017 9H20l.001 2H13z"></path></svg>
            <span class="side-menu__label">Donatur</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('donatur') }}" class="slide-item">Daftar Donatur</a></li>
                {{-- <li><a href="{{ url('settelemarketer') }}" class="slide-item">Penugasan Agent</a></li> --}}
             </ul>
        </li>
        {{-- <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 2 .001 4H5V5h14zM5 11h8v8H5v-8zm10 8v-8h4.001l.001 8H15z"></path></svg>
            <span class="side-menu__label">Integrasi</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('telemarketing') }}" class="slide-item">Telemarketing</a></li>
            </ul>
        </li> --}}
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 17V7c0-2.168-3.663-4-8-4S4 4.832 4 7v10c0 2.168 3.663 4 8 4s8-1.832 8-4zM12 5c3.691 0 5.931 1.507 6 1.994C17.931 7.493 15.691 9 12 9S6.069 7.493 6 7.006C6.069 6.507 8.309 5 12 5zM6 9.607C7.479 10.454 9.637 11 12 11s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2V9.607zM6 17v-2.393C7.479 15.454 9.637 16 12 16s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2z"></path></svg>
            <span class="side-menu__label">Data Induk</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('mprogram') }}" class="slide-item">Program</a></li>
                <li><a href="{{ url('mproject') }}" class="slide-item">Project</a></li>
                <li><a href="{{ url('kas') }}" class="slide-item">Kas</a></li>
                <li><a href="{{ url('cabang') }}" class="slide-item">Kantor/Cabang</a></li>
                <li><a href="{{ url('jaringan') }}" class="slide-item">Jaringan</a></li>
                <li><a href="{{ url('salesman') }}" class="slide-item">Fundraiser</a></li>

            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M12 22c4.879 0 9-4.121 9-9s-4.121-9-9-9-9 4.121-9 9 4.121 9 9 9zm0-16c3.794 0 7 3.206 7 7s-3.206 7-7 7-7-3.206-7-7 3.206-7 7-7zm5.284-2.293 1.412-1.416 3.01 3-1.413 1.417zM5.282 2.294 6.7 3.706l-2.99 3-1.417-1.413z"></path><path d="M11 9h2v5h-2zm0 6h2v2h-2z"></path></svg>
            <span class="side-menu__label">Keamanan</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('scpengguna') }}"href="{{ url('') }}" class="slide-item">Daftar Pengguna</a></li>
                <li><a href="{{ url('scgroup') }}" class="slide-item">Daftar Group</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 11h8V3H3zm2-6h4v4H5zM3 21h8v-8H3zm2-6h4v4H5zm8-12v8h8V3zm6 6h-4V5h4zm-5.99 4h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2zm4 0h2v2h-2zm2 2h2v2h-2zm-4 0h2v2h-2zm2-6h2v2h-2zm2 2h2v2h-2z"></path></svg>
            <span class="side-menu__label">Konfigurasi</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('setting') }}" class="slide-item">Pengaturan</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"></path></svg>
            <span class="side-menu__label">Laporan</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('laporan/donasi') }}" class="slide-item">Donasi</a></li>
                <li><a href="{{ url('laporan/donasiperrekening') }}" class="slide-item">Donasi Per Rekening</a></li>
                <li><a href="{{ url('laporan/donasiperjaringan') }}" class="slide-item">Donasi Per Jaringan</a></li>
                <li><a href="{{ url('laporan/donasiperfundraiser') }}" class="slide-item">Donasi Per Fundraiser</a></li>
                <li><a href="{{ url('laporan/donasiperproject') }}" class="slide-item">Donasi Per Project</a></li>
            </ul>
        </li>
    </ul>
</aside>
<!--aside closed-->
