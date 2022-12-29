@extends('templates.index')
@include('templates.komponen.sweetalert')
@section('body')
<!---Global-loader-->

<!--- End Global-loader-->
<!-- Page -->
<div class="page">
    <div class="page-main">
        @include('templates.menu')
        <!-- App-Content -->
        <div class="app-content main-content">
            <div class="side-app">
                @include('templates.navbar')
                <!--Page header-->
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0 text-primary">{{ $judul }}</h4>
                    </div>
                    <div class="page-rightheader">
                    </div>
                </div>
                <!--End Page header-->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">


                        <div class="card mb-4">
                            <div class="card-body py-4">
                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-1 form-label">Status</label>
                                    <div class="col-md-4">
                                        <select name="status" id="pilih-status" class="form-select form-control  form-control-sm  mb-2" tabindex="1">
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1" selected>Aktif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="tampil" class="btn btn-sm btn-primary"><i class="fe fe-search"></i>Tampil</button>
                                    </div>
                                </div>
                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-1 col-form-label-sm">Cari </label>
                                    <div class="col-md-4">
                                        <input type="text"  id="tx-search"  placeholder="" autocomplete="off" name="" class="form-control  form-control-sm  mb-2" tabindex="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive" id="tbl">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->

<!--#region --- Modal -------------------------------->
<div class="modal" tabindex="-1" id="add-modal" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen bwa-modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="page-title text-primary">Donatur</h4>
            <div class="float-right">
                <button type="button" class="btn btn-outline-danger position-relative" id="batal"><i class="fe fe-slash"></i>
                        Tutup</button>
                <button type="button" id="btn-close" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
        </div>
        <div class="modal-body bwa-modal_body">
            <div class="bwa-modal-container">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row row-sm">
                                    <div class="col-lg-6 col-md-12">
                                        <form class="" id='trn'>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Kode</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-kd" autocomplete="off" name="kd_pelanggan" class="form-control  form-control-sm  mb-2" tabindex="12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Lengkap</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-nm" name="nm_pelanggan" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </form><!-- END Form -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-sm">
                                    <table class="table table-bordered card-table table-vcenter table-striped" width="100%">
                                        <thead>
                                          <tr class="border-top">
                                            <th class="py-2" width="5%">#</th>
                                            <th class="py-2">Tanggal Transaksi</th>
                                            <th class="py-2">Program</th>
                                            <th class="py-2">Project</th>
                                            <th class="py-2 angka">Nilai</th>
                                          </tr>
                                        </thead>
                                        <tbody class="border-bottom" id='trn-body'>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
      </div>
    </div>
  </div>
<!--#endregion === Modal=== -->

<!--#region --- Modal Riwayawt -------------------------------->
{{-- <div class="modal" tabindex="-1" id="add-modal" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen bwa-modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="page-title text-primary">Riwayat Transaksi</h4>
            <div class="float-right">
                <button type="button" class="btn btn-outline-danger position-relative" id="batal"><i class="fe fe-slash"></i>
                        Batal</button>
                <button type="button" id="btn-close" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
        </div>
        <div class="modal-body bwa-modal_body">
            <div class="bwa-modal-container">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row row-sm">
                                    <div class="col-lg-6 col-md-12">
                                        <form class="" id='trn'>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Lengkap</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="rw-nm-lengkap" autocomplete="off" name="nm_lengkap" class="form-control  form-control-sm  mb-2" tabindex="12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Alamat</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="rw-alamat" name="alamat" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">HP</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="rw-hp" name="hp" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Email</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="rw-email" name="email" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        </form><!-- END Form -->
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
      </div>
    </div>
  </div> --}}
<!--#endregion === Modal=== -->

@endsection
@section('footer')
<?php
    //use App\Http\Controllers\MDonaturController;
