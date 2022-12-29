@component('templates.widgets')
    @slot('header')
    <link href="{{ asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
    <style>
        .ms-choice span {
            overflow: hidden;
            width: 200px;
        }
    </style>
    @endslot
    @slot('footer')
    <script src="{{asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
    @endslot
@endcomponent

