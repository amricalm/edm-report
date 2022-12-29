@extends('templates.index')
@include('templates.komponen.multiselect-checklist')
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
                            <a href="javascript:void(0)" id="save" class="btn btn-outline-primary" ><i class="fe fe-save"></i>Simpan</a>
                        </div>
                    </div>
                </div>
                <!--End Page header-->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">


                        <div class="card mb-2">
                            <div class="card-body py-4">
                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-3 form-label">Kode</label>
                                    <div class="col-md-9">
                                        <input type="text" id="tx-no" autocomplete="off" name="kd_group" value="{{$kdGroup}}" class="form-control  form-control-sm  mb-2" disabled tabindex="0">
                                    </div>
                                </div>
                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-3 form-label">Nama Group</label>
                                    <div class="col-md-9">
                                        <input type="text" id="tx-nm" autocomplete="off" name="nm_group" value="{{$nmGroup}}" class="form-control  form-control-sm  mb-2" disabled tabindex="1">
                                    </div>
                                </div>

                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-3 form-label">Akses Data Cabang</label>
                                    <div class="col-md-9">
                                        <select name="pilih-cabang" id="pilih-cabang" multiple="multiple" class="multi-select" tabindex="2">
                                            @foreach($lstCabang as $item)
                                            <option value="{{ $item->ID }}">{{ $item->Nm }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-body py-4">

                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-3 form-label">Pilih Aplikasi</label>
                                    <div class="col-md-9">
                                        <select name="pilih-aplikasi" id="pilih-aplikasi" class="form-select form-control  form-control-sm  mb-2" tabindex="3">
                                            <option value="inovaGL">inovaGL</option>
                                            <option value="BukuBank">Buku Bank</option>
                                            <option value="MgmDonasi" selected>Managemen Donasi</option>
                                            <option value="RptManagement">Laporan Pendapatan dan Project</option>
                                            <option value="Andhana">Keamanan</option>
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

@endsection
@section('footer')
<?php
    use App\Http\Controllers\ScGroupController;
?>
<script type="text/javascript">

    var mode = 'TAMBAH';
    str = <?php echo json_encode($strCabang); ?>;
	var dataCabang= str.split(';');

    $(function() {
               
        $('#pilih-cabang').multipleSelect();
        $('#pilih-cabang').multipleSelect('setSelects',dataCabang);

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

        loadData(1);

        $('#tampil').click(function () {
           loadData(1);
        });

        $('#pilih-aplikasi').on('change',function() {
            loadData(1);
        });

        $('#save').click(function(){
            var el = $(this);
            el.html('...');

            var tbl = tbl = document.getElementById("tbl-role");
            var baris = tbl.tBodies[0].getElementsByTagName('tr');

            var id = $('#tx-no').val();
            let oGroup = {kdGroup: id, lstRole:[], lstCabang:[]}
            let lstCabang  = $('#pilih-cabang').multipleSelect('getSelects');
            oGroup.lstCabang = lstCabang;
            oGroup.aplikasi =  $('#pilih-aplikasi').val();
            
            for (i = 0; i < baris.length; i++) {
                var objID = $(baris.item(i).cells.item(1)).children('.kd-obj').val();
                var baca = $(baris.item(i).cells.item(2)).children('.akses').is(':checked');
                var roleEntri = "";
                if (objID == "FDonasi")
                {
                    roleEntri = $(baris.item(i).cells.item(3)).children('.role-entri').val();
                }

                let dtl = {objID : objID, akses : baca, roleEntri: roleEntri}
                oGroup.lstRole.push(dtl);
            }

            console.log(oGroup);

            $.ajax({
                data: oGroup,
                url:  "{{ route('scrole.save') }}",
                type: "POST",
                success: function(msg) {
                    console.log(msg);
                    if (msg.IsSuccess){
                        alert('Sukses.');
                    }else{
                        alert(msg.Message)
                    }

                },
                error: function(msg) {
                    console.log('Error:', msg);
                    el.html('<i class="fe fe-save"></i>Simpan');
                    el.removeAttr('disabled');
                }
            }).done(function(msg){
                el.html('<i class="fe fe-save"></i>Simpan');
                el.removeAttr('disabled');
            });//$.ajax

        });


    
    
    
    });//$function


function loadData(page){
    var kdGroup = $('#tx-no').val();
    var aplikasi = $('#pilih-aplikasi').val();
    $.ajax({
        url:"/scgroup/getTabelRole",
        method:"POST",
        data:{page:page, kdGroup:kdGroup, aplikasi:aplikasi},
        success:function(data){
            $('#tbl').html(data);
        }
    })
}


</script>
@endsection
