@extends('templates.index')
@include('templates.komponen.sweetalert')
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
                        <div class="btn-list">
                            <a href="javascript:void(0)" id="createNew" class="btn btn-outline-primary"><i class="fe fe-plus-square"></i> Tambah</a>
                        </div>
                    </div>
                </div>
                <!--End Page header-->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body py-4">
                                <div class="form-group row row-sm mb-0 align-items-center">
                                    <label class="col-auto text-right fs-11">Pembayaran</label>
                                    <div class="col-md-3">
                                        <select name="kas" id="kas" class="form-select form-control  form-control-sm  mb-2 huruf-kecil" tabindex="1">
                                            @foreach($kas as $item){
                                                <option value="{{$item->kd_kas}}">{{$item->nm_kas}}</option>
                                            }
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-auto text-right fs-11">Periode</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-tgl-dr" placeholder="yyyy-mm-dd" autocomplete="off" name="" class="form-control  form-control-sm  mb-2  huruf-kecil" tabindex="2">
                                    </div>
                                    <label class="col-auto text-right fs-11">s/d</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-tgl-sd"  placeholder="yyyy-mm-dd" autocomplete="off" name="" class="form-control  form-control-sm  mb-2  huruf-kecil" tabindex="3">
                                    </div>
                                    {{-- <label class="col-auto text-right fs-11">Status</label>
                                    <div class="col-md-2">
                                        <select name="alur-donasi" id="alur-donasi" class="form-select form-control  form-control-sm  mb-2 huruf-kecil" tabindex="4">
                                            @foreach($alurDonasi as $key => $value){
                                                <option value="{{$value}}">{{$key}}</option>
                                            }
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <label class="col-auto text-right fs-11">Jenis</label>
                                    <div class="col-md-2">
                                        <select name="jenis-periode" id="jenis-periode" class="form-select form-control  form-control-sm  mb-2 huruf-kecil" tabindex="8">
                                            <option value="PeriodeSetor">Periode Setor</option>
                                            <option value="PeriodeTransaksi">Periode Transaksi</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row row-sm mb-0 align-items-center">
                                    <label class="col-auto text-right fs-11">Kantor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="col-md-3">
                                        <select name="cabang" id="cabang" class="form-select form-control  form-control-sm  mb-2 huruf-kecil" tabindex="5">
                                            @foreach($cabang as $item){
                                                <option value="{{$item->kd_cabang}}">{{$item->nm_cabang}}</option>
                                            }
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-auto text-right fs-11">Kwitansi</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-kwitansi-dr" placeholder="" autocomplete="off" name="" class="form-control  form-control-sm  mb-2 huruf-kecil" tabindex="6">
                                    </div>
                                    <label class="col-auto text-right fs-11">s/d</label>
                                    <div class="col-md-2">
                                        <input type="text" id="tx-kwitansi-sd"  placeholder="" autocomplete="off" name="" class="form-control  form-control-sm  mb-2 huruf-kecil" tabindex="7">
                                    </div>
                                    
                                </div>
                                <div class="form-group row row-sm mb-0">
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

    loadData(1,$('#tx-tgl-dr').val(),$('#tx-tgl-sd').val(),$('#tx-kwitansi-dr').val(),$('#tx-kwitansi-sd').val(),$('#cabang').val(),$('#kas').val(),$('#jenis-periode').val(),'','asc','tgl');


    $(document).on('click','.sort', function(){
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noKwitansiDr = $('#tx-kwitansi-dr').val();
        var noKwitansiSd = $('#tx-kwitansi-sd').val();
        var cabang = $('#cabang').val();
        var kas = $('#kas').val();
        var jenisPeriode = $('#jenis-periode').val();
        var alurDonasi = $('#alur-donasi').val();
        var sort = $(this).data('sort');
        sort = (sort==='asc')?'desc':'asc';
        var sortField = $(this).data('sort-field');
        loadData(1,tglDr, tglSd,noKwitansiDr, noKwitansiSd, cabang, kas, jenisPeriode, alurDonasi, sort,sortField);
     });

    $(document).on('click', '.halaman', function(){
        var page = $(this).attr("id");
        // var tglDr = $('#tx-tgl-dr').val();
        // var tglSd = $('#tx-tgl-sd').val();
        // var sort = $('#sortAktif').data('sort');
        // var sortField = $('#sortAktif').data('sortField');

        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noKwitansiDr = $('#tx-kwitansi-dr').val();
        var noKwitansiSd = $('#tx-kwitansi-sd').val();
        var cabang = $('#cabang').val();
        var kas = $('#kas').val();
        var jenisPeriode = $('#jenis-periode').val();
        var alurDonasi = $('#alur-donasi').val();
        var sort = $('#sortAktif').data('sort');
        var sortField = $('#sortAktif').data('sort-field');
        loadData(page,tglDr, tglSd,noKwitansiDr, noKwitansiSd, cabang, kas, jenisPeriode, alurDonasi, sort,sortField);

        ///loadData(page,tglDr, tglSd,sort,sortField);
    });

    //var eltgl =  document.getElementById('tx-tgl');
    //var momentFormat = 'YYYY-MM-DD';
    // var momentMask = IMask(eltgl, {
    //     mask: Date,
    //     pattern: momentFormat,
    //     lazy: false,
    //     min: new Date(1970, 0, 1),
    //     max: new Date(2030, 0, 1),

    //     format: function (date) {
    //         return moment(date).format(momentFormat);
    //     },
    //     parse: function (str) {
    //         return moment(str, momentFormat);
    //     },

    //     blocks: {
    //         YYYY: {
    //         mask: IMask.MaskedRange,
    //         from: 1970,
    //         to: 2030
    //         },
    //         MM: {
    //         mask: IMask.MaskedRange,
    //         from: 1,
    //         to: 12
    //         },
    //         DD: {
    //         mask: IMask.MaskedRange,
    //         from: 1,
    //         to: 31
    //         },
    //         HH: {mask: IMask.MaskedRange,from: 0,to: 23},
    //         mm: {mask: IMask.MaskedRange,from: 0,to: 59 }
    //     }
    //     });

    // // END Tanggal

    $(document).on('click','.btn-delete',function(){
            checkdelete($(this).data('kd'),$(this));
    });

    $('#createNew').click(function () {
        mode = 'TAMBAH';
        var tambah = "{{URL::to('donasi/create')}}";
        window.open(tambah, "_blank");
    });

    $('#tampil').click(function () {
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noKwitansiDr = $('#tx-kwitansi-dr').val();
        var noKwitansiSd = $('#tx-kwitansi-sd').val();
        var cabang = $('#cabang').val();
        var kas = $('#kas').val();
        var jenisPeriode = $('#jenis-periode').val();
        var alurDonasi = $('#alur-donasi').val();
        var sort = $('#sortAktif').data('sort');
        var sortField = $('#sortAktif').data('sort-field');
        loadData(1,tglDr, tglSd,noKwitansiDr, noKwitansiSd, cabang, kas, jenisPeriode, alurDonasi, sort,sortField);
    });


    $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kd = $(this).closest('tr').find('td.id-link').eq(0).html();
            console.log(kd);
            var linkEdit = "{{URL::to('donasi/create')}}"+  "/" + kd;
            window.open(linkEdit, "_blank");

            //$('#tx-no').val(kd.trim());
            //$('#tx-no').prop('readonly', true);
            // $.ajax({
            //     url:"",
            //     method:"POST",
            //     data:{kd:kd.trim()},
            //     success:function(data){
            //         console.log(data);
            //         var obj = JSON.parse(data);

            //         $('#tx-no').val(obj[0].kd_tkm);
            //         $('#tx-tgl').val((obj[0].tgl).substr(0,10));
            //         $('#tx-dr').val(obj[0].dr);
            //         $("#tx-desk").val([obj[0].deskripsi]);

            //         var dtl = obj.items;

            //         var $tr    = $('#tbl-transaksi tbody .tr_clone');
            //         var clone;
            //         for (i=1;i<dtl.length;i++)
            //         {
            //             $clone = $tr.clone();
            //             $clone.find(':text').val('');
            //             $clone.find('.angka').val('0');
            //             $clone.find('td').eq(idxKolomProgram).html(entriKdProgram);
            //             $clone.find('td').eq(idxKolomProject).html(entriKdProject);

            //             $tr.after($clone);
            //         }
            //         $(".entri-kd-program").autocomplete({source: [dataKdProgram],autoselect: true});
            //         $(".entri-kd-project").autocomplete({source: [dataProject],autoselect: true});

            //         var i = 0;
            //         // $('#tbl-transaksi tbody tr').each(function(index, tr) {

            //         //     var akun = dataAkun.filter(item => item.toLowerCase().indexOf(dtl[i].kd_akun) > -1);
            //         //     var prj = dataProject.filter(item =>
            //         //     {
            //         //         let arr =item.split('[');
            //         //         let kd = arr[0];
            //         //         return (AdnToString(kd)===AdnToString(dtl[i].kd_project)? true: false);
            //         //     });

            //         //     $(this).find('td').eq(idxKolomProgram).find('input').first().val(dtl[i].kd_program);
            //         //     $(this).find('td').eq(idxKolomProject).find('input').first().val(prj);
            //         //     $(this).find('td').eq(idxKolomMemo).find('input').first().val(AdnToString(dtl[i].memo));
            //         //     $(this).find('td').eq(idxKolomDebet).find('input').first().val(AdnFormatNum(dtl[i].debet));
            //         //     $(this).find('td').eq(idxKolomKredit).find('input').first().val(AdnFormatNum(dtl[i].kredit));
            //         //     i++;
            //         // });

            //         UpdateTotalDebet(idxKolomDebet);UpdateTotalKredit(idxKolomKredit);
            //         setTabIndex();

            //     }
            // })

            // $('#ajax-loading').show();
            // setTimeout(() => {$('#add-modal').show();
            // $('#tx-tgl').focus();
            // $('#ajax-loading').hide();}, 1500);


    });

	$(document).on('click','.hapus-baris',function(e){
        if($(this).closest('table').find('tbody tr').length>1)
        {
            $(this).closest('tr').remove();
        }
        else
        {
            $('#tbl-transaksi .entri').val('');
            $('#td-total-debet').html('0');
            $('#td-total-kredit').html('0');
        }
        UpdateTotalDebet(idxKolomDebet);
        UpdateTotalKredit(idxKolomKredit);
	});
}); //end $(function()


function loadData(page,tglDr,tglSd,noKwitansiDr, noKwitansiSd, cabang,kas,jenisPeriode, alurDonasi, sort,sortField){
    $.ajax({
            url:"{{ route('donasi.tbl') }}",
            method:"POST",
            data:{page:page,tglDr:tglDr,tglSd:tglSd,kdCabang:cabang,kdKas:kas,
                jenisPeriode:jenisPeriode,alurDonasi:alurDonasi,
                noKwitansiDr:noKwitansiDr, noKwitansiSd:noKwitansiSd, sort:sort,sortField:sortField},
            success:function(data){
                    $('#tbl').html(data);
                    $("#ajax-loading").hide();
            }
    });//$.ajax
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
                    url:"{{ route('donasi.delete')}}",
                    method:"POST",
                    data:{kd:id},
                    success:function(data){
                        console.log(data);
                        if(data.Message=='Sukses')
                        {
                            el.closest('tr').remove();
                        }
                    }
                }).done(function(view) {
                        //window.location.reload();
                });

            }
        })
}

</script>
@endsection
