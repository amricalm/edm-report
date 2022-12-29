@extends('templates.minimalist.index')
@include('templates.komponen.sweetalert')
@section('body')
<!-- Page -->
<div class="page">
    <div class="page-main">
        <div class="page-header">
            <h4 class="col-md-3 page-title text-primary">Donasi</h4>
            <div class="float-right col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger position-relative btn-batal" id="Batal"><i class="fe fe-slash"></i>
                    Batal</button>
                <button type="button" id='btn-simpan' class="btn btn-blue position-relative btn-simpan" data-adnmode="ADD"><i class="fe fe-save"></i>
                    Simpan</button>
            </div>
        </div>
        <!-- Row -->
        <div class="row px-6">
            <div class="col-md-12">
                <div class="card bg-light mb-0">
                    {{-- <div class="card-body"> --}}
                        <div class="row row-sm">
                            <div class="card mb-2">
                                <form id="trn" class="form-horizontal">
                                    <div class="card border mb-2 py-2 mt-3">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group row row-sm mb-0">
                                                {{-- <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Status</label>
                                                        <div class="col-md-9">
                                                            <select name="AlurKerja" id="AlurKerja" class="form-select form-control form-control-sm huruf-kecil" tabindex="1">
                                                                @foreach($alurDonasi as $key => $value){
                                                                    <option value="{{$value}}" {{ $value == $donasi->alur_kerja ? 'selected' : '' }}>{{$key}}</option>
                                                                }
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Kantor</label>
                                                        <div class="col-md-9">
                                                            <select name="KdCabang" id="KdCabang" class="form-select form-control  form-control-sm  huruf-kecil" tabindex="1">
                                                                @if(count($cabang)==1)
                                                                @foreach($cabang as $item){
                                                                    <option value="{{$item->kd_cabang}}">{{$item->nm_cabang}}</option>
                                                                }
                                                                @endforeach
                                                                @else
                                                                    <option value="">--- Pilih Kantor/Cabang ---</option>
                                                                    @foreach($cabang as $item){
                                                                        <option value="{{$item->kd_cabang}}"  {{ $item->kd_cabang == $donasi->kd_cabang ? 'selected' : '' }}>{{$item->nm_cabang}}</option>
                                                                    }
                                                                    @endforeach
                                                                @endif

                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-info m-0">
                                    <div class="card border mb-2 pt-2">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group row row-sm mb-1">
                                                <div class="col-lg-4 col-md-12">
                                                    <input type="hidden" id="tr-id" value="0" />
                                                    <input type="hidden" id="link-idbukubank" value="{{$linkId}}"  />
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold px-1">Pembayaran</label>
                                                        <div class="col-md-9">
                                                            <select name="KdKas" id="KdKas" class="form-select form-control form-control-sm mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="2">
                                                                @foreach($kas as $item){
                                                                    <option value="{{$item->kd_kas}}" {{ $item->kd_kas == $donasi->kd_kas ? 'selected' : '' }}>{{$item->nm_kas}}</option>
                                                                }
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold px-1">Kwitansi</label>
                                                        <div class="col-md-6">
                                                            <input type="text" value="{{$donasi->no_kwitansi}}" name="NoKwitansi" id="NoKwitansi" class="form-control  form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" autocomplete="off" tabindex="4">
                                                        </div>
                                                        @php $nonAktif='';@endphp
                                                        @if($ModeEdit == 'EDIT')
                                                            @php
                                                                $nonAktif = 'disabled="disabled"';
                                                            @endphp
                                                        @endif
                                                        <label class="col-md-2 fs-11 fw-bold px-1 custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" {{$nonAktif}} id="chk-auto" name="auto" tabindex="5">
                                                            <span class="custom-control-label"></span><span class="mx-4">Auto</span>
                                                        </label>
                                                       
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Tgl. Transaksi</label>
                                                        <div class="col-md-4">
                                                            <input type="text" value="{{$donasi->tgl_transaksi}}" name="Tgl" id="tgl" class="form-control form-control-sm mb-1   huruf-kecil" data-val-date="The field Tgl. Transaksi must be a date." data-val-required="Harus Diisi" autocomplete="off" tabindex="10">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Tgl. Setor</label>
                                                        <div class="col-md-4">
                                                            <input type="text" value="{{$donasi->tgl}}" id="tgl-setor" name="TglSetor" class="form-control form-control-sm mb-1   huruf-kecil" data-val-date="The field Tgl. Setor must be a date." data-val-required="Harus Diisi" autocomplete="off" tabindex="10">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Jaringan</label>
                                                        <div class="col-md-9">
                                                            @if($ModeEdit=='TAMBAH')                                                              
                                                                @if($linkId!='')
                                                                    <select name="KdAgen" id="KdAgen" class="form-select form-control form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="12">
                                                                        <option value="">--- Pilih Jaringan ---</option>
                                                                        @foreach($agen as $item){
                                                                            <option value="{{$item->kd_agen}}" {{ $item->kd_agen == $donasi->kd_agen ? 'selected' : '' }}>{{$item->nm_agen}}</option>
                                                                        }
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <select name="KdAgen" id="KdAgen" class="form-select form-control form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="12">
                                                                        <option value="">--- Pilih Jaringan ---</option>
                                                                    </select>
                                                                @endif
                                                            @else
                                                                <select name="KdAgen" id="KdAgen" class="form-select form-control form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="12">
                                                                    <option value="">--- Pilih Jaringan ---</option>
                                                                    @foreach($agen as $item){
                                                                        <option value="{{$item->kd_agen}}" {{ $item->kd_agen == $donasi->kd_agen ? 'selected' : '' }}>{{$item->nm_agen}}</option>
                                                                    }
                                                                    @endforeach
                                                                   
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Fundraiser</label>
                                                        <div class="col-md-9">
                                                                @if($ModeEdit=='TAMBAH')
                                                                    @if($linkId!='')
                                                                        <select name="KdSales" id="KdSales" class="form-select form-control form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="14">
                                                                            <option value="">--- Pilih Fundraiser ---</option>
                                                                            @foreach($sales as $item){
                                                                                <option value="{{$item->kd_sales}}" {{ trim($item->kd_sales) == trim($donasi->kd_sales) ? 'selected' : '' }}>{{$item->nm_sales}}</option>
                                                                            }
                                                                            @endforeach
                                                                        
                                                                        </select>
                                                                    @else
                                                                        <select name="KdSales" id="KdSales" class="form-select form-control form-control-sm mb-1  huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="14">
                                                                            <option value="">--- Pilih Fundraiser ---</option>
                                                                        </select>                                               
                                                                    @endif
                                                                @else
                                                                    <select name="KdSales" id="KdSales" class="form-select form-control form-control-sm  mb-1   huruf-kecil" data-val="true" data-val-required="Harus Diisi" tabindex="14">
                                                                        <option value="">--- Pilih Fundraiser ---</option>
                                                                        @foreach($sales as $item){
                                                                            <option value="{{$item->kd_sales}}" {{ trim($item->kd_sales) == trim($donasi->kd_sales) ? 'selected' : '' }}>{{$item->nm_sales}}</option>
                                                                        }
                                                                        @endforeach
                                                                    
                                                                    </select>
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-info m-0">
                                    <div class="card border mb-1 py-2">
                                        <div class="form-group row row-sm mb-0">
                                            <div class="col-lg-4">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right fw-bold">Cari HP</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="cr-hp" id="cr-hp" class="form-control  form-control-sm  huruf-kecil" autocomplete="off" tabindex="18">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group row row-sm mb-0 align-items-center">
                                                    <label class="col-md-3 fs-11 text-right fw-bold">Cari Email</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="cr-email" id="cr-email" class="form-control  form-control-sm  huruf-kecil" autocomplete="off" tabindex="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">

                                            </div>
                                            
                                        
                                    
                                            
                                                
                                    
                                        </div>
                                    </div>

                                    <div class="row pt-2 pb-1">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group row row-sm mb-1">
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Nm Pdftr</label>
                                                        <div class="col-md-7">
                                                            <input type="hidden" id="pelanggan-id" value="0" />
                                                            <input type="hidden" id="kd-pelanggan" value="{{$donasi->donatur->kd_pelanggan}}" />
                                                            <input type="text"  value="{{$donasi->donatur->nm_lengkap}}" name="NmDonatur" id="NmDonatur" class="form-control  form-control-sm  mb-1  huruf-kecil" data-val="true" data-val-required="Harus Diisi" autocomplete="off" tabindex="22">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0)" id="pelanggan-tambah" class="btn bg-dark-transparent" tabindex="40">
                                                                <i class="ion ion-plus-round"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Nm Wakif</label>
                                                        <div class="col-md-7">
                                                            <input type="text"  value="{{$donasi->nm_wakif}}" name="NmWakif" id="NmWakif" class="form-control  form-control-sm  mb-1  huruf-kecil" data-val="true" data-val-required="Harus Diisi" autocomplete="off" tabindex="24">
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Alamat</label>
                                                        <div class="col-md-7">
                                                            <textarea name="Alamat" id="Alamat" rows="2" class="form-control  huruf-kecil" autocomplete="off" tabindex="25">{{$donasi->donatur->alamat}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Kota-Kd Pos</label>
                                                        <div class="col-md-6">
                                                            <input type="text"  value="{{$donasi->donatur->kota}}" name="Kota" id="Kota" class="form-control form-control-sm mb-1  huruf-kecil" autocomplete="off" tabindex="27">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" value="{{$donasi->donatur->pos}}" name="Pos" id="Pos" maxlength="5"  class="form-control form-control-sm mb-1  huruf-kecil angka" autocomplete="off" tabindex="28">
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Telp</label>
                                                        <div class="col-md-9">
                                                            <input type="text"   value="{{$donasi->donatur->telp}}" name="Telp" id="Telp" class="form-control form-control-sm mb-1  huruf-kecil" autocomplete="off" tabindex="30">
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Keterangan Verifikasi</label>
                                                        <div class="col-md-9">
                                                            <textarea name="Keterangan" id="Keterangan" rows="2" class="form-control  huruf-kecil" autocomplete="off" disabled>{{$donasi->bb_deskripsi}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Propinsi</label>
                                                        <div class="col-md-9">
                                                            <select name="Propinsi" id="Propinsi" class="form-select form-control  form-control-sm  mb-1  huruf-kecil" tabindex="32">
                                                                <option value="0">---</option>
                                                                @foreach($propinsi as $rows)
                                                                    <option value="{{$rows['kd_propinsi']}}"  {{ $rows['kd_propinsi'] == $donasi->donatur->propinsi ? 'selected' : '' }}>{{$rows['nm_propinsi']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">HP</label>
                                                        <div class="col-md-9">
                                                            <input type="text" value="{{$donasi->donatur->hp}}"  name="Hp" id="Hp" class="form-control  form-control-sm  mb-1  huruf-kecil" autocomplete="off" tabindex="32">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row row-sm mb-0 align-items-center">
                                                        <label class="col-md-3 fs-11 text-right fw-bold">Email</label>
                                                        <div class="col-md-9">
                                                            <input type="text"  value="{{$donasi->donatur->email}}"  name="Email" id="Email" class="form-control  form-control-sm  mb-1  huruf-kecil" autocomplete="off" tabindex="33">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- TABEL TRANSAKSI -->
                        <hr class="border-info m-0 pb-0">
                        <div class="row pt-0 pb-0">
                            <div class="col-md-12">
                                {{-- <table class="table-sm table-condensed table-bordered small fw-bold" id="tbl-transaksi">
                                    <tbody>
                                                                               


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="align-right" style="text-align:right;font-weight:bold">TOTAL TRANSFER</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="total-transfer">0</td>
                                            <td class="align-right" style="text-align:right;font-weight:bold" colspan="2">TOTAL</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="td-total">0</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="align-right" style="text-align:right;font-weight:bold" colspan="4">Biaya Bank</td>
                                            <td class="align-right angka" style="text-align:right;font-weight:bold" id="td-biaya-bank"><input id="biaya-bank" name="Biaya Bank" tabindex="50" value="0"  class="form-control input-sm angka text-right entri" type="text" tabindex="50"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table> --}}
                            </div>
                        </div>


                        <!-- TABEL TRANSAKSI DIV -->
                        <div class="card">
                            <div class="container py-2 px-2 mx-2 mw-100" id = "trn-tbl">
                                <div class="row" id = "trn-head">
                                    <div class="row" id = "trn-head-row">
                                        <div class="col-lg-1 border huruf-kecil text-center fw-bold py-1">
                                            Program
                                        </div>
                                        <div class="col-lg-3 border huruf-kecil text-center fw-bold py-1">
                                            Project
                                        </div>
                                        <div class="col-lg-2 col-md-12 text-right border huruf-kecil text-center fw-bold py-1">
                                            Wakif
                                        </div>
                                        <div class="col-lg-1 col-md-12 text-right border huruf-kecil text-center fw-bold py-1">
                                            Qty
                                        </div>
                                        <div class="col-lg-1 col-md-12 text-right border huruf-kecil text-center fw-bold py-1">
                                            Dana
                                        </div>
                                        <div class="col-lg-2 col-md-12 text-right border huruf-kecil text-center fw-bold py-1">
                                            Jumlah
                                        </div>
                                        <div class="col-lg-1 col-md-12 border">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id = "trn-body">
                                    @if($ModeEdit == 'EDIT')
                                    @foreach ($donasi->items as $item)
                                        @php
                                        $dana = 0;
                                        @endphp
                                        @if($item->qty!=0)
                                            @php
                                            $dana= $item->jmh/$item->qty;
                                            @endphp
                                        @endif
                                        <div class="row trn-body-row" id="trn-body-row">
                                            <div class="col-lg-1 kolom-program border  py-1 px-1"><span class="huruf-kecil">{{$item->kd_program}}</span><input type="hidden" class="kd-prg" name="program-id[]" value="{{$item->kd_program}}"></div>
                                            <div class="col-lg-3 kolom-project border  py-1 px-1"><span class="huruf-kecil">{{$item->nm_project}}</span><input type="hidden" class="kd-prj" name="kd-prj[]" value="{{$item->kd_project}}"></div>
                                            <div class="col-lg-2 col-md-12 kolom-wakif border py-1 px-1"><span class="huruf-kecil px-2">{{$item->nm_wakif}}</span></div>
                                            <div class="col-lg-1 col-md-12 kolom-qty border py-1 px-1 text-right"><span class="huruf-kecil px-2">{{number_format($item->qty,0,',','.')}}</span></div>
                                            <div class="col-lg-1 col-md-12 kolom-dana border py-1 px-1 text-right"><span class="huruf-kecil px-2">{{number_format($dana,0,',','.')}}</span></div>
                                            <div class="col-lg-2 col-md-12 kolom-jmh border huruf-kecil text-right px-2 py-2" style="display:table-cell; vertical-align:middle"><span class="huruf-kecil px-2">{{number_format($item->jmh,0,',','.')}}</span></div>
                                            <div class="col-lg-1 col-md-12 .kolom-aksi border py-1 px-1 text-center"> 
                                            <a href="#" class="btn-edit" data-btn="EDIT"><i class="px-2 fa fa-pencil text-success fa-lg"></i></a>
                                            <a href="#" class="btn-hapus" data-btn="EDIT"><i class="px-2 fa fa-close text-danger fa-lg"></i></a></div>
                                        </div>
                                    @endforeach
                                    @endif
                                    <div class="row trn-body-row entri" id = "trn-body-row">
                                        <div class="col-lg-1 kolom-program border  py-1 px-1">
                                            <select class="form-control select2 input-sm entri cbo-program huruf-kecil" name="pilih-program" id="pilih-program" tabindex="34" style="width: 100%">
                                                <option></option>
                                                @foreach($getProgram as $program)
                                                    <option value="{{$program->kd_program}}">{{$program->kd_program}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 kolom-project border  py-1 px-1">
                                            <select class="form-control select2 input-sm entri cbo-prj huruf-kecil " name="pilih-prj" id="entri-prj" tabindex="35" style="width: 100%">
                                                <option value=""></option>
                                                @foreach($getProject as $project)
                                                    <option value="{{$project->kd_project}}">{{$project->nm_project}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-12 kolom-wakif border py-1 px-1">
                                            <input id="entri-wakif" value="" class="form-control input-sm huruf-kecil entri-wakif py-1"  style="display: flex;justify-content: center;align-items: center;" type="text" tabindex="36">
                                        </div>
                                        <div class="col-lg-1 col-md-12 kolom-qty border py-1 px-1">
                                            <input id="entri-qty" value="1" class="form-control input-sm angka text-right huruf-kecil entri-qty  py-1" type="text" tabindex="36" disabled='disabled'>
                                        </div>
                                        <div class="col-lg-1 col-md-12 kolom-dana border py-1 px-1">
                                            <input id="entri-dana" class="form-control input-sm angka text-right entri-dana huruf-kecil  py-1" type="text" tabindex="37" disabled='disabled'>
                                        </div>
                                        <div class="col-lg-2 col-md-12 kolom-jmh border huruf-kecil text-right py-2 px-2 " style="display:table-cell; vertical-align:middle">
                                            <span class="px-2" id="entri-jmh">0</span>
                                        </div>
                                        <div class="col-lg-1 col-md-12 .kolom-aksi border py-1 px-1 text-center">
                                            <button class="btn btn-block btn-blue btn-sm" id="tambah-baris" tabindex="38" >Tambah</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id = "trn-footer">
                                    <div class="row" id = "trn-footer-row">
                                        <div class="col-lg-4 border huruf-kecil text-center fw-bold py-1">
                                            
                                        </div>
                                        <div class="col-lg-2 border huruf-kecil text-right fw-bold py-1">
                                            TOTAL TRANSFER
                                        </div>
                                        <div class="col-lg-1 col-md-12 text-right border huruf-kecil text-right fw-bold py-1">
                                            <span id="total-transfer">{{$totalTransfer}}</span>
                                        </div>
                                        <div class="col-lg-1 col-md-12 text-right border huruf-kecil text-right fw-bold py-1">
                                            T O T A L
                                        </div>
                                        <div class="col-lg-2 col-md-12 text-right border huruf-kecil text-right fw-bold py-1 px-4">
                                            <span id="trn-total">0</span>
                                        </div>
                                        <div class="col-lg-1 col-md-12 border">
                                            
                                        </div>
                                    </div>
                                    <div class="row" id = "trn-footer2-row">
                                        <div class="col-lg-8 col-md-12 text-right border huruf-kecil text-right fw-bold py-2">
                                            Biaya Admin
                                        </div>
                                        <div class="col-lg-2 col-md-12 text-right border huruf-kecil text-right fw-bold py-1 px-1">
                                            <input id="biaya-bank" value="0" class="form-control input-sm text-right huruf-kecil" type="text" tabindex="50">
                                        </div>
                                        <div class="col-lg-1 col-md-12 border">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <br/>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-cari">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Pencarian Donatur</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body pb-2 mb-0">
                <div class="card border py-2 px-2 mb-1">
                    <div class="row" style=" padding-bottom:2px;">
                        <form id="modal-form" action="/Donasi/Create" method="post"><input name="__RequestVerificationToken" type="hidden" value="pePQGhP9lp5BjUHKDWDBjL0cLGV0CAYmre0U-Y_wGiwdXC3v1iic7iw8QpbA3BmpGT_ZlufJBKzbav2ZozzLQkAlcv1aUvCu9vQu66ZSgLE1" />
                            <div class="form-horizontal form-group-sm">
                                <div class="form-group row row-sm mb-0 align-items-center">
                                    <label class="col-md-4 fs-11 text-right fw-bold" for="Nama_Donatur" style="padding-left:0px;padding-right:0px">Cari (Kode Donatur,Nama, HP, Email) </label>
                                    <div class="col-md-4">
                                        <input class="form-control form-control-sm  mb-1" id="filter-nm" name="filter-nm" type="text" value="" />
                                    </div>
                                    <div class="col-md-2">
                                        <a href="#" class="btn btn-primary btn-sm" id="tampil-lookup"><i class="fa fa-search" style="margin-right:5px"></i>Tampil</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-1">
                    <div class="scrollable" id="wadah-donatur">

                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- End Page -->
@endsection
@section('footer')
    <script type="text/javascript">
        // shortcut.add("F12",function() {
        //     SimpanDonasi();
        // });

        var strTrnBodyRow = '';
        var modeEdit = '<?php echo $ModeEdit; ?>';
        var ModeBaris = 'TAMBAH';

        var idTabel = "tbl-transaksi";
        var idxKolomProgram = 0;var idxKolomPrj = 1;
        var idxKolomQty = 2;  var idxKolomDana = 3; var idxKolomJmh = 4;
        var idxKolomEdit = 5; var idxKolomHapus =6;
        var baseUrl = '@Url.Content("~/")';

        var mode = '';
        var barisAktifIndex = -1;
        var cboProgram; var cboPrj; var cboEditProgram;var cboEditPrj;
        var pelangganArray = [];

        var proses = '';

        //new getKdProgram
        var programArr = @php echo App\Http\Controllers\DonasiController::getKd(); @endphp;
        $(document).ready(function () {
            console.log('siap...');
            //$('#AlurKerja').focus();
            console.log(modeEdit);
            mode = 'TAMBAH';
            var i = 1;

            $(document).delegate(".cbo-program","change",function(){

                if (proses !='')
                {
                    return false;
                }

                let baris = this.parentElement.parentElement;
                var o = GetInArray($(this).val());
                //default---
                $('.entri-qty').attr('disabled', 'disabled');
                $('.entri-dana').removeAttr('disabled');
                $('.entri-qty').val(1);
                $('.entri-dana').val(0);
                //=====
                if(o!=null || o!=undefined){
                    //Program WAP
                    $('.entri-dana').val(AdnFormatNum(o.dana));
                    setJumlah(baris);
                    if (o.kd_kategori.trim()=='01')
                    {
                        //Program WAP
                        $('.entri-dana').attr('disabled', 'disabled');
                        $('.entri-qty').removeAttr('disabled');
                    }
                }
            });

            $(document).delegate(".btn-edit","click",function(){
                btnEditEventListener(this);
            });

            $(document).delegate(".btn-hapus","click",function(){
                btnHapusEventListener(this);
            });

            $(document).delegate(".angka",'keypress',function(evt){
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });

            $(document).delegate("#Hp",'keypress',function(evt){
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });

            //======= Digunakan tapi belum di konversi ===============//
            // if (ViewBag.ModeEdit == "EDIT")
            // {
            //     $(document).delegate(".btn-edit","click",function(){
            //         btnEditEventListener(this);
            //     });

            //     $(document).delegate(".btn-hapus","click",function(){
            //         btnHapusEventListener(this);
            //     });
            // }

            //$('.angka').autoNumeric('init', { aSep: '.', aDec: ',', vMin: "0", vMax: '999999999' });

            // $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
            // $('[data-mask]').inputmask()


            // $('input[type="checkbox"].flat-red').iCheck({
            //     checkboxClass: 'icheckbox_flat-red',
            //     radioClass   : 'iradio_flat-red'
            // })
            //======= Akhir Digunakan tapi belum di konversi ===============//

            //----------------------------------------
            //$elTrnBodyRow = $('.trn-body-row').clone();            
            strTrnBodyRow= $('.trn-body-row.entri').clone().html();

            cboProgram = $('.cbo-program').select2({placeholder:"Pilih Program",dropdownCssClass: "huruf-kecil"});
            cboProject = $('.cbo-prj').select2({placeholder:"Pilih Project",dropdownCssClass: "huruf-kecil"});

            //cboProgram = $('#entri-program').select2();
            //cboPrj = $('#entri-prj').select2();

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            // on first focus (bubbles up to document), open the menu
            $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
            $(this).closest(".select2-container").siblings('select:enabled').select2('open');
            });

            // steal focus during close - only capture once and stop propogation
            $('select.select2').on('select2:closing', function (e) {
            $(e.target).data("select2").$selection.one('focus focusin', function (e) {
                e.stopPropagation();
            });
            });

            //----------------------------------------------
            $(document).delegate(".entri-qty,.entri-dana","change",function(){
                let baris = this.parentElement.parentElement;
                setJumlah(baris);
            });

            $('#cr-hp').on("change",function(){
                $('#cr-email').val('');
                getDonatur($(this).val());
            });

            $('#cr-email').on("change",function(){
                $('#cr-hp').val('');
                getDonaturByEmail($(this).val());
            });

            $('#NoKwitansi').on("change",function(){
                CekNoKwitansi($(this).val());
            });

            $('#KdCabang').on("change",function(){
                getJaringan($(this).val());
                getSales($(this).val());
                
            });//$KdCabang).on('change')

            //$('#KdCabang').trigger('change');

            var tbl;
            setSubTotal();

            $(document).delegate('#tambah-baris','click', function () {
                var $el = $(this);
                elRow = document.querySelectorAll(".trn-body-row:last-child")[0];
                elAksi = elRow.getElementsByClassName('.kolom-aksi')[0];

                // --- Validasi
                let kolomJmh = elRow.getElementsByClassName('kolom-jmh')[0];
                let jmh = kolomJmh.getElementsByTagName('span')[0];
                if(jmh.innerText==0)
                {
                    return false;
                }

                let prg = '';
                let objProgram = $('#pilih-program').select2('data');
                if (objProgram!=null)
                {
                    prg = objProgram[0].text.trim();
                }

                if(prg.trim() =='')
                {
                    return false;
                }

                let kolomWakif = elRow.getElementsByClassName('kolom-wakif')[0];
                let wakif = kolomWakif.getElementsByTagName('input')[0];
                if(wakif.value.trim() =='')
                {
                    return false;
                }
                //=========== END VALIDASI =================


                setAksiEdit(elAksi);
                setAksiHapus(elAksi);
                setBarisEditorNonAktif(elRow);
                
                var wrapper= document.createElement('div');
                wrapper.innerHTML= strTrnBodyRow;
                let lst = wrapper.classList;
                lst.add('row');lst.add('trn-body-row');

                $('#trn-body').append(wrapper);

                cboProgram = $('.cbo-program').select2({placeholder:"Pilih Program",dropdownCssClass: "huruf-kecil"});
                cboProject = $('.cbo-prj').select2({placeholder:"Pilih Project",dropdownCssClass: "huruf-kecil"});
                
                $el.remove();
                setSubTotal();

            });

        });//document

        function btnEditEventListener(src)
        {
            console.log('Event Edit...');
            if (ModeBaris == 'EDITING')
            {
                //Ada Baris yang Sedang DiEdit (Mencegah 2 baris EDITING)
                return false;
            }

            proses = 'Proses Klik Edit...'

            let tmp = document.querySelectorAll(".trn-body-row:last-child");
            tmp[0].remove();
            
            setBarisAktif(src.parentElement.parentElement);
            
            ModeBaris = 'EDITING';$('#btn-simpan').attr("disabled","disabled");
            let elAksi = src.parentElement;
            elAksi.innerHTML = '';

            setAksiOk(elAksi);
            setAksiBatal(elAksi);
            
            proses = '';
        }

        function btnHapusEventListener(src)
        {
            console.log('Event Hapus...');
            if (ModeBaris == 'EDITING')
            {
                //Ada Baris yang Sedang DiEdit
                return false;
            }
            src.parentElement.parentElement.remove();
            setSubTotal();

            
        }

        function btnOkEventListener(src)
        {
            console.log('Event OK...');
            //---- Editor Tambah Baris ---  
            var wrapper= document.createElement('div');
            wrapper.innerHTML= strTrnBodyRow;
            let lst = wrapper.classList;
            lst.add('row');lst.add('trn-body-row');

            $('#trn-body').append(wrapper);

            cboProgram = $('.cbo-program').select2({placeholder:"Pilih Program",dropdownCssClass: "huruf-kecil"});
            cboProject = $('.cbo-prj').select2({placeholder:"Pilih Project",dropdownCssClass: "huruf-kecil"});

            let elRow = src.parentElement.parentElement;
            setBarisEditorNonAktif(elRow);
            setSubTotal();

            //------------------
            ModeBaris = 'TAMBAH'; $('#btn-simpan').removeAttr('disabled');
            let elAksi = src.parentElement;
            elAksi.innerHTML = '';

            setAksiEdit(elAksi);
            setAksiHapus(elAksi);
        }

        function btnBatalEventListener(src)
        {
            console.log('Event Batal...');
            ModeBaris = 'TAMBAH';$('#btn-simpan').removeAttr('disabled');
            let elAksi = src.parentElement;
            setBarisKeNilaiOriginal(elAksi.parentElement);
            
            elAksi.innerHTML = '';
            setAksiEdit(elAksi);
            setAksiHapus(elAksi);
            
            // --- Tambah Baris Editor ---
            var wrapper= document.createElement('div');
            wrapper.innerHTML= strTrnBodyRow;
            let lst = wrapper.classList;
            lst.add('row');lst.add('trn-body-row');

            $('#trn-body').append(wrapper);

            cboProgram = $('.cbo-program').select2({placeholder:"Pilih Program",dropdownCssClass: "huruf-kecil"});
            cboProject = $('.cbo-prj').select2({placeholder:"Pilih Project",dropdownCssClass: "huruf-kecil"});
            // === END Tambah Baris Editor ==== 
        }

        function GetInArray(id) {
            for (var i = 0, len = programArr.length; i < len; i++) {
                if (programArr[i].kdProgram.trim() == id.trim())
                    return programArr[i];
            }
            return null;
        }

        function setBarisAktif(baris) {

            let kolomProgram = baris.getElementsByClassName('kolom-program')[0];
            let elNilai = kolomProgram.getElementsByTagName('span')[0];

            kolomProgram.innerHTML = setProgram() + '<input type="hidden" class="kd-prg" ori-value="' + elNilai.innerText + '" name="kd-prg[]" value="' +elNilai.innerText + '"/>';
            $('.cbo-program').select2({placeholder:"Pilih Program",dropdownCssClass: "huruf-kecil"});
            $('.cbo-program').val(elNilai.innerText);
            $('.cbo-program').trigger('change');

            let kolomProject = baris.getElementsByClassName('kolom-project')[0];
            elNilai = kolomProject.getElementsByTagName('input')[0];
            let elTeks = kolomProject.getElementsByTagName('span')[0];

            kolomProject.innerHTML = setProject() + '<input type="hidden" class="kd-prj" ori-text="'+elTeks.innerText+'"  ori-value="' + elNilai.value + '" name="kd-prj[]" value="' +elNilai.value + '"/>';
            $('.cbo-prj').select2({placeholder:"Pilih Project",dropdownCssClass: "huruf-kecil"});
            $('.cbo-prj').val(elNilai.value);
            $('.cbo-prj').trigger('change');

            let kolom = baris.getElementsByClassName('kolom-wakif')[0];
            let wakif = kolom.getElementsByTagName('span')[0];
            wakif = wakif.innerText;
            kolom.innerHTML = '<input type="text" class="form-control input-sm entri-edit-qty huruf-kecil entri-wakif" id="entri-wakif" tabindex = "104" name="wakif" original-value="' + wakif + '" value="' + wakif + '">';

            kolom = baris.getElementsByClassName('kolom-qty')[0];
            let qty = kolom.getElementsByTagName('span')[0];
            qty = AdnToNum(qty.innerText);
            kolom.innerHTML = '<input type="text" class="form-control input-sm angka text-right entri-edit-qty huruf-kecil entri-qty" id="entri-qty" tabindex = "104" name="qty" original-value="' + qty + '" value="' + qty + '">';

            kolom = baris.getElementsByClassName('kolom-dana')[0];
            let dana = kolom.getElementsByTagName('span')[0];
            dana = AdnToNum(dana.innerText);
            kolom.innerHTML = '<input type="text" class="form-control input-sm angka text-right entri-edit-dana huruf-kecil entri-dana" id="entri-dana" tabindex = "106" name="dana" original-value="' + dana + '" value="' + dana + '">';
  
        }

        function setBarisKeNilaiOriginal(baris) {

            let kolom = baris.getElementsByClassName('kolom-program')[0];
            let elNilai = kolom.getElementsByClassName('kd-prg')[0];
            let nilaiOri = $(elNilai).attr('ori-value');
            kolom.innerHTML = '<span class="huruf-kecil">' + nilaiOri + '</span><input type="hidden" class="kd-prg" name="program-id[]" value="' + nilaiOri + '"/>';

            kolom = baris.getElementsByClassName('kolom-project')[0];
            elNilai = kolom.getElementsByClassName('kd-prj')[0];
            nilaiOri = $(elNilai).attr('ori-value');
            let teksOri = $(elNilai).attr('ori-text');
            kolom.innerHTML = '<span class="huruf-kecil">' + teksOri + '</span><input type="hidden" class="kd-prj" name="project-id[]" value="' + nilaiOri + '"/>';

            kolom = baris.getElementsByClassName('kolom-wakif')[0];
            let nilaiOriWakif = document.getElementById('entri-wakif').getAttribute('original-value');
            kolom.innerHTML = '<span class="huruf-kecil px-2">' + nilaiOriWakif  + "</span>";

            kolom = baris.getElementsByClassName('kolom-qty')[0];
            let nilaiOriQty = AdnFormatNum(document.getElementById('entri-qty').getAttribute('original-value'));
            kolom.innerHTML = '<span class="huruf-kecil px-2">' + nilaiOriQty  + "</span>";
   
            kolom = baris.getElementsByClassName('kolom-dana')[0];
            let nilaiOriDana = AdnFormatNum(document.getElementById('entri-dana').getAttribute('original-value'));
            kolom.innerHTML = '<span class="huruf-kecil px-2">' + nilaiOriDana  + "</span>";
            
            kolom = baris.getElementsByClassName('kolom-jmh')[0];
            kolom.innerHTML = '<span class="huruf-kecil px-2">' + AdnNumToString(AdnToNum(nilaiOriQty) * AdnToNum(nilaiOriDana)) + '</span>';           
        }

        function setBarisEditorNonAktif(baris) {
            
            let kolomProgram = baris.getElementsByClassName('kolom-program')[0];
            let teks = $(baris.getElementsByClassName('cbo-program')[0]).select2('data')[0].text;
            kolomProgram.innerHTML = '<span class="huruf-kecil">' + teks + '</span><input type="hidden" class="kd-prg" name="program-id[]" value="' + teks + '"/>';

            let kolomProject = baris.getElementsByClassName('kolom-project')[0];
            teks = $(baris.getElementsByClassName('cbo-prj')[0]).select2('data')[0].text;
            let nilai = $(baris.getElementsByClassName('cbo-prj')[0]).select2('data')[0].id.trim();
            kolomProject.innerHTML =  '<span class="huruf-kecil">' + teks + '</span><input type="hidden" class="kd-prj" name="kd-prj[]" value="' + nilai + '"/>';

            let kolomWakif = baris.getElementsByClassName('kolom-wakif')[0];
            let kolomQty = baris.getElementsByClassName('kolom-qty')[0];
            let kolomDana = baris.getElementsByClassName('kolom-dana')[0];
            let kolomJmh = baris.getElementsByClassName('kolom-jmh')[0];

            let nilaiWakif = document.getElementById('entri-wakif').value;
            kolomWakif.innerHTML = '<span class="huruf-kecil px-2">' + nilaiWakif  + "</span>";

            let nilaiQty = AdnFormatNum(document.getElementById('entri-qty').value);
            kolomQty.innerHTML = '<span class="huruf-kecil px-2">' + nilaiQty  + "</span>";
            
            let nilaiDana = AdnFormatNum(document.getElementById('entri-dana').value);
            kolomDana.innerHTML = '<span class="huruf-kecil px-2">' + nilaiDana  + "</span>";
            
            kolomJmh.innerHTML = '<span class="huruf-kecil px-2">' + AdnNumToString(AdnToNum(nilaiQty) * (AdnToNum(nilaiDana))) + "</span>";

            let lst = kolomQty.classList;
            lst.add('text-right');

            lst = kolomDana.classList;
            lst.add('text-right');

            lst = kolomJmh.classList;
            lst.add('text-right');

        }

        function setSubTotal() {

            let subTotal = 0;
            let koleksi = document.querySelectorAll(".trn-body-row");
           
            for (i = 0; i < koleksi.length - 1; i++) {
                let kolomJmh = koleksi[i].querySelector('.kolom-jmh');
                let jmh = kolomJmh.getElementsByTagName('span')[0];

                subTotal = subTotal + AdnToNum(jmh.innerText);
            }

            document.getElementById('trn-total').innerHTML = AdnNumToString( subTotal );

        }

        function setJumlah(baris) {
            var jmh = 0;

            var qty = document.getElementById('entri-qty').value;
            var dana = document.getElementById('entri-dana').value;

            jmh = AdnToNum(qty) * AdnToNum(dana);
            var num = AdnNumToString(jmh);

            let kolomJmh = baris.getElementsByClassName('kolom-jmh')[0];
            let elJmh = kolomJmh.getElementsByTagName('span')[0];
            elJmh.innerHTML = num;
        }

        function isValid()
        {
            var sProgram = document.getElementById('entri-program').value;
            var nQty = document.getElementById('entri-qty').value;
            var nDana = document.getElementById('entri-dana').value;

            if (sProgram.trim() =='' ||  AdnToNum(nQty) ==0 || AdnToNum(nDana)==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function getDonatur(noHp)
        {
            if(noHp.toString().trim()=='')
            {
                $('#cr-email').val('');

                $('#pelanggan-id').val('');
                $('#kd-pelanggan').val('');
                $('#NmDonatur').val('');
                $('#Alamat').val('');
                $('#Kota').val('');
                $('#Pos').val('');
                $('#Propinsi').val('0');
                // $('#Telp').val('');
                $('#Hp').val('');
                $('#Email').val('');
                //$('#NmWakif').val('');
                return false;
            }

            var urlAksi = "{{ url('donasi/cariNoHp') }}";
            $('body').loadingModal({text: 'Pencarian Data Donatur...', 'animation': 'fadingCircle'});
            $.ajax({
                url: urlAksi,
                type: "POST",
                data: {'NoHp' :noHp},
                success: function (respon) {
                    if (respon.IsSuccess) {
                        var Obj = respon.Obj;
                        console.log(Obj);
                        if(respon.ID !="" && respon.ID != null)
                        {
                            $('#pelanggan-id').val(Obj.id);
                            $('#kd-pelanggan').val(Obj.kd_pelanggan);
                            $('#NmDonatur').val(Obj.nm_lengkap);
                            $('#Alamat').val(Obj.alamat);
                            $('#Kota').val(Obj.kota);
                            $('#Pos').val(Obj.pos);
                            Obj.propinsi== null || Obj.propinsi.toString().trim()=='' ? $('#Propinsi').val('0'):$('#Propinsi').val(Obj.propinsi);
                            //$('#Telp').val(Obj.telp);
                            $('#Hp').val(Obj.hp);
                            $('#Email').val(Obj.email);

                            $('#NmDonatur').focus();
                        }
                        else
                        {
                            //Donatur Baru
                            $('#pelanggan-id').val('');
                            $('#kd-pelanggan').val('');
                            $('#NmDonatur').val('');
                            $('#Alamat').val('');
                            $('#Kota').val('');
                            $('#Pos').val('');
                            $('#Propinsi').val('0');
                            //$('#Telp').val('');
                            $('#Hp').val(noHp);
                            $('#Email').val('');
                            //$('#NmWakif').val('');
                        }
                    }
                    else {
                        showAlert('error','',"Terjadi Kesalahan: " + respon.Message);
                    }
                }
            }).done(function (data) {
                $('body').loadingModal('destroy');
            }).fail(function (jqXHR, textStatus, errorThrown) { $('body').loadingModal('destroy'); });
        }//GetDonaturByHp

        function getDonaturByEmail(email)
        {
            if(email.toString().trim()=='')
            {
                $('#cr-hp').val('');

                $('#pelanggan-id').val('');
                $('#kd-pelanggan').val('');
                $('#NmDonatur').val('');
                $('#Alamat').val('');
                $('#Kota').val('');
                $('#Pos').val('');
                $('#Propinsi').val('0');
                //$('#Telp').val('');
                $('#Hp').val('');
                $('#Email').val('');
                //$('#NmWakif').val('');
                return false;
            }

            var urlAksi = "{{ url('donasi/cariEmail') }}";
            $('body').loadingModal({text: 'Pencarian Data Donatur...', 'animation': 'fadingCircle'});
            $.ajax({
                url: urlAksi,
                type: "POST",
                data: {'Email' :email},
                success: function (respon) {
                    if (respon.IsSuccess) {
                        var Obj = respon.Obj;
                        if(respon.ID !="" )
                        {
                            $('#pelanggan-id').val(Obj.id);
                            $('#kd-pelanggan').val(Obj.kd_pelanggan);
                            $('#NmDonatur').val(Obj.nm_lengkap);
                            $('#Alamat').val(Obj.alamat);
                            $('#Kota').val(Obj.kota);
                            $('#Pos').val(Obj.pos);
                            Obj.propinsi== null || Obj.propinsi.toString().trim()=='' ? $('#Propinsi').val('0'):$('#Propinsi').val(Obj.propinsi);
                            //$('#Telp').val(Obj.telp);
                            $('#Hp').val(Obj.hp);
                            $('#Email').val(Obj.email);
                            $('#NmDonatur').focus();
                        }
                        else
                        {
                            //Donatur Baru
                            $('#pelanggan-id').val('');
                            $('#kd-pelanggan').val('');
                            $('#NmDonatur').val('');
                            $('#Alamat').val('');
                            $('#Kota').val('');
                            $('#Pos').val('');
                            $('#Propinsi').val('0');
                            //$('#Telp').val('');
                            $('#Hp').val('');
                            $('#Email').val(email);
                            //$('#NmWakif').val('');
                        }
                    }
                    else {
                        showAlert('error','',"Terjadi Kesalahan: " + respon.Message);
                    }
                }
            }).done(function (data) {
                $('body').loadingModal('destroy');
            }).fail(function (jqXHR, textStatus, errorThrown) { $('body').loadingModal('destroy'); });
        }//GetDonaturByEmail

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getJaringan(id)
        {
            $('#KdAgen').empty();
            if (id.trim()=='')
            {
                $('#KdAgen').append('<option value="">--- Pilih Jaringan ---</option>');
                return false;
            }
                                
            $.ajax({
                url: "{{ url('jaringan/getByCabang') }}",
                type: "POST",
                data: {'id' :id},
                dataType: "json",
                success: function (respon) {
                    var lst = respon.data;
                    if (respon.success) {
                        $('#KdAgen').append('<option value="">--- Pilih Jaringan ---</option>');
                        $.each(lst,function(){
                            $('#KdAgen').append('<option value='+this.kd_agen+'>'+this.nm_agen+'</option>');
                        });
                        //$('#KdAgen').val(KdAgen);
                    }
                    else {
                        showAlert('error','',"Terjadi Kesalahan: " + respon.Message);
                    }
                }
            });
        }

        function getSales(kd)
        {
            $('#KdSales').empty();
            if (kd.trim()=='')
            {
                $('#KdSales').append('<option value="">--- Pilih Fundraiser ---</option>');
                return false;
            }
            $.ajax({
                url: "{{ url('salesman/getByCabang') }}",
                type: "POST",
                data: {kd:kd},
                success: function (respon) {
                    let lst = JSON.parse(JSON.stringify(respon));
                    $('#KdSales').append('<option value="">-- Pilih Fundraiser --</option>');
                    for (let item in lst) {
                        $('#KdSales').append('<option value="'+ lst[item].kd_sales +'">'+ lst[item].nm_sales +'</option>');
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            
            $('#chk-auto').click(function(e){
                if($(this).prop('checked')) 
                {    
                    $('#NoKwitansi').attr('disabled','disabled');
                }
                else
                {
                    $('#NoKwitansi').removeAttr('disabled');
                }

            });

            $('.btn-simpan').click(function (e) {
                e.preventDefault();

                if (validasiForm())
                {
                    SimpanDonasi();
                }
            });

            $('.btn-batal').click(function(e){
                var newUrl = "{{URL::to('donasi/create')}}";
                
                //location.reload();
                document.location.href = newUrl;
            });

           // $('#KdCabang').find('option[value="1"]').prop('selected',true);

            var eltgl =  document.getElementById('tgl');
            var momentFormat = 'DD/MM/YYYY';
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

            var eltgl =  document.getElementById('tgl-setor');
            var momentFormat = 'DD/MM/YYYY';
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


            // $(document).on("keydown","#cr-hp",function(e){
            //     switch(e.key) {
            //         case "Enter":
            //             e.preventDefault();
            //             var hp = $('#cr-hp').val();
            //             getDonatur(hp);
            //             break;
            //     }
            // });

        }); //$(document).ready(function ()

        function SimpanDonasi()
        {
            $('#btn-simpan').attr('disabled', 'disabled');
               
            let trn = document.getElementById('trn-body')
            var qty = 0,wakif ='',
                dana = 0,
                jmh = 0,
                kdProgram = "",kdProject="", kolomPrj, kolomProgram,
                dtlID = 0;
            
            var alurKerja = "VERIFIKASI",
                donasiID = $('#tr-id').val(),
                idBukuBank = $('#link-idbukubank').val(),
                noKwitansi = $('#NoKwitansi').val(),
                kdKas = $('#KdKas').val(),
                kdAgen = $('#KdAgen').val(),
                kdSales = $('#KdSales').val(),
                kdCabang = $('#KdCabang').val(),
                tgl = $('#tgl').val(), tglSetor = $('#tgl-setor').val(),
                sah = $('#sah').is(":checked"),
                biayaBank =  $('#biaya-bank').val(),
                auto = 0;
            
            if($('#chk-auto').prop('checked'))
            {
                auto = 1;
            }

            var pelangganID = $('#pelanggan-id').val(),
            kdPelanggan =$('#kd-pelanggan').val() ,
            nmDonatur= $('#NmDonatur').val(),
            alamat= $('#Alamat').val(),
            kota= $('#Kota').val(),
            pos= $('#Pos').val(),
            propinsi= $('#Propinsi').val(),
            //telp= $('#Telp').val(),
            ket= $('#Keterangan').val(),
            hp= $('#Hp').val(),
            email= $('#Email').val();

            //--------- Validasi ---------------------------------------------//
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('donasi/validasi') }}",
                type: "POST",
                data: $('form').serialize(),
                dataType: "json",
                success: function (respon) {
                    if($.isEmptyObject(respon.error)) {
                        // var skrg = new Date();
                        // let year = skrg.getFullYear();
                        // let month = skrg.getMonth();
                        // let date = skrg.getDate();
                        // var tglSkrg = new Date(year,month,date);
                        // var arrId = tgl.split('/'); //tgl-format dd/mm/yyyy
                        // selisihTrans = tglSkrg.getTime() - new Date(arrId[2],arrId[1]-1, arrId[0]).getTime();
                        // selisihTrans = Math.round(selisihTrans/(1000*60*60*24));

                        // if (tgl > tglSkrg)
                        // {
                        //     showAlert('warning','','Tanggal Transaksi Tidak Sah');
                        //     return false;
                        // }

                        // if(selisihTrans<0 || selisihTrans > 360)
                        // {
                        //     showAlert('warning','','Tanggal Transaksi Tidak Sah');
                        //     return false;
                        // }

                        // var arrIdSetor = tglSetor.split('/'); //tgl-format dd/mm/yyyy
                        // selisihSetor =  tglSkrg.getTime() - new Date(arrIdSetor[2],arrIdSetor[1]-1, arrIdSetor[0]).getTime();
                        // selisihSetor = Math.round(selisihSetor/(1000*60*60*24));

                        // if(selisihSetor<0 || selisihSetor > 360)
                        // {
                        //     showAlert('warning','','Tanggal Setor Tidak Sah.');
                        //     return false;
                        // }
                        tgl = moment(tgl, "DD/MM/YYYY").format("YYYY-MM-DD");
                        tglSetor = moment(tglSetor, "DD/MM/YYYY").format("YYYY-MM-DD");
                        
                        const o = {ModeEdit:modeEdit , AlurKerja:alurKerja, DonasiID:donasiID, NoKwitansi:noKwitansi, IdBukuBank:idBukuBank
                                , Sah:sah, KdKas:kdKas, KdAgen:kdAgen, KdSales:kdSales, KdCabang:kdCabang, Tgl:tgl
                                , TglSetor:tglSetor,  Ket:ket,  BiayaBank:biayaBank, Auto:auto};
                        o.donatur = {PelangganID:pelangganID, KdDonatur:kdPelanggan, NmDonatur:nmDonatur
                                , Alamat:alamat, Kota:kota, Pos:pos, Propinsi:propinsi
                                , Hp:hp, Email:email,}
                        o.items = [];
                        
                        baris = document.getElementsByClassName('trn-body-row');
                        rowCount = 0;
                        var total = 0;
                        let kolom;
                        for (i = 0; i < baris.length - 1; i++)
                        {
                            kolomProgram    = baris[i].getElementsByClassName('kolom-program')[0];
                            kdProgram       = kolomProgram.getElementsByTagName('span')[0].innerText;
                            kolomPrj        = baris[i].getElementsByClassName('kolom-project')[0];
                            kdProject       = AdnToString(kolomPrj.getElementsByTagName('input')[0].value);
                            
                            kolom           = baris[i].getElementsByClassName('kolom-wakif')[0];
                            wakif           = kolom.getElementsByTagName('span')[0].innerText;
                            
                            kolom           = baris[i].getElementsByClassName('kolom-qty')[0];
                            qty             = AdnToNum(kolom.getElementsByTagName('span')[0].innerText);
                            
                            kolom           = baris[i].getElementsByClassName('kolom-dana')[0];
                            dana            = AdnToNum(kolom.getElementsByTagName('span')[0].innerText);
                            jmh             = qty*dana;
                            total           = total + jmh;
                            //var btn = baris.item(i).cells.item(idxKolomEdit).firstChild.getAttribute('data-btn');
                            // if(btn.toString().trim().toUpperCase()=="OK")
                            // {
                            //     showAlert('warning','','Detail Transaksi Belum Lengkap.');
                            //     return false;
                            // }

                            const dtl = [{DtlID:dtlID, NoKwitansi:noKwitansi, KdProgram:kdProgram,NmWakif:wakif, KdProject:kdProject, Qty:qty, Dana:dana, Jmh:jmh}];
                            o.items.push(dtl);
                        }
                        if (total != AdnToNum(document.getElementById('trn-total').innerHTML))
                        {
                            showAlert('warning','','Terjadi Kesalahan pada Total Transaksi');
                            return false;
                        }
                        
                        o.Total = total;

                        if(total==0)
                        {
                            showAlert('warning','','Detail Transaksi Tidak Ada.');
                            return false;
                        }

                        var ntransfer = AdnToNum($('#total-transfer').html());
                        // if (idBukuBank.trim()!='' && ntransfer!= total)
                        // {
                        //     showAlert('warning','','TOTAL Transaksi Tidak Sama dengan Jumlah Transfer.');
                        //     return false;
                        // } else {
                        //     console.log('gagal');
                        // }
                        console.log(o);
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ url('donasi/simpan') }}",
                            type: "POST",
                            data: {donasi : o},
                            success: function (respon) {
                                $('#btn-simpan').removeAttr('disabled');
                                console.log(respon);
                                if (respon.IsSuccess)
                                {
                                    showAlert('success','',respon.Message);
                                    modeEdit = 'EDIT';  
                                    $('#kd-pelanggan').val(respon.Obj.KdDonatur);
                                    $('#NoKwitansi').val(respon.Obj.NoKwitansi);
                                //     window.location = baseUrl + ("Donasi/Create");
                                }
                                // else {
                                //     showAlert('error','',"Terjadi Kesalahan: " + respon.Message);
                                // }
                            }, error: function(respon) {
                                $('#btn-simpan').removeAttr('disabled');
                                console.log('Error:', respon);
                            }
                        });
                    } else {
                        $('#btn-simpan').removeAttr('disabled');
                        showAlert('warning','','Data Belum Lengkap.');
                    }
                }
            });
            //--------------------------------------------
        }//SimpanDonasi

    </script><!-- Simpan -->

    <script>
        $(document).ready(function () {
            $('#pelanggan-tambah').click(function (e) {
                $("#wadah-donatur").html("");
            });

            $('#modal-lookup').on('shown.bs.modal', function () {
                $('#filter-nm').focus();
            });

            $('#modal-lookup').on('hidden.bs.modal', function (e) {
                $('#NmDonatur').focus();
            })

            $('#pelanggan-tambah').click(function () {
                mode = 'TAMBAH';
                //$('#modal-lookup').show();
                var myModal = new bootstrap.Modal(document.getElementById('modal-cari'),{
                    backdrop: 'static'
                    });
                myModal.show();
                $('#filter-nm').focus();
            });
            $('#tampil-lookup').click(function(e){
                $("#wait-grid-lookup").css("display", "block");
                loadDataDonatur($('#filter-nm').val());
                $("#wait-grid-lookup").css("display", "none");
            });

            $('#btn-close').click(function () {
                $('#modal-form').trigger( "reset" );
                $('#modal-lookup').hide();
            });

            $(document).delegate(".clickable-row","click",function(){
                var pid = $(this.cells[0]).children('.pid').val();
                var kd = $(this.cells[1]).text();
                var nm = $(this.cells[2]).text();
                var alamat = $(this.cells[3]).text();
                var hp =$(this.cells[4]).text();
                var email =$(this.cells[5]).text();

                //var telp = $(this.cells[0]).children('.ctelp').val();
                var kota = $(this.cells[0]).children('.ckota').val();
                var pos = $(this.cells[0]).children('.cpos').val();
                var kdPropinsi = $(this.cells[0]).children('.cpropinsi').val();

                $('#pelanggan-id').val(pid);
                $('#kd-pelanggan').val(kd);
                $('#NmDonatur').val(nm);
                $('#Alamat').val(alamat);
                $('#Kota').val(kota);
                //$('#Telp').val(telp);
                $('#Pos').val(pos);
                $('#Propinsi').val(kdPropinsi);
                $('#Hp').val(hp);
                $('#Email').val(email);

                $('#cr-hp').val('');
                $('#cr-email').val('');
                $('#filter-nm').val('');
                $('#filter-kd').val('');
                $('#filter-hp').val('');
                $('#modal-cari').modal('hide');
            });

        });//document

    </script><!--Simpan Pelanggan -->

    <script>
        function CekNoKwitansi(noKwitansi)
        {
            var urlAksi = "{{ url('donasi/cariNoKwitansi') }}";
            var no = $('#NoKwitansi').val();

            $.ajax({
                url: urlAksi,
                type: "POST",
                headers: getAdnToken(),
                data: {NoKwitansi: no},
                success: function (respon) {
                    if (respon.IsSuccess) {
                        if(respon.Message=="ADA")
                        {
                            showAlert('warning','','No. Kwitansi = '+ no + ' Sudah ADA dalam Database.');
                            $('#NoKwitansi').val("");
                            $('#NoKwitansi').focus();
                        }
                    }
                    else {
                        showAlert('error','',"Terjadi Kesalahan: " + respon.Message);
                    }
                    // $('#NoKwitansi').focus();
                }
            });

        }

        function loadDataDonatur(crNm){
            $.ajax({
                    url:"{{ route('dns.tbl.donatur') }}",
                    method:"POST",
                    data:{crNm:crNm},
                    success:function(data){
                            $('#wadah-donatur').html(data);
                            $("#ajax-loading").hide();
                    }
            });//$.ajax
        }

    </script><!-- Cek No Kwitansi, LoadDataDonatur -->

    <script>

        function setProgram()
        {
            var ori = $(".kd-prg").attr("ori-value");
            var str = `<select class="form-control input-sm entri cbo-program" name="entri-edit-program" id="entri-edit-program">
                        <option value=""></option>
                        @foreach($getProgram as $program)
                            <option value="{{$program->kd_program}}">{{$program->kd_program}}</option>
                        @endforeach
                    </select>`;
            return str;
        }

        function setProject()
        {
            var ori = $(".kd-prj").attr("ori-value");
            var str = `<select class="form-control input-sm entri cbo-prj" name="pilih-prj" id="pilih-prj">
                        <option value=""></option>
                        @foreach($getProject as $project)
                            <option value="{{$project->kd_project}}">{{$project->nm_project}}</option>
                        @endforeach
                    </select>`;
            return str;
        }


        function setAksiEdit(el)
        {
            var btnEdit = document.createElement("a");
            btnEdit.href = "#";
            btnEdit.addEventListener("click", function (e) {
                e.preventDefault();

                btnEditEventListener(this);

            });
            var imgEdit = document.createElement("i");
            imgEdit.setAttribute('class', 'px-2 fa fa-pencil text-success fa-lg');
            btnEdit.setAttribute('data-btn', 'EDIT');
            btnEdit.appendChild(imgEdit);
            el.appendChild(btnEdit);
        }

        function setAksiHapus(el)
        {
            var btnHapus = document.createElement("a");
            btnHapus.href = "#";
            btnHapus.addEventListener("click", function (e) {
                e.preventDefault();

                btnHapusEventListener(this);

            });
            var imgHapus = document.createElement("i");
            imgHapus.setAttribute('class', 'px-2 fa fa-close text-danger fa-lg');
            btnHapus.appendChild(imgHapus);
            el.appendChild(btnHapus);
        }

        function setAksiOk(el)
        {
            var btnOk = document.createElement("a");
            btnOk.href = "#";
            btnOk.addEventListener("click", function (e) {
                e.preventDefault();

                btnOkEventListener(this);

            });
            var img = document.createElement("i");
            img.setAttribute('class', 'px-2 fa fa-check text-success fa-lg btnOk');
            btnOk.setAttribute('data-btn', 'OK');
            btnOk.appendChild(img);
            el.appendChild(btnOk);
        }

        function setAksiBatal(el)
        {
            var btnBatal = document.createElement("a");
            btnBatal.href = "#";
            btnBatal.addEventListener("click", function (e) {
                e.preventDefault();

                btnBatalEventListener(this);

            });
            var img = document.createElement("i");
            img.setAttribute('class', 'px-2 fa fa-undo text-dangers fa-lg btnBatal');
            btnBatal.appendChild(img);
            el.appendChild(btnBatal);
        }

        function validasiForm() {
            var errors = [];
            //var form = document.getElementsByTagName('form')[0];

            var elNoKwitansi = document.getElementById("NoKwitansi");
            //var elTgl = document.getElementById("tx-tgl");

            if(!($('#chk-auto').prop('checked')))
            {
                if (elNoKwitansi.value.length < 5) {
                    errors.push({
                    elem: elNoKwitansi,
                    message: "No Kwitansi Kosong atau Tidak Sah."
                    });
                }   
            }

            var elKdAgen = document.getElementById("KdAgen");
            if(elKdAgen.value==''){
                errors.push({
                elem: elKdAgen,
                message: "Jaringan Harus Diisi."
                });
            }

            var elKdSales = document.getElementById("KdSales");
            if(elKdSales.value==''){
                errors.push({
                elem: elKdSales,
                message: "Fundraiser Harus Diisi."
                });
            }

            var elEmail = document.getElementById("Email");
            if(elEmail.value.trim()!='')
            {
                if(!elEmail.value.trim().includes('@'))
                {
                        errors.push({
                        elem: elEmail,
                        message: "Format Email Tidak Sah."
                    });
                }
            }           

            // 'KdKas' => 'required',
            // 'Tgl' => 'required',
            // 'TglSetor' => 'required',
            // 'KdAgen' => 'required',
            // 'KdSales' => 'required',
            // 'NmDonatur' => 'required',
            
            var elTgl = document.getElementById("tgl");
            var elTglSetor = document.getElementById("tgl-setor");

            if(elTgl.value.includes('_')){
                errors.push({
                elem: elTgl,
                message: "Tanggal Transaki Tidak Sah."
                });
            }else
            {

            }

            if(elTglSetor.value.includes('_')){
                errors.push({
                elem: elTglSetor,
                message: "Tanggal Setor Tidak Sah."
                });
            }



            // d = new Date(elTgl.value);
            // if (Object.prototype.toString.call(d) === "[object Date]") {
            //         // it is a date
            //         // if (isNaN(d.getTime())) {  // d.valueOf() could also work
            //         //     // date is not valid
            //         // } else {
            //         //     // date is valid
            //         // }
            //     }
            //     else {
            //         errors.push({
            //         elem: elTgl,
            //         message: "Tanggal Tidak Valid."
            //         });
            //     }


            // if(AdnToNum($('#td-total-debet').html()) != AdnToNum($('#td-total-kredit').html()))
            // {
            //     errors.push({
            //         elem: {},
            //         message:'Debet dan Kredit Tidak Seimbang.'
            //     });
            // }

            // if((AdnToNum($('#td-total-debet').html()) + AdnToNum($('#td-total-kredit').html()))==0)
            // {
            //     errors.push({
            //         elem: {},
            //         message:'Total Transaksi Tidak Boleh Nol (0).'
            //     });
            // }

            // var rowCount = $('#tbl-transaksi tbody tr').length;
            // if(rowCount == 1) {
            //     errors.push({
            //         elem: {},
            //         message:'Transaksi minimal 2 (dua) baris.'
            //     });
            // }

            // if (rowCount == 2) {
            //     var lastTr = $('#tbl-transaksi tbody tr:last-child');
            //     console.log('test');
            //     console.log(lastTr.eq(idxKolomAkun).find('input').first().val());
            //     const nl = lastTr.eq(idxKolomAkun).find('input').first().val();
            //     if(nl.trim()==="") {
            //         errors.push({
            //         elem: {},
            //         message:'Kode Akun Tidak Boleh Kosong.'});
            //     }


            //     const debet = lastTr.find('td').eq(idxKolomDebet).find('input').val();
            //     const kredit =lastTr.find('td').eq(idxKolomKredit).find('input').val();
            //     if(AdnToNum(debet)==0 && AdnToNum(kredit)==0){
            //         errors.push({
            //         elem: {},
            //         message:'Terdapat Baris dengan Transaksi 0 (Nol).'});
            //     }
            // }


            let tgl = $('#tgl').val();
            let tglSetor = $('#tgl-setor').val();
            let skrg = new Date();
            let year = skrg.getFullYear();
            let month = skrg.getMonth();
            let date = skrg.getDate();
            var tglSkrg = new Date(year,month,date);
            var arrId = tgl.split('/'); //tgl-format dd/mm/yyyy
            selisihTrans = tglSkrg.getTime() - new Date(arrId[2],arrId[1]-1, arrId[0]).getTime();
            selisihTrans = Math.round(selisihTrans/(1000*60*60*24));

            if (tgl > tglSkrg)
            {
                //showAlert('warning','','Tanggal Transaksi Tidak Sah');
                //return false;
                errors.push({
                    elem: elTgl,
                    message: "Tanggal Transaksi Tidak Sah."
                });
            }

            if(selisihTrans<0 || selisihTrans > 360)
            {
                // showAlert('warning','','Tanggal Transaksi Tidak Sah');
                // return false;
                errors.push({
                    elem: elTgl,
                    message: "Tanggal Transaksi Tidak Sah."
                });
            }

            var arrIdSetor = tglSetor.split('/'); //tgl-format dd/mm/yyyy
            selisihSetor =  tglSkrg.getTime() - new Date(arrIdSetor[2],arrIdSetor[1]-1, arrIdSetor[0]).getTime();
            selisihSetor = Math.round(selisihSetor/(1000*60*60*24));

            if(selisihSetor<0 || selisihSetor > 360)
            {
                //showAlert('warning','','Tanggal Setor Tidak Sah.');
                //return false;
                errors.push({
                    elem: elTglSetor,
                    message: "Tanggal Setor Tidak Sah."
                });
            }
            
            var str ='';
            errors.forEach(item => {
                str += item.message + '<br>'
            });

            if (str!='')
            {
                showAlert('error','',str);  
            }

            return errors.length === 0;
        }


    </script>
@endsection