?>
<script type="text/javascript">

    var mode = 'TAMBAH';

    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ajaxStart(function() {
		    $("#ajax-loading").show();
	    });

        $(document).ajaxStop(function() {
            $("#ajax-loading").hide();
        });

        loadData(1,$('#pilih-status').val(),$('#cr-program').val(),$('#tx-search').val(),'asc','nm_lengkap');

        // $('#tx-kd').on('change', function(){
        //     var el = $(this);
        //     $.ajax({
        //         url:"{{ route('mproject.isExist') }}",
        //         method:"POST",
        //         data:{kdProject:el.val()},
        //         success:function(data){
        //             if(data==='true'){
        //                 alert('Kode Project telah ada.');
        //                 el.val('');
        //                 el.focus();
        //             }
        //         }
        //     });
        // });

        $(document).on('click', '.halaman', function(){
           var page = $(this).attr("id");
           var status = $('#pilih-status').val();
           var prg = $('#cr-program').val();
           var cari = $('#tx-search').val();
           var sort = $('#sortAktif').data('sort');
           var sortField = $('#sortAktif').data('sort-field');
           loadData(page,status,prg,cari, sort,sortField);
        });

        $(document).on('click','.sort', function(){
            var status = $('#pilih-status').val();
            var cari = $('#tx-search').val();
            var prg = $('#cr-program').val();
            var sort = $(this).data('sort');
            sort = (sort==='asc')?'desc':'asc';
            var sortField = $(this).data('sort-field');
            loadData(1,status,prg,cari, sort,sortField);
        });

        $('#tampil').click(function () {
           var status = $('#pilih-status').val();
           var cari = $('#tx-search').val();
           var prg = $('#cr-program').val();
           var sort = $('#sortAktif').data('sort');
           var sortField = $('#sortAktif').data('sort-field');
           loadData(1,status,prg, cari, sort,sortField);
        });

        $('#createNew').click(function(){
            mode = 'TAMBAH';
            $('#tx-kd').prop('readonly', false);
            var frm = document.querySelector("#trn")
            frm.reset();
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kd = $(this).closest('tr').find('td').eq(0).html();
            $('#tx-kd').val(kd.trim());
            $('#tx-kd').prop('readonly', true);
            $.ajax({
                url:"{{ route('donatur.get') }}",
                method:"POST",
                data:{kd:kd.trim()},
                success:function(data){
                        var obj = data[0];

                        $('#tx-kd').val(obj.kd_pelanggan);
                        $('#tx-nm').val(obj.nm_lengkap);

                        var trn = data['trn'];
                        $('#trn-body').empty().append(trn);
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $('#add-modal').on('shown.bs.modal', function (e) {
            //AktivasiTab();
            $('#tx-kd').focus();
        });

        $('#btn-close').click(function () {
            $('#add-modal').hide();
        });

        $('#batal').click(function () {
            $('#add-modal').hide();
        });

        $('#save').click(function(e) {
            if(validateForm()) {
                e.preventDefault();
                var el = $(this);
                el.html('...');

                var kirim = true;
                const frm = new FormData(document.querySelector("#trn"));
                const obj = Object.fromEntries(frm.entries());

                obj.mode = mode;
                console.log(obj);
                $.ajax({
                    data: obj,
                    url:  "{{ route('mproject.save') }}",
                    type: "POST",
                    success: function(msg) {
                        console.log(msg);
                        if (msg.IsSuccess){
                            alert('Sukses.');
                            $('#trn').trigger("reset");
                            $('#add-modal').hide();
                            window.location.reload();
                        }else{
                            alert(msg.Message)
                        }

                    },
                    error: function(msg) {
                        console.log('Error:', msg);
                    }
                }).done(function(msg){
                    el.html('Simpan');
                    el.removeAttr('disabled');

                    $('#tx-kd').prop('readonly', false);
                });//$.ajax
            }
        });

        function validateForm() {
            var errors = [];
            var form = document.getElementsByTagName('form')[0];

            var elKd = document.getElementById("tx-kd");
            var elNm = document.getElementById("tx-nm");
            var elTgl = document.getElementById("tx-tgl-dr");
            if (elKd.value.trim() === "") {
                errors.push({
                elem: elKd,
                message: "Kode Tidak Boleh Kosong."
                });
            }

            if (elNm.value.trim() === "") {
                errors.push({
                elem: elNm,
                message: "Nama Tidak Boleh Kosong."
                });
            }

            // if (elTgl.value === "") {
            //     errors.push({
            //     elem: elTgl,
            //     message: "Tanggal Mulai Tidak Boleh Kosong."
            //     });
            // }


            var str ='';
            errors.forEach(item => {
                str += item.message + '\n'
            });

            if (str!='')alert(str);

            return errors.length === 0;
        }

        $('body').on('click', '.delete', function() {
            var id = $(this).data("KdProject");
            var url = '{{ collect(request()->segments())->last() }}';

            Swal.fire({
                title: 'Betul akan dihapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url + '/' + id,
                        success: function(data) {
                            Swal.fire(
                                'Deleted!',
                                '{{ $judul }} sudah dihapus',
                                'success'
                            )
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            })
        });
    });


function loadData(page,status, program, cari, sort,sortField){
    $.ajax({
        url:"{{route('donatur.tbl')}}",
        method:"POST",
        data:{page:page, status:status, program:program, cari:cari, sort:sort,sortField:sortField},
        success:function(data){
            $('#tbl').html(data);
        }
    });
}

</script>
@endsection
