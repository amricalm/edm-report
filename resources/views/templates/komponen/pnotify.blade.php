@component('templates.widgets')
    @slot('header')
    <link href="../css/pnotify.css" rel="stylesheet" />
    <link href="../css/pnotifydesktop.css" rel="stylesheet" />
    @endslot
    @slot('footer')
    <!-- PNotify -->
    <script src="../js/pnotify.js"></script>
    <script src="../js/pnotifydesktop.js"></script>
    @endslot
@endcomponent