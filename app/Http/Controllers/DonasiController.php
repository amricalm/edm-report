<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Response;
use Validator;
use App\Models\MCabang;
use App\Models\MKas;
use App\Models\MSales;
use App\Models\MPropinsi;
use App\Models\TDonasi;
use App\Models\TDonasiDtl;
use App\Models\MDonatur;
use App\MGM;
use App\Adn;
use App\VarGlobal;
use Illuminate\Support\Carbon;

class DonasiController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'tdonasi';
        $this->tbl_dtl = 'tdonasi_dtl';
    }

    public function index(Request $req)
    {
        $app['judul']   = "Daftar Donasi";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        return view('pages.donasi.index', $app);
    }

    public function create(Request $req)
    {
        
        $app['judul']       = "Tambah Donasi";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),false);
        //$app['alurKerja']   = ["ENTRI"=>"1 - Entri","VERIFIKASI"=>"2 - Verifikasi","3 - Pengesahan"=>"3 - Pengesahan"];
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),false);// MCabang::select('ID', 'Nm')->orderBy('Nm')->get()->toArray();
        $app['kas']         = MGM::getKas(true,false);//MKas::select('kd_kas', 'nm_kas')->get()->toArray();
        //$app['sales']       = MSales::select('kd_sales', 'nm_sales')->orderBy('nm_sales')->get()->toArray();
        $app['propinsi']    = MPropinsi::select('kd_propinsi', 'nm_propinsi')->orderBy('nm_propinsi')->get()->toArray();
        $app['getProgram']  = MGM::getProgram(true,false,'kd_program')->toArray();
        $app['getProject']  = MGM::getProject(true)->toArray();
        
        $app['ModeEdit']    = "EDIT";
        $app['linkId']      = trim($req->IdBukuBank);
        $app['totalTransfer']   = 0;
        if($app['linkId'] !='')
        {
            $app['totalTransfer']   = number_format($req->Debet,0,',','.');
            $app['agen']            = MGM::getJaringan(true,false,trim($req->KdCabang));
            $app['sales']           = MGM::getSales(false,false,trim($req->KdCabang));
        }
        $noKwitansi         = trim($req->id);
        if ($noKwitansi == '')
        {
            $app['ModeEdit']    = "TAMBAH";
            $app['donasi']      = MGM::getNewDonasi();
            $app['donasi']->kd_cabang       = trim($req->KdCabang);
            $app['donasi']->kd_agen         = trim($req->KdAgen);
            $app['donasi']->kd_sales        = trim($req->KdSales);
            $app['donasi']->bb_deskripsi    = trim($req->Deskripsi);

            $app['donasi']->donatur->hp     = trim($req->Hp);; 
            $app['donasi']->donatur->email  = trim($req->Email);
        }
        else
        {
            $app['donasi']      = MGM::getDonasi($noKwitansi);
            
            // if($app['donasi']->alur_kerja == 'SAH')
            // {
            //     $app['donasi']->alur_kerja = 'VERIFIKASI';
            // }

            $app['agen']        = MGM::getJaringan(false,false,$app['donasi']->kd_cabang);
            $app['sales']        = MGM::getSales(false,false,$app['donasi']->kd_cabang);
            if ($app['donasi']==null)
            {
                $app['donasi']      = MGM::getNewDonasi();//antisipasi jika ada masalah
            }
            $app['donasi']->bb_deskripsi = trim($req->Deskripsi);
        }
        return view('pages.donasi.create', $app);
    }

    public static function getKd()
    {
        $q = DB::table('mprogram')
             ->select('kd_program as kdProgram','nilai as dana','kd_kategori')
             ->where('aktif', 1)
             ->orderBy("nm_program")
             ->get();
        return $q;
    }

    public static function validasi(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'KdKas' => 'required',
            'Tgl' => 'required',
            'TglSetor' => 'required',
            'KdAgen' => 'required',
            'KdSales' => 'required',
            'NmDonatur' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        return response()->json(["status"=>true,"Message"=>"Data Lengkap."]);
    }

    public static function cariNoKwitansi(Request $req)
    {
        $noKwitansi = trim($req->NoKwitansi);
        
        try {
            $query = DB::table('tdonasi')->where('no_kwitansi', 'like',"%".$noKwitansi."%")->first();
            if(isset($query)) {
                $qryNoKwitansi = trim($query->no_kwitansi);
                if($noKwitansi == $qryNoKwitansi) {
                    return response()->json(["IsSuccess"=>true,"Message"=>"ADA"]);
                } else {
                    return response()->json(["IsSuccess"=>true,"Message"=>""]);
                }
            } else {
                return response()->json(["IsSuccess"=>true,"Message"=>""]);
            }
        } catch(\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public static function cariNoHp(Request $req)
    {

        $noHp = trim($req->NoHp);
        try {
            $hasil = MGM::getDonaturByHp($noHp);
            // $query = DB::table('mpelanggan')->where('hp', 'like',"%".$noHp."%")->first();
            // if(isset($query)) {
            //     $qryNoHp = trim($query->hp);
            //     if($noHp == $qryNoHp) {
            //         $query = DB::table('mpelanggan')->where('kd_pelanggan', $query->kd_pelanggan)->first();
            //         return response()->json(["IsSuccess"=>true,"ID"=>$query->kd_pelanggan,"Obj"=>$query]);
            //     } else {
            //         return response()->json(["IsSuccess"=>true,"ID"=>""]);
            //     }
            // } else {
            //     return response()->json(["IsSuccess"=>true,"ID"=>""]);
            // }
            $response= Adn::Response(true,"Sukses",$hasil);
            return response()->json($response);
        } catch(\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public static function cariEmail(Request $req)
    {
        $email = trim($req->Email);
        try {
            $hasil = MGM::getDonaturByEmail($email);
            // $query = DB::table('mpelanggan')->where('email', 'like',"%".$email."%")->first();
            // if(isset($query)) {
            //     $qryEmail = trim($query->email);
            //     if($email == $qryEmail) {
            //         $query = DB::table('mpelanggan')->where('kd_pelanggan', $query->kd_pelanggan)->first();
            //         return response()->json(["IsSuccess"=>true,"ID"=>$query->kd_pelanggan,"Obj"=>$query]);
            //     } else {
            //         return response()->json(["IsSuccess"=>true,"ID"=>""]);
            //     }
            // } else {
            //     return response()->json(["IsSuccess"=>true,"ID"=>""]);
            // }

            $response= Adn::Response(true,"Sukses",$hasil);
            return response()->json($response);

        } catch(\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function save(Request $req)
    {
        //$donasi = $req->donasi;
        try {
            $objRespon = new \stdClass();
            
            DB::transaction(function ()use ($req,&$objRespon) {
                
                $donasi = $req->donasi;
                $items = $donasi['items']; 
                $donatur = $donasi['donatur'];

                $kdDonatur = trim($donatur['KdDonatur']);
                $noKwitansi = $donasi['NoKwitansi'];
                
                $obj['nm_lengkap']    = $donatur['NmDonatur'];
                $obj['alamat']        = $donatur['Alamat'];
                $obj['kota']          = $donatur['Kota'];
                $obj['pos']           = $donatur['Pos'];
                $obj['propinsi']      = $donatur['Propinsi'];
                //$obj['telp']          = $donatur['Telp']; 
                $obj['hp']            = trim($donatur['Hp']); 
                $obj['email']         = trim($donatur['Email']); 
                $obj['uid']           = session('UserID');
                $obj['uid_edit']      = session('UserID');

                if ($obj['hp'] == "" && $obj['email'] == "")
                {
                    $obj['status'] =  VarGlobal::DONATUR_DIABAIKAN;
                }
                else
                {
                    $obj['status'] = VarGlobal::DONATUR_AKTIF;
                } 
                //$obj->save();
                
                
            //--- Todo Update or Add ?
                //throw new \Error("test error123 : " . strval($donasi['Auto']));
                
                // $alurKerja  = $donasi['AlurKerja'];
                // $sah        = false;
                // if ($alurKerja == "VERIFIKASI")
                // {
                //     $sah = true;
                // }
                
                $sah = true; //Perubahan: langsung SAH

                $dt['no_kwitansi'] = $noKwitansi;
                $dt['kd_kas'] =  $donasi['KdKas'];
                $dt['posting'] = false;
                $dt['sah'] = $sah;
                //$dt['alur_kerja'] = $alurKerja;

                $dt['kd_cabang'] =  $donasi['KdCabang'];
                $dt['kd_agen'] =   $donasi['KdAgen'];
                $dt['kd_sales'] =  $donasi['KdSales'];

                //$dt['nm_wakif'] = $donasi['NmWakif'];
                $dt['total'] = $donasi['Total'];
                $dt['biaya_bank'] =  $donasi['BiayaBank'];

                $dt['ket'] =  "MgmDonasi";
                $dt['sumber'] = 'MgmDonasi';
                
                $dt['tgl_transaksi'] = $donasi['Tgl'];
                $dt['tgl'] = $donasi['TglSetor'];
                $dt['uid_edit'] = session('UserID');
                
                if ($donasi['ModeEdit']=='TAMBAH')
                {
                    //1. Tambah Data Donatur Bisa BARU atau LAMA
                    if($kdDonatur =='')
                    {
                        $kdDonatur = MDonatur::getKd();
                    }
                    $obj['kd_pelanggan']  = $kdDonatur;
                    db::table('mpelanggan')->insert($obj);
                    //========= Data Donatur ===========
                    
                    //2. Tambah Data Donasi Header
                    $dt['kd_pelanggan'] = $kdDonatur;
                    $dt['uid'] = session('UserID');
                    if($donasi['Auto'])
                    {
                        $noKwitansi=MGM::getNoKwitansiAuto();
                        $dt['no_kwitansi'] = $noKwitansi;
                    }
                    db::table($this->tbl)->insert($dt);
                }
                else
                {
                    //1. Update Data Donatur dipastikan LAMA (edit data donatur)
                    db::table('mpelanggan')
                        ->where('kd_pelanggan', "=", $kdDonatur )
                        ->update($obj);
                    //==== Data Donatur ====

                    //2. Update Data Header Donasi
                    $dt['kd_pelanggan'] = $kdDonatur;
                    $dt['tgl_edit'] = Carbon::Now();
                    db::table($this->tbl)
                        ->where('no_kwitansi', "=", $donasi['NoKwitansi'] )
                        ->update($dt);

                    //3. Delete Data Detail Donasi
                    db::table($this->tbl_dtl)->where('no_kwitansi','=',$donasi['NoKwitansi'])->delete();
                }
           
                $objRespon->KdDonatur = $kdDonatur;
                $objRespon->NoKwitansi = $noKwitansi;

             //--- END Donasi --------------------------------
                for ($i=0;$i<count($items);$i++) {
                    $dtl = $items[$i][0];
                    // $obj = new TDonasiDtl;

                    // // $obj->DtlID     = $vdtl['DtlID'];
                    // $obj->no_kwitansi   = $dtl['NoKwitansi'];
                    // $obj->kd_program    = $dtl['KdProgram'];
                    // $obj->kd_project    = $dtl['KdProject'];
                    // $obj->qty           = $dtl['Qty'];
                    // $obj->jmh           = $dtl['Jmh'];
                    
                    // $obj->save();
                    $qty = 0;
                    $jmh = 0;
                                
                    // if(MGM::isProgramWAP($dtl['KdProgram']))
                    // {
                         $qty = $dtl['Qty'];
                         $jmh = $dtl['Jmh'];
                    // }
                    // else
                    // {
                    //     //Non WAP mainkan di dana
                    //     $qty = 1;
                    //     $total = $dtl['Jmh'] *  $dtl['Qty'];
                    //     $jmh = $total;
                    // }
                    DB::table($this->tbl_dtl)->insert([
                        'no_kwitansi'=> $noKwitansi,
                        'nm_wakif' => $dtl['NmWakif'],
                        'kd_program'=>  $dtl['KdProgram'],
                        'kd_project'=> is_null($dtl['KdProject'])?'':$dtl['KdProject'],
                        'qty'=>$qty,
                        'jmh'=>$jmh,
                    ]);
                }
                

            }, 3);//Transaction
            $response= Adn::Response(true,"Berhasil disimpan.",$objRespon);
        }
        
        
        catch(\PDOException $e)
        {
            $response= Adn::Response(false,"Database > " .$e->getMessage());
        }
        catch (\Error $e) {
            $response= Adn::Response(false,$e->getMessage());
        }
        return response()->json($response);
    }

    public function delete(Request $req)
    {
        try {
            $q = DB::table($this->tbl)
                    ->where('no_kwitansi',$req->kd)->delete();
            $response= Adn::Response(true,"Sukses");
        }
        catch(\PDOException $e)
        {
            $response= Adn::Response(false,"Database > " .$e->getMessage());
        }
        catch (\Error $e) {
            $response= Adn::Response(false,$e->getMessage());
        }

        return response()->json($response);
    }

    public function getTabel(Request $req){

        $sort = $req->sort;
        $sortField = $req->sortField;

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th width="10%" class="padat-kecil text-nowrap">Tgl Setor<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'tgl').'" href="#" data-sort-field="tgl" data-sort="'.Adn::setSortData($sort,$sortField,'tgl').'"><i class="'.Adn::setSortIcon($sort,$sortField,'tgl').'"></i></span></a> </th>
            <th width="8%" class="padat-kecil">Tgl Trs<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'tgl_transaksi').'" href="#" data-sort-field="tgl_transaksi" data-sort="'.Adn::setSortData($sort,$sortField,'tgl_transaksi').'"><i class="'.Adn::setSortIcon($sort,$sortField,'tgl_transaksi').'"></i></span></a> </th>
            <th width="8%" class="padat-kecil ">Tgl Input</th>
            <th class="padat-kecil ">No. Kwitansi</th>
            <th class="padat-kecil ">Kd. Jurnal</th>
            <th class="padat-kecil ">Donatur</th>
            <th width="20%" class="padat-kecil ">Alamat</th>
            <th class="padat-kecil ">Hp - Email</th>
            <th class="padat-kecil angka">Dana</th>
            <th class="padat-kecil " colspan="2" width="8%"></th>
          </tr>
        </thead>
        <tbody>';

        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $tglDr= $req->tglDr;
        $tglSd=$req->tglSd;
        $tglSd= ($tglSd == '') ? $tglDr:$tglSd;

        $noKwitansiDr = trim($req->noKwitansiDr);
        $noKwitansiSd = trim($req->noKwitansiSd);
        $noKwitansiSd = ($noKwitansiSd=='')?$noKwitansiDr:$noKwitansiSd;
        $kdCabang = trim($req->kdCabang);
        $kdKas = trim($req->kdKas);
        $jenisPeriode = trim($req->jenisPeriode);
       // $alurDonasi= trim($req->alurDonasi);

        
        $q = DB::table($this->tbl .' as hdr')
            ->join('mpelanggan as plg','hdr.kd_pelanggan','=','plg.kd_pelanggan');
        
        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $q= $q->where('tgl','>=', $tglDr);

                if($tglSd!=''){
                    $q= $q->where('tgl','<', Adn::setTglSd($tglSd));
                }
            }
            else
            {
                $q= $q->where('tgl_transaksi','>=', $tglDr);

                if($tglSd!=''){
                    $q= $q->where('tgl_transaksi','<', Adn::setTglSd($tglSd));
                }
            }
        }

        if($noKwitansiDr!='')
        {
            $q= $q->whereBetween('hdr.no_kwitansi', [$noKwitansiDr, $noKwitansiSd]);
        }

        if($kdCabang!='999')
        {
            $q= $q->where('kd_cabang','=', $kdCabang);
        }

        if($kdKas!='999')
        {
            $q= $q->where('kd_kas','=', $kdKas);
        }

        // if($alurDonasi!='999')
        // {
            
        //     if ($alurDonasi == 'VERIFIKASI')
        //     {
        //         $q= $q->where('sah','=', 1);
        //     }
        //     else
        //     {
        //         $q= $q->where('sah','=', 0);
        //     }
        // }

        $total_records = $q->count();
        $q = $q->selectRaw("kd_agen, tgl,tgl_transaksi,hdr.tgl_tambah, hdr.no_kwitansi, hdr.kd_tkm, hdr.kd_pelanggan, nm_lengkap, alamat,telp,hp, email, hdr.total, hdr.konfirmasi, posting ");
        $q= $q->offset($limit_start)
        ->orderBy($sortField,$sort)
        ->limit($limit)->get();
        
        $kelas_baris_akhir ='';
        $tr = '';
        foreach ($q as $row) {
            // $sah = '';
            // if($row->sah)
            // {
            //     $sah = '<i class="fe fe-check font-weight-bold"></i>';
            // }

            $btn = '<button type="button" class="btn py-0 px-0 btn-edit" ><i class="fe fe-edit text-success"></i></button>
            <button type="button" class="btn py-0 px-0 btn-upload"><i class="fe fe-upload-cloud text-primary"></i></button>
            <button type="button" class="btn py-0 px-0 btn-delete"  data-kd="'.$row->no_kwitansi.'"  ><i class="fe fe-x-circle text-danger"></i></button>';

            if($row->posting)
            {   //Jika sudah diposting TIDAK BISA DIEDIT dan DIHAPUS
                $btn = '<button type="button" class="btn py-0 px-0 btn-upload"><i class="fe fe-upload-cloud text-primary"></i></button>';
            }

            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. substr($row->tgl,0,10) .'</td>
              <td class="padat-kecil">'. substr($row->tgl_transaksi,0,10) .'</td>
              <td class="padat-kecil">'. substr($row->tgl_tambah,0,10) .'</td>
              <td class="padat-kecil id-link">'. $row->no_kwitansi .'</td>
              <td class="padat-kecil">'. $row->kd_tkm .'</td>';
              //<td class="padat-kecil">'. $row->nm_wakif .'</td>
            $tr .= '  <td class="padat-kecil">'. $row->nm_lengkap .'</td>
              <td class="padat-kecil">'. $row->alamat .'</td>
              <td class="padat-kecil">'. $row->hp .'</br>'. $row->email .'</td>
              <td class="padat-kecil angka">'. number_format($row->total,0,',','.') .'</td>
              <td class="px-0 py-1 text-center" width="6%"> ' . $btn . '</td>
            </tr>'
        ;
            $no++;
            if ($no==($limit_start + $limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }
        $output .=  $tr .'</tbody></table>';
        
        $tampilDr= $total_records >0 ? $limit_start+1:0;
        $tampilSd = $total_records >0 ?$no-1:0;
        $output .= '<div class="row mt-4">
            <div class="col-sm-12 col-md-5">
                <div>Tampil '.  ($tampilDr) . ' sd ' . ($tampilSd) .' dari ' . $total_records .' </div>
            </div>
            <div class="col-sm-12 col-md-7">
                <div>
                <nav class="mb-0">
                <ul class="pagination justify-content-end">';
                
                $jumlah_page = ceil($total_records / $limit);
                $jumlah_number = 3; //jumlah halaman ke kanan dan kiri dari halaman yang aktif
                $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
                $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;

                if($page == 1){
                    $output .= '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
                    $output .= '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
                } else {
                    $link_prev = ($page > 1)? $page - 1 : 1;
                    $output .= '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
                    $output .= '<li class="page-item halaman" id="'.$link_prev.'"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
                }

                for($i = $start_number; $i <= $end_number; $i++){
                    $link_active = ($page == $i)? ' active' : '';
                    $output .= '<li class="page-item halaman '.$link_active.'" id="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
                }

                if($page == $jumlah_page){
                    $output .= '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
                    $output .= '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
                } else {
                    $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
                    $output .= '<li class="page-item halaman" id="'.$link_next.'"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
                    $output .= '<li class="page-item halaman" id="'.$jumlah_page.'"><a class="page-link" href="#">Last</a></li>';
                }
                $output .= '
                    </ul>
                </nav>
                </div>
            </div>
        </div>';

        echo $output;

    }

    public function getTabelDonatur(Request $req){
        $output ='
        <table class="table table-hover table-bordered border-bottom fs-11" width="100%" id="tbl-donatur">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil sticky" style="width: 3%">#</th>
            <th class="padat-kecil sticky" style="width: 6%">Kode</th>
            <th class="padat-kecil sticky" style="width: 20%">Nama Lengkap</th>
            <th class="padat-kecil sticky" style="width: 30%">Alamat</th>
            <th class="padat-kecil sticky" style="width: 10%">Hp</th>
            <th class="padat-kecil sticky" style="width: 15%">Email</th>
          </tr>
        </thead>
        <tbody>';

        $page = (isset($req->page))?$req->page:1;
        $limit = 1000;//session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $crNm = $req->crNm;
        $q = DB::table('mpelanggan')
            ->select('kd_pelanggan','nm_lengkap','alamat','kota','pos','propinsi','telp','hp','email')
            ->orderBy('nm_lengkap')
            ->offset($limit_start);
        
        if($crNm!='')
        {
            $q= $q->where('nm_lengkap','like', '%' . $crNm .'%');
            $q= $q->orWhere('hp','like', '%' . $crNm .'%');
            $q= $q->orWhere('kd_pelanggan','like', '%' . $crNm .'%');
            $q= $q->orWhere('email','like', '%' . $crNm .'%');
        }

        $q=$q->limit($limit)->get();

        $kelas_baris_akhir ='class="clickable-row"';
        $tr = '';

        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .' style="cursor: pointer;">
                    <td class="padat-kecil" style="width: 3%">
                        <span>'.$no.'</span><input type="hidden" class="pid" value="'.$row->kd_pelanggan.'" />
                        <input type="hidden" class="ctelp" value="'.$row->telp.'" />
                        <input type="hidden" class="ckota" value="'.$row->kota.'" />
                        <input type="hidden" class="cpos" value="'.$row->pos.'" />
                        <input type="hidden" class="cpropinsi" value="'.$row->propinsi.'" />
                    </td>
                    <td class="padat-kecil" style="width: 6%">'.$row->kd_pelanggan.'</td>
                    <td class="padat-kecil" style="width: 15%">'.$row->nm_lengkap.'</td>
                    <td class="padat-kecil" style="width: 35%">'.$row->alamat.'</td>
                    <td class="padat-kecil" style="width: 10%">'.$row->hp.'</td>
                    <td class="padat-kecil" style="width: 15%"> '.$row->email.'</td>
                </tr>';

            $no++;
            if ($no==($limit_start + $limit))
            {
                $kelas_baris_akhir = 'class="clickable-row border-bottom"';
            }
        }
        $output .=  $tr .'</tbody></table>';

        echo $output;
    }

}
