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
                        <div class="btn-list">
                            <a href="javascript:void(0)" id="createNew" class="btn btn-outline-primary" ><i class="fe fe-plus-square"></i>Tambah</a>
                        </div>
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
                                    <div class="col-md-3">
                                        <select name="status" id="pilih-status" class="form-select form-control  form-control-sm  mb-2" tabindex="1">
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1" selected>Aktif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="tampil" class="btn btn-sm btn-primary"><i class="fe fe-search"></i>Tampil</button>
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
          <h4 class="page-title text-primary">Program</h4>
            <div class="float-right">
                <button type="button" class="btn btn-outline-primary position-relative" id="save" tabindex="-1"><i class="fe fe-save"></i>
                    Simpan</button>
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
                                                        <label class="col-md-3 form-label">Kode Program</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-kd-program" autocomplete="off" name="kd_program" class="form-control  form-control-sm  mb-2" tabindex="12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Program</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-nm-program" name="nm_program" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Alias</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-alias" name="alias" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="14">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Kategori</label>
                                                        <div class="col-md-9">
                                                            <select name="kd_kategori" id="kd-kategori" class="form-select form-control  form-control-sm  mb-2" tabindex="15">
                                                                <option value="">-- Pilih Kategori Program --</option>
                                                                @foreach($ktProgram as $item)
                                                                <option value="{{$item->kd_kategori}}">{{$item->nm_kategori}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nilai</label>
                                                        <div class="col-md-9">
                                                            <input type="number" id="tx-nilai" name="nilai" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="16">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">
                                                        </label>
                                                        <div class="col-md-9 col-auto">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="chk-aktif" name="aktif" tabindex="17">
                                                                <span class="custom-control-label">Program Dinonaktifkan</span>
                                                            </label>
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
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
    </div>
  </div>
<!--#endregion === Modal=== -->

@endsection
@section('footer')
<?php
    use App\Http\Controllers\MProgramController;
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
        loadData(1,$('#pilih-status').val());

        $('#tx-kd-program').on('change', function(){
            var el = $(this);
            $.ajax({
                url:"{{ route('mprogram.isExist') }}",
                method:"POST",
                data:{kdProgram:el.val()},
                success:function(data){
                    if(data==='true'){
                        alert('Kode Program telah ada.');
                        el.val('');
                        el.focus();
                    }
                }
            });
        });

        $(document).on('click', '.halaman', function(){
           var page = $(this).attr("id");
           var status = $('#pilih-status').val();
           loadData(page,status);
        });

        $('#tampil').click(function () {
           var status = $('#pilih-status').val();
           loadData(1,status);
        });

        $('#createNew').click(function(){
            mode = 'TAMBAH';
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kdProgram = $(this).closest('tr').find('td').eq(0).html();
            $('#tx-kd-program').val(kdProgram.trim());
            $('#tx-kd-program').prop('readonly', true);
            $.ajax({
                url:"{{ route('mprogram.get') }}",
                method:"POST",
                data:{kdProgram:kdProgram.trim()},
                success:function(data){
                    console.log(data);
                        var obj = data[0];

                        $('#tx-kd-program').val(obj.kd_program);
                        $('#tx-nm-program').val(obj.nm_program);
                        $('#tx-alias').val(obj.alias);
                        $('#kd-kategori').val(obj.kd_kategori);
                        $('#tx-nilai').val(obj.nilai);
                        $("#chk-aktif").prop('checked', !(Boolean(Number(obj.aktif))));
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $(document).on('click','.btn-delete',function(){
            var kdProgram = $(this).closest('tr').find('td').eq(0).html();
            checkdelete(kdProgram.trim(),$(this));
        });

        $('#add-modal').on('shown.bs.modal', function (e) {
            //AktivasiTab();
            $('#tx-kd-program').focus();
        });

        $('#btn-close').click(function () {
            $('#add-modal').hide();
        });

        $('#batal').click(function () {
            mode = 'TAMBAH';
            $('#tx-kd-program').prop('readonly', false);
            var frm = document.querySelector("#trn")
            frm.reset();
        });

        $('#save').click(function(e) {
            e.preventDefault();
            var el = $(this);
            el.html('...');

            var kirim = true;
            const frm = new FormData(document.querySelector("#trn"));
            const obj = Object.fromEntries(frm.entries());

            obj.mode = mode;

            $.ajax({
                data: obj,
                url:  "{{ route('mprogram.save') }}",
                type: "POST",
                success: function(msg) {
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

                $('#tx-kd-program').prop('readonly', false);
            });//$.ajax
        });

    });


function loadData(page,status){
    $.ajax({
        url:"/mprogram/getTabel",
        method:"POST",
        data:{page:page, status:status},
        success:function(data){
            $('#tbl').html(data);
        }
    })
}

function checkdelete(id,el)
{
    Swal.fire({
        title: 'Yakin?',
        text: "Anda yakin ingin menghapus data ini?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                url:"{{route('mprogram.delete') }}",
                method:"POST",
                data:{kdProgram:id},
                success:function(data){
                    if(data.Message=='Sukses')
                    {
                        el.closest('tr').remove();
                        var status = $('#pilih-status').val();
                        loadData(1,status);
                        $('#trn').trigger("reset");
                        $('#tx-kd-program').prop('readonly', false);
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            }).done(function(view) {
                    //window.location.reload();
            });

        }
    })
}

</script>
@endsection
