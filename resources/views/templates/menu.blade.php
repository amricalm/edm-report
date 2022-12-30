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
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"></path></svg>
            <span class="side-menu__label">Program</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('rekapkeuanganprogram') }}" class="slide-item">Rekap Keuangan</a></li>
                <li><a href="{{ url('rekapkeuanganprogramdtl') }}" class="slide-item">Rekap Keuangan [Detail]</a></li>
                <li><a href="{{ url('rekapkeuanganmatrik') }}" class="slide-item">Rekap Keuangan Matrik</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"></path></svg>
            <span class="side-menu__label">Proyek</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu" style="display: none;">
                <li><a href="{{ url('rekapkeuanganproject') }}" class="slide-item">Rekap Keuangan</a></li>
            </ul>
        </li>
    </ul>
</aside>
<!--aside closed-->
