@component('templates.widgets')
    @slot('header')
    <link href="{{ asset('theme/bower_components/sweetalert2/sweetalert2.css')}}" rel="stylesheet" />
    @endslot
    @slot('footer')
    <!-- SWEETALERT -->
    <script src="{{asset('theme/bower_components/sweetalert2/sweetalert2.all.js')}}"></script>
    @endslot
@endcomponent

