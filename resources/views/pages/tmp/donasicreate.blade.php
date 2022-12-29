@extends('templates.index')
@include('templates.komponen.sweetalert')
@section('body')

<div class="page">
    <div class="page-main">
        <div class="page-header">
            <h4 class="col-md-3 page-title text-primary">Donasi</h4>
            <div class="float-right">
                <button type="button" class="btn btn-outline-primary position-relative" id="save"><i class="fe fe-save"></i>
                    Simpan</button>
                <button type="button" class="btn btn-outline-danger position-relative" id="batal"><i class="fe fe-slash"></i>
                    Batal</button>
            </div>
        </div>
        <!-- Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row row-sm">
                            <form id="trn" class="form-horizontal">
                                <hr class="border-primary m-0">
                                <div class="row pt-2 pb-2">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row row-sm mb-1">
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Status</label>
                                                    <div class="col-md-9">
                                                        <select name="" id="" class="form-select form-control  form-control-sm" tabindex="1">
                                                            <option value="">-- SEMUA --</option>
                                                            <option value="" selected="">1. Entri</option>
                                                            <option value="" selected="">2. Verifikasi</option>
                                                            <option value="" selected="">3. Pengesahan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-4 fs-11 text-right">Kantor</label>
                                                    <div class="col-md-8">
                                                        <select name="" id="" class="form-select form-control  form-control-sm" tabindex="2">
                                                            <option value=""></option>
                                                            <option value="">Balikpapan</option>
                                                            <option value="">Bandung</option>
                                                            <option value="" selected>Pusat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-primary m-0">
                                <div class="row border pt-2 pb-2">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row row-sm mb-1">
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Pembayaran</label>
                                                    <div class="col-md-9">
                                                        <select name="" id="" class="form-select form-control  form-control-sm  mb-2" tabindex="3">
                                                            <option value="">-- SEMUA --</option>
                                                            <option value="" selected>Kas</option>
                                                            <option value="">BNI 0555000009</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Kwitansi</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="4">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Cari HP</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm" autocomplete="off" tabindex="9">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-4 fs-11 text-right">Tgl. Transaksi</label>
                                                    <div class="col-md-4">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="5">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-4 fs-11 text-right">Tgl. Setor</label>
                                                    <div class="col-md-4">
                                                        <input type="text" id="tx-tgl" name="" class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="6">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-4 fs-11 text-right">Cari Email</label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="" name="" class="form-control  form-control-sm" autocomplete="off" tabindex="10">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Jaringan</label>
                                                    <div class="col-md-9">
                                                        <select name="" id="" class="form-select form-control  form-control-sm  mb-2" tabindex="7">
                                                            <option value="">-- Pilih Salah Satu Jaringan --</option>
                                                            <option value="">Telefundraising</option>
                                                            <option value="">Web</option>
                                                            <option value="">Corporate</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Sales</label>
                                                    <div class="col-md-9">
                                                        <select name="" id="" class="form-select form-control  form-control-sm  mb-2" tabindex="8">
                                                            <option value="">----</option>
                                                            <option value="">Ananda</option>
                                                            <option value="">ananda_cbg</option>
                                                            <option value="">Corp. Fundraising</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-primary m-0">
                                <div class="row pt-2 pb-2">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row row-sm mb-1">
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Nm Pdftr</label>
                                                    <div class="col-md-7">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="11">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn bg-dark-transparent" tabindex="12"><i class="ion ion-plus-round"></i></button>
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Nm Wakif</label>
                                                    <div class="col-md-7">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="13">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Alamat</label>
                                                    <div class="col-md-7">
                                                        <textarea id="" name="" rows="3" class="form-control" autocomplete="off" tabindex="14"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Kota-Kd Pos</label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="15">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" name="" id=""  class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="16">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Telp</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="" name="" class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="17">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Keterangan Verifikasi</label>
                                                    <div class="col-md-9">
                                                        <textarea id="" name="" rows="3" class="form-control" autocomplete="off" tabindex="21" disabled></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Propinsi</label>
                                                    <div class="col-md-9">
                                                        <select name="" id="" class="form-select form-control  form-control-sm  mb-2" tabindex="18">
                                                            <option value=""></option>
                                                            <option value="">Aceh</option>
                                                            <option value=""">Bali</option>
                                                            <option value="">Banten</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">HP</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="" name="" class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="19">
                                                    </div>
                                                </div>
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right">Email</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="" name="" class="form-control  form-control-sm  mb-2" autocomplete="off" tabindex="20">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- TABEL TRANSAKSI -->
                        <hr class="border-primary m-0">
                        <div class="row pt-2 pb-2">
                            <div class="col-md-12">
                                <table class="table-sm table-condensed table-bordered small"  id="tbl-transaksi">
                                    <thead>
                                        <tr>
                                            <th style="width:25%;">Program</th>
                                            <th style="width:35%;">Project</th>
                                            <th style="width:10%;">Qty</th>
                                            <th style="width:15%;" class=" text-right">Dana</th>
                                            <th style="width:15%;" class=" text-right">Jumlah</th>
                                            <th style="width:2%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="tr_clone mh-100">
                                            <td class="td-entri" style="max-width:100px;width:25%;"></td>
                                            <td class="td-entri" style="max-width:100px;width:35%;"></td>
                                            <td class="td-entri" style="width:10%;"><input id="" data-field="memo" value="" class="form-control input-sm entri" type="text" tabindex="23"></td>
                                            <td class="td-entri" style="width:15%;" class="text-right"><input id="" data-field="debet" class="form-control input-sm angka text-right entri" type="text" tabindex="24"></td>
                                            <td class="td-entri" style="width:15%;" class="text-right"><input id=""  value="0" data-field="kredit"  class="form-control input-sm angka text-right entri" type="text" tabindex="25"></td>
                                            <td class="td-entri" style="width:2%;"><button class="btn btn-block btn-danger btn-sm hapus-baris" id="hapus-baris" ><i class="fe fe-x"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="align-right" style="text-align:right;font-weight:bold">TOTAL TRANSFER</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="td-total-debet">0</td>
                                            <td class="align-right" style="text-align:right;font-weight:bold" colspan="2">TOTAL</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="td-total-kredit">0</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="align-right" style="text-align:right;font-weight:bold" colspan="4">Biaya Bank</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="td-total-kredit"><input id="" value="0" data-field="kredit"  class="form-control input-sm angka text-right entri" type="text" tabindex="26"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer-input')
<?php
    use App\Http\Controllers\MProgramController;
    use App\Http\Controllers\MProjectController;
?>
<script type="text/javascript">
//#region Deklarasi
    const idTabel = "tbl-transaksi";
    const idxKolomProgram =0, idxKolomProject=1, idxKolomMemo=2;
	const idxKolomDebet= 3;  const idxKolomKredit= 4;
	var mode = '';
	var entriKdProgram = '<input id="entri-kd-program" data-field="kd_program" value="" class="entri-kd-program form-control input-sm entri" type="text" tabindex="21">'
    const entriKdProject = '<input id="entri-kd-project" data-field="kd_project" value="" class="entri-kd-project form-control input-sm entri" type="text" tabindex="22">'
    var totalDebet=0; var totalKredit=0;

    str = <?php echo json_encode(MProgramController::getKd())?>;
	var dataKdProgram = str.split(',');

    str = <?php echo json_encode(MProjectController::getKd())?>;
	var dataKdProject = str.split(',');
    var str = <?php echo json_encode(MProjectController::getProject())?>;
	var dataProject = str.split('#');


	var util = {};
	util.key = {
	//   9: "tab",   13: "enter",   16: "shift",   18: "alt", 27: "esc",  33: "rePag",   34: "avPag",   35: "end",
	//   36: "home", 37: "left",    38: "up",      39: "right",  40: "down",
    112: "F1",  113: "F2",  114: "F3",  115: "F4",
	116: "F5",  117: "F6",  118: "F7",  119: "F8", 120: "F9",  121: "F10",  122: "F11",  123: "F12"
	}
//#endregion Deklarasi

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});

    $(document).ajaxStart(function() {$("#ajax-loading").show();});
    $(document).ajaxStop(function() {$("#ajax-loading").hide();});
    //--- end ajax setup

    $("input:text").focus(function() { $(this).select(); } ); // saat fokus, langsung pilih(block)
	$("#tbl-transaksi tbody tr:first-child").find('td').eq(idxKolomProgram).append(entriKdProgram);
    $("#tbl-transaksi tbody tr:first-child").find('td').eq(idxKolomProject).append(entriKdProject);

	// Tanggal

    $('#tx-tgl-dr, #tx-tgl-sd').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

    $('#tx-tgl-dr').val(moment().format('YYYY-MM-DD'));
    $('#tx-tgl-sd').val(moment().format('YYYY-MM-DD'));

    loadData(1,$('#tx-tgl-dr').val(),$('#tx-tgl-sd').val(),$('#search-no-bukti').val(),'asc','kd_tkm');


    $('#tx-no').on('change',function(){
        var el = $(this);
        $.ajax({
            url:"",
            method:"POST",
            data:{noBukti:el.val()},
            success:function(data){
                if(data==='true'){
                    alert('No. Bukti telah ada.');
                    el.val('');
                    el.focus();
                }
            }
        });
    });

    $(document).on('click','.sort', function(){
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noBukti = $('#search-no-bukti').val();
        var sort = $(this).data('sort');
        sort = (sort==='asc')?'desc':'asc';
        var sortField = $(this).data('sort-field');
        loadData(1,tglDr, tglSd,noBukti,sort,sortField);
     });

    $(document).on('click', '.halaman', function(){
        var page = $(this).attr("id");
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noBukti = $('#search-no-bukti').val();
        var sort = $('#sortAktif').data('sort');
        var sortField = $('#sortAktif').data('sortField');
        loadData(page,tglDr, tglSd,noBukti,sort,sortField);
    });

    var eltgl =  document.getElementById('tx-tgl');
    var momentFormat = 'YYYY-MM-DD';
    var momentMask = IMask(eltgl, {
        mask: Date,
        pattern: momentFormat,
        lazy: false,
        min: new Date(1970, 0, 1),
        max: new Date(2030, 0, 1),

        format: function (date) {
            return moment(date).format(momentFormat);
        },
        parse: function (str) {
            return moment(str, momentFormat);
        },

        blocks: {
            YYYY: {
            mask: IMask.MaskedRange,
            from: 1970,
            to: 2030
            },
            MM: {
            mask: IMask.MaskedRange,
            from: 1,
            to: 12
            },
            DD: {
            mask: IMask.MaskedRange,
            from: 1,
            to: 31
            },
            HH: {mask: IMask.MaskedRange,from: 0,to: 23},
            mm: {mask: IMask.MaskedRange,from: 0,to: 59 }
        }
        });

    // END Tanggal

    document.addEventListener('keydown', function(e){
		var key = util.key[e.which];
		if( key ){
			e.preventDefault();
		}

		if( key === 'F12' ){
            if(validateForm())
            {
                Simpan();
            }
		}
	});

    $(document).on('click','.btn-delete',function(){
            var kdAkun = $(this).closest('tr').find('td').eq(0).html();
            checkdelete(kdAkun.trim(),$(this));
    });

    $('#createNew').click(function () {
        // mode = 'TAMBAH';
        // $('#add-modal').show();
        // $('#tx-no').focus();
        window.open('http://localhost:1000/donasi','_blank');
    });

    $('#tampil').click(function () {
        var tglDr = $('#tx-tgl-dr').val();
        var tglSd = $('#tx-tgl-sd').val();
        var noBukti = $('#search-no-bukti').val();
        var sort = $('#sortAktif').data('sort');
        var sortField = $('#sortAktif').data('sort-field');
        loadData(1,tglDr, tglSd,noBukti,sort,sortField);
    });

    $('#batal').click(function(){
        ResetForm();
    });


    $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kd = $(this).closest('tr').find('td').eq(0).html();
            $('#tx-no').val(kd.trim());
            $('#tx-no').prop('readonly', true);
            $.ajax({
                url:"",
                method:"POST",
                data:{kd:kd.trim()},
                success:function(data){
                    console.log(data);
                    var obj = JSON.parse(data);

                    $('#tx-no').val(obj[0].kd_tkm);
                    $('#tx-tgl').val((obj[0].tgl).substr(0,10));
                    $('#tx-dr').val(obj[0].dr);
                    $("#tx-desk").val([obj[0].deskripsi]);

                    var dtl = obj.items;

                    var $tr    = $('#tbl-transaksi tbody .tr_clone');
                    var clone;
                    for (i=1;i<dtl.length;i++)
                    {
                        $clone = $tr.clone();
                        $clone.find(':text').val('');
                        $clone.find('.angka').val('0');
                        $clone.find('td').eq(idxKolomProgram).html(entriKdProgram);
                        $clone.find('td').eq(idxKolomProject).html(entriKdProject);

                        $tr.after($clone);
                    }
                    $(".entri-kd-program").autocomplete({source: [dataKdProgram],autoselect: true});
                    $(".entri-kd-project").autocomplete({source: [dataProject],autoselect: true});

                    var i = 0;
                    $('#tbl-transaksi tbody tr').each(function(index, tr) {

                        var akun = dataAkun.filter(item => item.toLowerCase().indexOf(dtl[i].kd_akun) > -1);
                        var prj = dataProject.filter(item =>
                        {
                            let arr =item.split('[');
                            let kd = arr[0];
                            return (AdnToString(kd)===AdnToString(dtl[i].kd_project)? true: false);
                        });

                        $(this).find('td').eq(idxKolomProgram).find('input').first().val(dtl[i].kd_program);
                        $(this).find('td').eq(idxKolomProject).find('input').first().val(prj);
                        $(this).find('td').eq(idxKolomMemo).find('input').first().val(AdnToString(dtl[i].memo));
                        $(this).find('td').eq(idxKolomDebet).find('input').first().val(AdnFormatNum(dtl[i].debet));
                        $(this).find('td').eq(idxKolomKredit).find('input').first().val(AdnFormatNum(dtl[i].kredit));
                        i++;
                    });

                    UpdateTotalDebet(idxKolomDebet);UpdateTotalKredit(idxKolomKredit);
                    setTabIndex();

                }
            })

            $('#ajax-loading').show();
            $('#tx-tgl').focus();
            $('#ajax-loading').hide();}, 1500);
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


	var options = {
    	source: [dataAkun],
    	minLength: 2,
        autoselect: true
	};

    $(".entri-kd-program").autocomplete({source: [dataKdProgram],autoselect: true});
    $(".entri-kd-project").autocomplete({source: [dataProject],autoselect: true});

    $(document).on("keydown","#tbl-transaksi .entri",function(e){

        switch(e.key) {

            case "ArrowLeft": // left
                e.preventDefault();
				rix = $(this).closest('tr').index();
				tix = $(this).parent().index();
				$(this).closest('tr').find('td').eq(tix-1).find('input').first().focus();
            break;

            case "ArrowUp":
				e.preventDefault();
				rix = $(this).closest('tr').index();
				tix = $(this).parent().index();
				$(this).closest('tr').prev().find('td').eq(tix).find('input').first().focus();
            	break;

            case "ArrowRight":
                e.preventDefault();
				rix = $(this).closest('tr').index();
				tix = $(this).parent().index();
				$(this).closest('tr').find('td').eq(tix+1).find('input').first().focus();
            break;

            case "ArrowDown":
                e.preventDefault();
				rix = $(this).closest('tr').index();
				tix = $(this).parent().index();
				$(this).closest('tr').next().find('td').eq(tix).find('input').first().focus();
            break;

            case "Enter":
				e.preventDefault();
                if($(this).closest("tr").is(":last-child"))
                {
					var lastTabIndex = $('#tbl-transaksi > tbody  > tr:last').find('td').eq(idxKolomKredit).find('input').attr('tabindex');
                    //Duplikat baris terakhir
                    var $tr    = $(this).closest('.tr_clone');
                    var $clone = $tr.clone();

                    //set tabindex satu baris tr.clone
                    for(let i=0;i<=idxKolomKredit;i++){
                        $clone.find('td').eq(i).find('input').attr('tabindex',lastTabIndex+1+i);
                    }
                    //---- //END set tabindex
                    $clone.find(':text').val('');
                    $clone.find('.angka').val('0');

				    $clone.find('td').eq(idxKolomProgram).html(entriKdProgram);
                    $clone.find('td').eq(idxKolomProject).html(entriKdProject);
                    $tr.after($clone);

                    $(".entri-kd-program").autocomplete({source: [dataKdProgram],autoselect: true});
                    $(".entri-kd-project").autocomplete({source: [dataProject],autoselect: true});
					AktivasiTab();
					$("input:text").focus(function() { $(this).select(); } );//fokus dan block
                }
				tix = $(this).parent().index();

				if(tix==idxKolomKredit)
				{$(this).closest('tr').next().find('td').eq(0).find('input').first().focus();}
				else
				{
					$(this).closest('tr').find('td').eq(tix+1).find('input').first().focus();
				}
           		break;
            default: return; // exit this handler for other keys
        }

        e.preventDefault(); // prevent the default action (scroll / move caret)
    });

    //#region Periksa Kd Akun Asli
	$(document).on('change','.entri',function(e){
		var tix = $(this).parent().index();
		UpdateTotalDebet(tix);
		UpdateTotalKredit(tix);

        var el = $(this);
		var str = $(this).attr('class');
		var KdAkun='';
		if(str.indexOf('entri-kd-akun')!== -1){
			var arr = $(this).val().split('[');
				if(arr.length>0)
					KdAkun =arr[0].trim();

			if(!dataKdAkun.includes(KdAkun)){
				$(this).val('');
			}
		}
        var KdProject ='';
        if(str.indexOf('entri-kd-project')!== -1){
			var arr = $(this).val().split('[');
				if(arr.length>0)
                    KdProject =arr[0].trim();

			if(!dataKdProject.includes(KdProject)){
			    el.val('');
			}
		}

        var KdProgram ='';
        if(str.indexOf('entri-kd-program')!== -1){
			if(!dataKdProgram.includes(el.val())){
			    el.val('');
			}
		}

        if(str.indexOf('angka')!== -1){
			el.val(AdnFormatNum(el.val()));
        };
    });

    $(document).on("keydown","#tbl-transaksi .entri",function(e){
        switch(e.key) {
            case "Enter":
                e.preventDefault();
                $(this).parents('td').next('td').find('input').first().focus();
                break;
        }
    });

//#endregion Periksa Kd Akun Asli --------
	$('#save').on('click',function(e){
        if(validateForm())
        {
            Simpan();
        }
    });


    });


 function loadData(page,tglDr,tglSd,noBukti,sort,sortField){
    $.ajax({
        url:"",
        method:"POST",
        data:{page:page,tglDr:tglDr,tglSd:tglSd,noBukti:noBukti,sort:sort,sortField:sortField},
        success:function(data){
                $('#tbl').html(data);
        }
    })
}

function Simpan() {
	var kirim = true;
	const data = new FormData(document.querySelector("#trn"));// new FormData(e.target);
	const tkm = Object.fromEntries(data.entries());// Objek Kas Masuk

	const dtl = [];

    var isLastRow = false;
    var isKdAkunKosong = false;
	$('#tbl-transaksi tr').each(function(index, tr) {
		var obj = {};
		var kol=0;
        if($(this).closest("tr").is(":last-child"))
        {
            isLastRow = true;
            console.log('baris-terakhir ')
        }

		$('td input.entri', tr).each(function() {
			if ($(this).val()!= undefined)
			{
				if($(this).data('field')=='kd_akun')
				{
					var arr = $(this).val().split('[');
					if(arr.length>0)
						obj[$(this).data('field')] =arr[0].trim();

					if(obj['kd_akun']=="")
					{
                        isKdAkunKosong = true;
                        if (isLastRow!=true)
                        {
                            alert('Kode Akun Tidak Boleh Kosong.');
                            //$.AdnPesanPerhatian('Kode Akun Tidak Boleh Kosong.');
                            kirim=false;
                            return false;
                        }
					}
				}
                else if($(this).data('field')=='kd_project')
                {
                    let arr = $(this).val().split('[');
					if(arr.length>0)
						obj[$(this).data('field')] =arr[0].trim();
                }
                else if($(this).data('field')=='debet' || $(this).data('field')=='kredit')
                {
                    obj[$(this).data('field')] = AdnToNum($(this).val());
                }
				else
				{
					obj[$(this).data('field')] = $(this).val();
				}
			}
		});//$(each td)

        if(!AdnIsEmpty(obj))//Cek Apakah Objek Empty(kosong)
        {
            if(isLastRow)
            {
                if(AdnToNum(obj['debet'])==0 && AdnToNum(obj['kredit'])==0 && isKdAkunKosong){
                    // Baris diabaikan
                }
                else
                {
                    if(AdnToNum(obj['debet'])==0 && AdnToNum(obj['kredit'])==0){
                        alert('Terdapat Baris dengan Transaksi 0 (Nol)');
                        kirim =false;
                        return false;
                    }
                }
            }
            else
            {
                if(AdnToNum(obj['debet'])==0 && AdnToNum(obj['kredit'])==0){
                    alert('Terdapat Baris dengan Transaksi 0 (Nol)');
                    kirim =false;
                    return false;
                }
            }
        }

        if(!AdnIsEmpty(obj)){//Cek Apakah Objek Empty(kosong)
            if(isKdAkunKosong!=true)
            {
                dtl.push(obj);
            }
        }
	});//$('#tbl-transaksi tr')
	console.log('dtl : ' + dtl );
    tkm.items= dtl;

	if(dtl.length<2)
	{
		alert('Baris Transaksi minimal 2 Baris');
		kirim =false;
		return false;
	}
    tkm.mode=mode;
    console.log(tkm);
		if(kirim)
			{
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "",//urlAksi,
					type: "POST",
					//headers: getAdnToken(),
					data: tkm,
					success: function (respon) {
						console.log(respon);
						if (respon.IsSuccess) {
							alert('sukses');
							//ResetForm();
                            $('#tx-no').prop('readonly', false);
							$('#tx-no').focus();
							//var alertNode = document.querySelector('.alert')
							//var alert = bootstrap.Alert.getInstance(alertNode)
							//alert.close()
							//$.AdnPesanSukses(respon.Message);
							//window.location = baseUrl + ("/tkk");
						}
						else {
							//$.AdnPesanPerhatian("Terjadi Kesalahan: " + respon.Message);
						}
					}
				});//$.ajax({
			}
	// });
}

function validateForm() {
	var errors = [];
	var form = document.getElementsByTagName('form')[0];

	var elNo = document.getElementById("tx-no");
	var elTgl = document.getElementById("tx-tgl");

	if (elNo.value === "") {
		errors.push({
		elem: elNo,
		message: "Nomor Tidak Boleh Kosong."
		});
	}

    if (elTgl.value === "_--_") {
		errors.push({
		elem: elTgl,
		message: "Tanggal Tidak Boleh Kosong."
		});
	}

    d = new Date(elTgl.value);
    if (Object.prototype.toString.call(d) === "[object Date]") {
            // it is a date
            // if (isNaN(d.getTime())) {  // d.valueOf() could also work
            //     // date is not valid
            // } else {
            //     // date is valid
            // }
        }
        else {
            errors.push({
            elem: elTgl,
            message: "Tanggal Tidak Valid."
            });
        }


	if(AdnToNum($('#td-total-debet').html()) != AdnToNum($('#td-total-kredit').html()))
	{
		errors.push({
			elem: {},
			message:'Debet dan Kredit Tidak Seimbang.'
		});
	}

    if((AdnToNum($('#td-total-debet').html()) + AdnToNum($('#td-total-kredit').html()))==0)
	{
		errors.push({
			elem: {},
			message:'Total Transaksi Tidak Boleh Nol (0).'
		});
	}

    var rowCount = $('#tbl-transaksi tbody tr').length;
    if(rowCount == 1) {
        errors.push({
			elem: {},
			message:'Transaksi minimal 2 (dua) baris.'
		});
    }

    if (rowCount == 2) {
        var lastTr = $('#tbl-transaksi tbody tr:last-child');

        console.log(lastTr.eq(idxKolomAkun).find('input').first().val());
        const nl = lastTr.eq(idxKolomAkun).find('input').first().val();
        if(nl.trim()==="") {
            errors.push({
            elem: {},
            message:'Kode Akun Tidak Boleh Kosong.'});
        }


        const debet = lastTr.find('td').eq(idxKolomDebet).find('input').val();
        const kredit =lastTr.find('td').eq(idxKolomKredit).find('input').val();
        if(AdnToNum(debet)==0 && AdnToNum(kredit)==0){
            errors.push({
            elem: {},
            message:'Terdapat Baris dengan Transaksi 0 (Nol).'});
        }
    }
    var str ='';
    errors.forEach(item => {
        str += item.message + '\n'
    });

    if (str!='')alert(str);

	return errors.length === 0;
}

function UpdateTotalDebet(idxKolom){
		if(idxKolom == idxKolomDebet)
		{
			totalDebet=0;
			$('#tbl-transaksi tr').each(function(index, tr)
			{
				var el = $(this).find('td').eq(idxKolomDebet).find('input').first()
				totalDebet=totalDebet+ AdnToNum(el.val());
			});
			$('#td-total-debet').html(AdnFormatNum(totalDebet));
		}
}

function UpdateTotalKredit(idxKolom){
	if(idxKolom == idxKolomKredit)
	{
		totalKredit=0;
		$('#tbl-transaksi tr').each(function(index, tr)
		{
			var el = $(this).find('td').eq(idxKolomKredit).find('input').first()
			totalKredit=totalKredit+ AdnToNum(el.val());
		});
		$('#td-total-kredit').html(AdnFormatNum(totalKredit));
	}
}

function AktivasiTab()
{
		var inputs = $('input, textarea, select').not('.xdsoft_autocomplete_hint'), inputTo;
		inputs.on('keydown', function(e) {

			// if we pressed the tab
			if (e.keyCode == 9 || e.which == 9) {
				// prevent default tab action
				e.preventDefault();

				if (e.shiftKey) {
					// get previous input based on the current input
					inputTo = inputs.get(inputs.index(this) - 1);
				} else {
					// get next input based on the current input
					inputTo = inputs.get(inputs.index(this) + 1);
				}

				// move focus to inputTo, otherwise focus first input
				if (inputTo) {
					inputTo.focus();
				} else {
					inputs[0].focus();
				}
			}
		});
}

function ResetForm() {
    $('#tx-no').prop('readonly', false);
	clearForm(document.querySelector("#trn"));
	$('#tbl-transaksi tbody tr').not($('#tbl-transaksi tbody tr:first')).remove();
	$('#tbl-transaksi .entri').val('');
	$('#td-total-debet').html('0');
	$('#td-total-kredit').html('0');
	$('#tx-no').focus();

    mode = 'TAMBAH';
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
                    url:"",
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

function setTabIndex(){
    const tbl = $('#tbl-transaksi');
    var idx = 100;
    $('#tbl-transaksi > tbody  > tr').each(function(index, tr) {
        var row = $(this);
        for(i=0;i<=idxKolomKredit;i++) {
            td = row.find('td').eq(i);
            el = td.find('input');
            el.attr("tabindex", idx);
            idx = idx+1;
        }

    });
}

function getLastTabIndex(){
    var idx = $('#tbl-transaksi > tbody  > tr:last').find(td).eq(idxKolomKredit).find('input');
    console.log(idx)
}



</script>
@endsection
