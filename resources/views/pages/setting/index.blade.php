@extends('templates.index')
{{-- @include('templates.komponen.sweetalert') --}}
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
                            <a href="javascript:void(0)" id="save" class="btn btn-outline-primary"><i class="fe fe-save"></i> Simpan</a>
                        </div>
                    </div>
                </div>
                <!--End Page header-->
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">

								<div class="row">
									<div class="col-md-12 col-lg-12">
										<div class="card">
											<div class="card-body">
												{{-- <div class="form-group row row-sm mb-0">
													<label class="col-md-3 form-label">Periode Mulai</label>
													<div class="col-md-3">
														<input type="text" id="tx-tgl" value="{{$periodeMulai}}" placeholder="yyyy-mm-dd" name="tgl" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="1">
													</div>
												</div> --}}
												<div class="form-group row row-sm mb-0">
													{{-- <label class="col-md-2 form-label">Kelompok</label> --}}
													<div class="col-md-5">
														{{-- <select name="kd_gol" id="gol-akun" class="form-select form-control  form-control-sm  mb-2" tabindex="10">
															<option value="">-- Pilih Kelompok Akun --</option>
															@foreach($golAkun as $item){
															<option value="{{$item->kd_gol}}">{{$item->nm_gol}}</option>
															}
															@endforeach
														</select> --}}
		
													</div>
												
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 col-lg-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group row row-sm mb-0">
													<label class="col-md-3 form-label">Tampil Baris dalam Tabel</label>
													<div class="col-md-3">
														<input type="text" id="tx-jmh-baris" value="{{$tampilBaris}}" placeholder="" name="jmh-baris-tabel" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="2">
													</div>
												</div>

												</div>
											</div>
										</div>
									</div>
								</div>

                            </div> <!-- card-body -->
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
//#region Deklarasi

	var mode = '';
	var util = {};
	util.key = { 
	//   9: "tab",   13: "enter",   16: "shift",   18: "alt", 27: "esc",  33: "rePag",   34: "avPag",   35: "end",
	//   36: "home", 37: "left",    38: "up",      39: "right",  40: "down",
	112: "F1",  113: "F2",  114: "F3",  115: "F4",
	116: "F5",  117: "F6",  118: "F7",  119: "F8", 120: "F9",  121: "F10",  122: "F11",  123: "F12"
	}
//#endregion Deklarasi

$(function() {  
	mode = 'TAMBAH';
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

	// Tanggal 
	$('#tx-tgl').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});


    // END Tanggal 

	//loadData($('#gol-akun').val(),$('#tx-tgl').val());
	
    document.addEventListener('keydown', function(e){
		var key = util.key[e.which];
		if( key ){
			e.preventDefault();
		}

		if( key === 'F12' ){        
			Simpan();
		}
		if(key==='F10'){ Load();}
	});

//#endregion Periksa Kd Akun Asli --------
	$('#save').on('click',Simpan);

});


 function loadData(kd,tgl){
    // $.ajax({
    //     method:"POST",
    //     data:{kdGol:kd, tgl:tgl},
    //     success:function(data){
	// 		$('#tbl-transaksi tbody').html(data);
    //     }
    // }).done(function(msg){
	// 	$("input:text").focus(function() { $(this).select(); } ); // saat fokus, langsung pilih(block)
	// 	$("#tbl-transaksi tbody tr").find('td').eq(idxKolomDebet).find('input').focus();
	// 	UpdateTotalDebet(idxKolomDebet);
	// 	UpdateTotalKredit(idxKolomKredit);
	// });//ajax
}

function Simpan() {
	var kirim = true;
	var obj = {};

	obj.periodeMulai = $('#tx-tgl').val();
	obj.tampilBaris = $('#tx-jmh-baris').val();

	if(kirim)
		{
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
				url: "{{ route('setting.store') }}",
				type: "POST",
				data: obj,
				success: function (respon) {
					if (respon.IsSuccess) {
						alert('sukses');
					}
					else {
						//$.AdnPesanPerhatian("Terjadi Kesalahan: " + respon.Message);
					}
				}
			});//$.ajax({
		}//kirim
}




</script>
@endsection