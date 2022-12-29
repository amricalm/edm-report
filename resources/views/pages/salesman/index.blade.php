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
                                    <label class="col-md-2 form-label">Status</label>
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
                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-2 form-label">Cabang/Divisi</label>
                                    <div class="col-md-3">
                                        <select name="cr-cabang" id="pilih-cabang" class="form-select form-control  form-control-sm  mb-2" tabindex="2">
                                            <option value="">--- Pilih Salah Satu Cabang/Divisi ---</option>
                                            @foreach($cabang as $item)
                                                <option value="{{$item->ID}}">{{$item->Nm}}</option>
                                            @endforeach
                                        </select>
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
          <h4 class="page-title text-primary">Fundraiser</h4>
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
                                                        <label class="col-md-3 form-label">Kode Fundraiser</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-kd" name="kd_sales" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="-1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Nama Fundraiser</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="tx-nm" name="nm_sales" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="1">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">Cabang/Divisi</label>
                                                        <div class="col-md-9">
                                                            <select name="cabang" id="tx-cabang" class="form-select form-control  form-control-sm  mb-2" tabindex="2">
                                                                <option value="">--- Pilih Salah Satu Cabang/Divisi ---</option>
                                                                @foreach($cabang as $item)
                                                                    <option value="{{$item->ID}}">{{$item->Nm}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0">
                                                        <label class="col-md-3 form-label">
                                                        </label>
                                                        <div class="col-md-9 col-auto">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="chk-aktif" name="aktif" tabindex="3">
                                                                <span class="custom-control-label">Fundraiser Dinonaktifkan</span>
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
      </div>
    </div>
  </div>
<!--#endregion === Modal=== -->

@endsection
@section('footer')
<?php
    use App\Http\Controllers\MSalesController;
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
        loadData(1,$('#pilih-status').val(),$('#pilih-cabang').val());

        // $('#tx-kd').on('change', function(){
        //     var el = $(this);
        //     $.ajax({
        //         url:"{{ route('msales.isExist') }}",
        //         method:"POST",
        //         data:{kdSales:el.val()},
        //         success:function(data){
        //             if(data==='true'){
        //                 alert('Kode TeleAgent telah ada.');
        //                 el.val('');
        //                 el.focus();
        //             }
        //         }
        //     });
        // });

        $(document).on('click', '.halaman', function(){
           var page = $(this).attr("id");
           var status = $('#pilih-status').val();
           var crCabang = $('#pilih-cabang').val();
           loadData(page,status,crCabang);
        });

        $('#tampil').click(function () {
           var status = $('#pilih-status').val();
           var crCabang = $('#pilih-cabang').val();
           loadData(1,status,crCabang);
        });

        $('#createNew').click(function(){
            mode = 'TAMBAH';
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kdSales = $(this).closest('tr').find('input').val();
            $('#tx-kd').val(kdSales.trim());
            $('#tx-kd').prop('readonly', true);
            $.ajax({
                url:"{{ route('msales.get') }}",
                method:"POST",
                data:{kdSales:kdSales.trim()},
                success:function(data){
                    console.log(data);
                        var obj = data[0];

                        $('#tx-kd').val(obj.kd_sales);
                        $('#tx-nm').val(obj.nm_sales);
                        $('#tx-cabang').val(obj.kd_cabang);
                        $("#chk-aktif").prop('checked', !(Boolean(Number(obj.aktif))));
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $(document).on('click','.btn-delete',function(){
            var kdSales = $(this).closest('tr').find('input').val();
            checkdelete(kdSales.trim(),$(this));
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
            //$('#tx-kd').prop('readonly', false);
            var frm = document.querySelector("#trn")
            frm.reset();
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
                    url:  "{{ route('msales.save') }}",
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
                });//$.ajax
            }
        });
    });

function validateForm() {
	var errors = [];
	var form = document.getElementsByTagName('form')[0];

	//var elKd = document.getElementById("tx-kd");
	var elNm = document.getElementById("tx-nm");
	var elCabang = document.getElementById("tx-cabang");
    // if (elKd.value.trim() === "") {
	// 	errors.push({
	// 	elem: elKd,
	// 	message: "Kode Tidak Boleh Kosong."
	// 	});
	// }

    if (elNm.value.trim() === "") {
		errors.push({
		elem: elNm,
		message: "Nama Tidak Boleh Kosong."
		});
	}

	if (elCabang.value === "") {
		errors.push({
		elem: elCabang,
		message: "Cabang Tidak Boleh Kosong."
		});
	}


    var str ='';
    errors.forEach(item => {
        str += item.message + '\n'
    });

    if (str!='')alert(str);

	return errors.length === 0;
}


function loadData(page,status,cabang){
    $.ajax({
        url:"{{ route('msales.getTabel') }}",
        method:"POST",
        data:{page:page, status:status, cabang:cabang},
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
                url:"{{route('msales.delete') }}",
                method:"POST",
                data:{kdSales:id},
                success:function(data){
                    if(data.Message=='Sukses')
                    {
                        el.closest('tr').remove();
                        loadData(1,$('#pilih-status').val());
                        $('#trn').trigger("reset");
                        $('#tx-kd').prop('readonly', false);
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
