@extends('templates.index')
@include('templates.komponen.sweetalert')
@include('templates.komponen.multiselect-checklist')

@section('body')
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
                        {{-- <div class="btn-list">
                            <a href="#" id="xlsExport" class="btn btn-outline-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>
                                </svg> XLS</a>
                        </div> --}}
                    </div>
                </div>
                <!--End Page header-->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body py-4">
                                <div class="form-group row row-sm mb-0 align-items-center">
                                    <label class="col-md-1 fs-11">Periode</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-tgl-dr" placeholder="yyyy-mm-dd" autocomplete="off" name="" class="form-control  form-control-sm  mb-2  huruf-kecil" tabindex="2">
                                    </div>
                                    <label class="col-auto text-right fs-11">s/d</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-tgl-sd"  placeholder="yyyy-mm-dd" autocomplete="off" name="" class="form-control  form-control-sm  mb-2  huruf-kecil" tabindex="3">
                                    </div>
                                    <label class="col-md-1 fs-11">Program</label>
                                    <div class="col-sm-3">
                                        <select class="multiple-select" id="program" multiple="multiple" name="program" data-width="50">
                                            @foreach($program as $item)
                                                <option value="{{$item->kd_kategori}}">{{$item->nm_kategori}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="tampil" class="btn btn-sm btn-primary" tabindex="9"><i class="fe fe-search"></i>Tampil</button>
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

<script type="text/javascript">

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});

    $(document).ajaxStart(function() {$("#ajax-loading").show();});
    $(document).ajaxStop(function() {$("#ajax-loading").hide();});
    //--- end ajax setup

    $("input:text").focus(function() { $(this).select(); } ); // saat fokus, langsung pilih(block)

	// Tanggal

    $('#tx-tgl-dr, #tx-tgl-sd').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

    $('#tx-tgl-dr').val(moment().format('YYYY-MM-DD'));
    $('#tx-tgl-sd').val(moment().format('YYYY-MM-DD'));

    loadData(1,$('#tx-tgl-dr').val(),$('#tx-tgl-sd').val());

    $(document).on('click', '.halaman', function(){
        var page = $(this).attr("id");
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var cabang = $('#cabang').val();
        var program = $('#program').val();
        var jenisPeriode = $('#jenis-periode').val();
        var sales = $('#sales').val();
        loadData(page,tglDr,tglSd,cabang,program,jenisPeriode,sales);
    });

    $('#tampil').click(function () {
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var cabang = $('#cabang').val();
        var program = $('#program').val();
        var jenisPeriode = $('#jenis-periode').val();
        var sales = $('#sales').val();
        loadData(1,tglDr,tglSd,cabang,program,jenisPeriode,sales);
    });

    $(document).on("click", "#xlsExport", function()
    {
        let query = buildQuery({
        action: "{{route('export.project.xls')}}",
        url: {
                tglDr: $('#tx-tgl-dr').val(),
                tglSd: $('#tx-tgl-sd').val(),
                kdCabang: $('#cabang').val(),
                kdProgram:  $('#program').val(),
                jenisPeriode: $('#jenis-periode').val(),
                sales: $('#sales').val()
            }
        });
        location.replace(query);
    });
    $('.multiple-select').multipleSelect()
});

buildQuery = ({action, url}) => `${action}?${Object.entries(url)
  .map(pair => pair.map(encodeURIComponent).join('='))
  .join('&')}`;

function loadData(page,tglDr,tglSd,cabang,program,jenisPeriode,sales){
    $.ajax({
        url:"{{ route('get.project.tbl') }}",
        method:"POST",
        data:{page:page,tglDr:tglDr,tglSd:tglSd,kdCabang:cabang,
            kdProgram:program,jenisPeriode:jenisPeriode,sales:sales},
        success:function(data){
                $('#tbl').html(data);
                $("#ajax-loading").hide();
        }
    });
}
</script>
@endsection
