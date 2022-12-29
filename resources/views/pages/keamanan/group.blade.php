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
                                    <label class="col-md-2 col-form-label-sm">Cari Nama Group</label>
                                    <div class="col-md-3">
                                        <input type="text"  id="tx-search"  placeholder="" autocomplete="off" name="" class="form-control  form-control-sm  mb-2" tabindex="0">
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
          <h4 class="page-title text-primary">Tambah Group</h4>
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
                                                        <label class="col-md-3 form-label">Kode Group</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-kd-group" autocomplete="off" name="kd_group" class="form-control  form-control-sm  mb-2" tabindex="12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Group</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-nm-group" name="nm_group" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
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
  </div>
<!--#endregion === Modal=== -->

@endsection
@section('footer')
<?php
    use App\Http\Controllers\ScGroupController;
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

        loadData(1,$('#tx-search').val(),'asc','nm_group');

        $('#tx-kd-group').on('change', function(){
            var el = $(this);
            $.ajax({
                url:"{{ route('scgroup.isExist') }}",
                method:"POST",
                data:{kdGroup:el.val()},
                success:function(data){
                    if(data==='true'){
                        alert('Kode Group telah ada.');
                        el.val('');
                        el.focus();
                    }
                }
            });
        });

        $(document).on('click', '.halaman', function(){
           var page = $(this).attr("id");
           var txSearch = $('#tx-search').val();
           var sort = $('#sortAktif').data('sort');
           var sortField = $('#sortAktif').data('sort-field');
           loadData(page,txSearch,sort,sortField);

        });

        $('#tampil').click(function () {
            var txCari = $('#tx-search').val();
            var sort = $('#sortAktif').data('sort');
            var sortField = $('#sortAktif').data('sort-field');
            loadData(1,txCari,sort,sortField);
        });

        $('#createNew').click(function(){
            mode = 'TAMBAH';
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kdGroup = $(this).closest('tr').find('td').eq(0).html();
            $('#tx-kd-group').val(kdGroup.trim());
            $('#tx-kd-group').prop('readonly', true);
            $.ajax({
                url:"{{ route('scgroup.get') }}",
                method:"POST",
                data:{kdGroup:kdGroup.trim()},
                success:function(data){
                        var obj = data[0];

                        $('#tx-kd-group').val(obj.kd_group);
                        $('#tx-nm-group').val(obj.nm_group);
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $(document).on('click','.btn-delete',function(){
            var kdGroup = $(this).closest('tr').find('td').eq(0).html();
            checkdelete(kdGroup.trim(),$(this));
        });

       
        $('#add-modal').on('shown.bs.modal', function (e) {
            //AktivasiTab();
            $('#tx-kd-group').focus();
        });

        $('#btn-close').click(function () {
            $('#add-modal').hide();
        });

        $('#batal').click(function () {
            mode = 'TAMBAH';
            $('#tx-kd-group').prop('readonly', false);
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
                url:  "{{ route('scgroup.save') }}",
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

                $('#tx-kd-group').prop('readonly', false);
            });//$.ajax
        });

        $(document).on('click','.sort', function(){
            var txSearch = $('#tx-search').val();
            var sort = $(this).data('sort');
            sort = (sort==='asc')?'desc':'asc';
            var sortField = $(this).data('sort-field');
            loadData(1,txSearch,sort,sortField);
        });



    });


function loadData(page,txSearch,sort,sortField){
    $.ajax({
        url:"/scgroup/getTabel",
        method:"POST",
        data:{page:page,txSearch:txSearch,sort:sort,sortField:sortField},
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
                url:"{{route('scgroup.delete') }}",
                method:"POST",
                data:{kdGroup:id},
                success:function(data){
                    if(data.Message=='Sukses')
                    {
                        el.closest('tr').remove();
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
