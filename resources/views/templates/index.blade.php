<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('templates/header')
</head>

<body class="app sidebar-mini">
    <!---Global-loader-->
    <div id="global-loader">
        <img src="{{ asset('assets/images/svgs/loader.svg')}}" alt="loader">
    </div>
    <div id="ajax-loading">
        <img src="{{ asset('img/loading.svg')}}" alt="loader-ajax">
    </div>
    <!--- End Global-loader-->

    @yield('body')
    @include('templates/footer')
</body>

</html>
