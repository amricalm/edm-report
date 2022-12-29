<!--Footer-->
@if(Request::url() !== URL::to('donasi/create'))
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                Copyright © 2021 <a href="javascript:void(0);">BWA</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0);"> andhana </a> All rights reserved
            </div>
        </div>
    </div>
</footer>
@endif
<!-- End Footer-->
<!-- Back to top -->
<a href="#top" id="back-to-top"><i class="fe fe-chevron-up"></i></a>

<!-- Jquery js-->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>

<!-- Bootstrap5 js-->
<script src="{{asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!--Sidemenu js-->
<script src="{{asset('assets/plugins/sidemenu/sidemenu.js')}}"></script>

<!--Othercharts js-->
{{-- <script src="{{asset('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

<!-- Circle-progress js-->
<script src="{{asset('assets/js/circle-progress.min.js')}}"></script>

<!-- Jquery-rating js-->
<script src="{{asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script> --}}


<!-- P-scroll js-->
{{-- <script src="{{asset('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
<script src="{{asset('assets/plugins/p-scrollbar/p-scroll1.js')}}"></script>
<script src="{{asset('assets/plugins/p-scrollbar/p-scroll.js')}}"></script> --}}

<!--INTERNAL Flot Charts js-->
{{-- <script src="{{asset('assets/plugins/flot/jquery.flot.js')}}"></script>
<script src="{{asset('assets/plugins/flot/jquery.flot.fillbetween.js')}}"></script>
<script src="{{asset('assets/plugins/flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{asset('assets/js/chart.flot.sampledata.js')}}"></script> --}}

<!-- INTERNAL Chart js -->
{{-- <script src="{{asset('assets/plugins/chart/chart.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/chart/utils.js')}}"></script> --}}

<!-- INTERNAL Apexchart js -->
{{-- <script src="{{asset('assets/js/apexcharts.js')}}"></script> --}}

<!--INTERNAL Moment js-->
{{-- <script src="{{asset('assets/plugins/moment/moment.js')}}"></script> --}}

<!--INTERNAL Index js-->
{{-- <script src="{{asset('assets/js/index1.js')}}"></script> --}}

<!-- INTERNAL Data tables -->
{{-- <script src="{{asset('assets/plugins/datatables/DataTables/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/DataTables/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/Responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/Responsive/js/responsive.bootstrap5.min.js')}}"></script> --}}

<!-- INTERNAL Select2 js -->
<script src="{{asset('assets/plugins/date-picker/jquery-ui.js')}}"></script>
<script src="{{asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>
{{-- <script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script> --}}
{{-- <script src="{{asset('assets/plugins/bootstrap-multiselect/bootstrap-multiselect.min.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/select2.js')}}"></script> --}}

<!-- Simplebar JS -->
{{-- <script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>

<!-- Rounded bar chart js-->
<script src="{{asset('assets/js/rounded-barchart.js')}}"></script> --}}

<!-- Custom js-->
<script src="{{asset('assets/js/custom.js')}}"></script>

<script src="{{ asset('js/NumberFormat.js')}}"></script>
<script src="{{ asset('js/autoNumeric-min.js')}}"></script>
{{-- <script src="{{ asset('js/bootstrap-combobox.js')}}"></script> --}}
<script src="{{ asset('js/adn.js')}}"></script>
<script src="{{ asset('js/app.js')}}"></script>

<script src="{{ asset('js/jquery.autocomplete.js')}}"></script>
<script src="{{ asset('assets/js/notifIt.js')}}"></script>
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

    //cekPesan();
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
            vars[key] = value;
        });
        return vars;
    }

    function showAlert(tipe, judul, pesan) {
        Swal.fire({
            type: tipe,
            title: judul,
            html: pesan
           
        })
    }
    @php if(Request::url() !== URL::to('donasi/create')) { @endphp
        $(document).ready(function() {
            $('#simpan_password').on('click', function(event) {
                var formData = new FormData($("#ganti_password")[0]);
                $.ajax({
                    url: "",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        showAlert('success', 'Berhasil', 'Ganti Password Berhasil.');
                        $('#ganti_password').trigger("reset");
                        window.location.reload();
                    },
                    error: function(data) {
                        showAlert('error', 'Gagal', 'Ganti Password Gagal.');
                        $('#ganti_password').trigger("reset");
                    }
                });
            });

            $('#simpan_pp').on('click', function(event) {
                var formData = new FormData($("#ganti_pp")[0]);
                $.ajax({
                    url: "",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        respon('success', 'Berhasil', 'Photo Profile berhasil disimpan');
                        $('#ganti_pp').trigger("reset");
                        // console.log(data.msg); //menampilkan nama foto profil yang baru
                        $('#uploaded_image').html(data.uploaded_image);
                        window.location.reload();
                    },
                    error: function(data) {
                        respon('error', 'Gagal', 'Ganti Photo Profile Gagal.');
                        $('#ganti_pp').trigger("reset");
                    }
                });
            });
        });
    @php } @endphp
</script>
@yield('footer')
