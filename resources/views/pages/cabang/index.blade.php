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
          <h4 class="page-title text-primary">Tambah Cabang/Divisi</h4>
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
                                        <form action="#" class="" id='trn'">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Kode</label>
                                                        <div class="col-md-9">
                                                            <input type="number" id="tx-kd" name="ID" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="1">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Cabang/Divisi</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-nm" name="Nm" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="2">
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
    use App\Http\Controllers\CabangController;
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

        $('#tx-kd').on('change', function(){
            var el = $(this);
            $.ajax({
                url:"{{ route('cabang.isExist') }}",
                method:"POST",
                data:{kdCabang:el.val()},
                success:function(data){
                    if(data==='true'){
                        alert('Kode Cabang telah ada.');
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
            var frm = document.querySelector("#trn");
            frm.reset();
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kdCabang = $(this).closest('tr').find('td').eq(0).find('input').val();
            $('#tx-kd').val(kdCabang.trim());
            $('#tx-kd').prop('readonly', true);
            $.ajax({
                url:"{{ route('cabang.get') }}",
                method:"POST",
                data:{kdCabang:kdCabang.trim()},
                success:function(data){

                    var obj = data[0];

                    $('#tx-kd').val(obj.ID);
                    $('#tx-nm').val(obj.Nm);
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $(document).on('click','.btn-delete',function(){
            var kdCabang = $(this).closest('tr').find('td').eq(0).find('input').val();
            checkdelete(kdCabang.trim(),$(this));
        });

        $('#add-modal').on('shown.bs.modal', function (e) {
            //AktivasiTab();
            $('#tx-nm').focus();
        });

        $('#btn-close').click(function () {
            $('#add-modal').hide();
        });

        $('#batal').click(function () {
            mode = 'TAMBAH';
            var frm = document.querySelector("#trn")
            frm.reset();
            $('#tx-kd').prop('readonly', false);
        });


        $('#save').click(function(e) {
            if (validateForm())
            {
                const frm = new FormData(document.querySelector("#trn"));
                e.preventDefault();
                var el = $(this);
                el.html('...');

                var kirim = true;
                const obj = Object.fromEntries(frm.entries());

                obj.mode = mode;
                $.ajax({
                    data: obj,
                    url:  "{{ route('cabang.save') }}",
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
                    $('#tx-kd').prop('readonly', false);
                });//$.ajax
            }//validate()
        });


    });

function validateForm() {
	var errors = [];
	var form = document.getElementsByTagName('form')[0];

	var elKd = document.getElementById("tx-kd");
	var elNm = document.getElementById("tx-nm");

	if (elKd.value === "") {
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


    var str ='';
    errors.forEach(item => {
        str += item.message + '\n'
    });

    if (str!='')alert(str);

	return errors.length === 0;
}


function loadData(page,status){
    $.ajax({
        url:"/cabang/getTabel",
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
                url:"{{route('cabang.delete') }}",
                method:"POST",
                data:{kdCabang:id},
                success:function(data){
                    if(data.Message=='Sukses')
                    {
                        el.closest('tr').remove();
                        loadData(1,$('#pilih-status').val());
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            }).done(function(view) {
                    // window.location.reload();
            });

        }
    })
}

</script>
@endsection
