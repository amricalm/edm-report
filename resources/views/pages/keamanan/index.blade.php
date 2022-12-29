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
                                    <div class="col-md-4">

                                        <select name="status" id="pilih-status" class="form-select form-control  form-control-sm  mb-2" tabindex="0">
                                            @foreach($status as $key => $value)
                                            <option value="{{ $value }}">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="tampil" class="btn btn-sm btn-primary" tabindex="3"><i class="fe fe-search"></i>Tampil</button>
                                    </div>
                                </div>

                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-2 form-label">Nama Group</label>
                                    <div class="col-md-4">
                                        <select name="kd_group" id="cr-group" class="form-select form-control  form-control-sm  mb-2" tabindex="1">
                                            <option value="">--- Pilih Salah Satu Group ---</option>
                                            @foreach($group as $item)
                                                <option value="{{$item->kd_group}}">{{$item->nm_group}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row row-sm mb-0">
                                    <label class="col-md-2 col-form-label-sm">Cari Nama Login</label>
                                    <div class="col-md-4">
                                        <input type="text"  id="tx-search"  placeholder="" autocomplete="off" name="" class="form-control  form-control-sm  mb-2" tabindex="2">
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
          <h4 class="page-title text-primary">Pengguna</h4>
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
                                                            <label class="col-md-3 form-label">Group</label>
                                                            <div class="col-md-9">
                                                                <select name="kd_group" id="kd-group" class="form-select form-control  form-control-sm  mb-2" tabindex="10">
                                                                    <option value="">-- Pilih Group --</option>
                                                                    @foreach($group as $item)
                                                                    <option value="{{trim($item->kd_group)}}">{{$item->nm_group}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row row-sm mb-0">
                                                            <label class="col-md-3 form-label">Nama Login</label>
                                                            <div class="col-md-9">
                                                                <input type="text" id="tx-kd" autocomplete="off" name="kd" class="form-control  form-control-sm  mb-2" tabindex="12">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row row-sm mb-0">
                                                            <label class="col-md-3 form-label">
                                                            </label>
                                                            <div class="col-md-9 col-auto">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="chk-aktif" name="aktif" tabindex="19">
                                                                    <span class="custom-control-label">Pengguna Dinonaktifkan</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row row-sm mb-0">
                                                            <label class="col-md-3 form-label">Password</label>
                                                            <div class="col-md-9">
                                                                <input type="password" id="tx-password" name="password" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row row-sm mb-0">
                                                            <label class="col-md-3 form-label">Konfirmasi Password</label>
                                                            <div class="col-md-9">
                                                                <input type="password" id="tx-konfirmasi-password" name="konfirmasi-password" autocomplete="off" class="form-control  form-control-sm  mb-2" tabindex="13">
                                                            </div>
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

        loadData(1,$('#tx-search').val(),$('#cr-group').val(),'asc','nm_login',$('#pilih-status').val());

        $('#tx-kd').on('change', function(){
            var el = $(this);
            $.ajax({
                url:"{{ route('scpengguna.isExist') }}",
                method:"POST",
                data:{kd:el.val()},
                success:function(data){
                    if(data==='true'){
                        alert('Nama Login telah ada.');
                        el.val('');
                        el.focus();
                    }
                }
            });
        });

        $(document).on('click', '.halaman', function(){
           var page = $(this).attr("id");
           var status = $('#pilih-status').val();
           var group = $('#cr-group').val();
           var txSearch = $('#tx-search').val();
           var sort = $('#sortAktif').data('sort');
           var sortField = $('#sortAktif').data('sort-field');
           loadData(page,txSearch,group,sort,sortField,status);
        });

        $('#tampil').click(function () {
            var status = $('#pilih-status').val();
            var txCari = $('#tx-search').val();
            var group = $('#cr-group').val();
            var sort = $('#sortAktif').data('sort');
            var sortField = $('#sortAktif').data('sort-field');
            loadData(1,txCari,group,sort,sortField, status);

        });

        $('#createNew').click(function(){
            mode = 'TAMBAH';
            $('#add-modal').show();
        });

        $(document).on('click','.btn-edit',function(){
            mode = 'EDIT';
            var kd = $(this).closest('tr').find('td').eq(0).html();
            $('#tx-kd').val(kd.trim());
            $('#tx-kd').prop('readonly', true);
            $.ajax({
                url:"{{ route('scpengguna.get') }}",
                method:"POST",
                data:{kd:kd.trim()},
                success:function(data){

                        var obj = data[0];

                        $('#tx-kd').val(obj.nm_login);
                        $('#tx-password').val(obj.pwd);
                        $('#tx-konfirmasi-password').val(obj.pwd);
                        $('#kd-group').val(obj.kd_group.trim());
                        $("#chk-aktif").prop('checked', !(Boolean(Number(obj.aktif))));
                }
            })
            $('#ajax-loading').show();
            setTimeout(() => {$('#add-modal').show();$('#ajax-loading').hide();}, 1500);

        });

        $(document).on('click','.btn-delete',function(){
            var kd = $(this).closest('tr').find('td').eq(0).html();
            checkdelete(kd.trim(),$(this));
        });

        $('#add-modal').on('shown.bs.modal', function (e) {
            //AktivasiTab();
            $('#tx-kd').focus();
        });

        $('#btn-close').click(function () {
            $('#add-modal').hide();
        });

        $('#batal').click(function () {
            mode = 'TAMBAH';
            $('#tx-kd').prop('readonly', false);
            var frm = document.querySelector("#trn")
            frm.reset();
        });

        $('#save').click(function(e) {
            if(validateForm())
            {
                e.preventDefault();
                var el = $(this);
                el.html('...');

                var kirim = true;
                const frm = new FormData(document.querySelector("#trn"));
                const obj = Object.fromEntries(frm.entries());

                obj.mode = mode;

                $.ajax({
                    data: obj,
                    url:  "{{ route('scpengguna.save') }}",
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
            var elNm = document.getElementById("kd-group");

            if (elKd.value.trim() === "") {
                errors.push({
                elem: elKd,
                message: "Kode Tidak Boleh Kosong."
                });
            }

            if (elNm.value.trim() === "") {
                errors.push({
                elem: elNm,
                message: "Group Tidak Boleh Kosong."
                });
            }

            var str ='';
            errors.forEach(item => {
                str += item.message + '\n'
            });

            if (str!='')alert(str);

            return errors.length === 0;
        }

        $(document).on('click','.sort', function(){
            var status = $('#pilih-status').val();
            var txSearch = $('#tx-search').val();
            var group = $('#cr-group').val();
            var sort = $(this).data('sort');
            sort = (sort==='asc')?'desc':'asc';
            var sortField = $(this).data('sort-field');
            loadData(1,txSearch,group,sort,sortField,status);
        });

    });


function loadData(page,txSearch,group,sort,sortField,status){
    $.ajax({
        url:"{{ route('scpengguna.getTabel') }}",
        method:"POST",
        data:{page:page, txSearch:txSearch, group:group, sort:sort,sortField:sortField, status:status},
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
                url:"{{route('scpengguna.delete') }}",
                method:"POST",
                data:{kd:id},
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
