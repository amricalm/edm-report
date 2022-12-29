<!-- Jquery js-->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>

<!-- Bootstrap5 js-->
<script src="{{asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{asset('assets/plugins/date-picker/jquery-ui.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>

<!-- Custom js-->
<script src="{{asset('assets/js/custom.js')}}"></script>
<script src="{{ asset('assets/js/notifIt.js')}}"></script>
<script src="{{ asset('js/NumberFormat.js')}}"></script>
<script src="{{ asset('js/autoNumeric-min.js')}}"></script>
{{-- <script src="{{ asset('js/bootstrap-combobox.js')}}"></script> --}}
<script src="{{ asset('js/adn.js')}}"></script>
<script src="{{ asset('js/app.js')}}"></script>

<script src="{{ asset('js/jquery.autocomplete.js')}}"></script>
<script src="{{ asset('js/jquery.loadingModal.min.js')}}"></script>
<script src="{{ asset('js/imask.min.js')}}"></script>
<script src="{{ asset('js/moment.min.js')}}"></script>

@stack('footer')
<script>
    var msg = '{{Session::get('
    alert ')}}';
    var exist = '{{Session::has('
    alert ')}}';
    if (exist) {
        showAlert('error', 'Oops...', msg)
    }

    function msgSukses(msg) {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
        })
    }
    function msgWarning(msg) {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
        })
    }
    function msgError(msg){
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: "<div style='font-size:12px;'>Jika diperlukan, bisa discreenshot, kirimkan ke admin!</div><br>"+msg,
            showConfirmButton: true
        })
    }

    function showAlert(tipe,judul,pesan)
    {
        Swal.fire({
            type: tipe,
            title: judul,
            html: pesan
        })
    }

</script>
@yield('footer')
