<?php

namespace App;
use Illuminate\Support\Facades\DB;
use App\KategoriProgram;
use App\Models\MSales;
use Carbon\Carbon;


class MGM{

    public static function getStatusAktif($tambahSemua = false)
    {
        $lst = collect(['Tidak Aktif' => 0, 'Aktif' => 1]);
        if($tambahSemua)
        {
            $lst->prepend(999,'-- Semua --');
        }
        return $lst;
    }

    public static function getAlurDonasi($otoritas,$tambahSemua = false)
    {
        $lst = collect();
        switch ($otoritas)
            {
                case "ENTRI":
                    $lst->prepend('ENTRI','1 - Entri');
                    break;
                // case "VERIFIKASI":
                //     stt = new AdnAlurDonasi(); stt.Kd = "ENTRI"; stt.Nm = "1 - Entri";
                //     lst.Add(stt);

                //     stt = new AdnAlurDonasi(); stt.Kd = "VERIFIKASI"; stt.Nm = "2 - Verifikasi ";
                //     lst.Add(stt);
                //     break;
                // case "SAH":
                //     stt = new AdnAlurDonasi(); stt.Kd = "VERIFIKASI"; stt.Nm = "2 - Verifikasi";
                //     lst.Add(stt);

                //     stt = new AdnAlurDonasi(); stt.Kd = "SAH"; stt.Nm = "3 - Pengesahan ";
                //     lst.Add(stt);
                //     break;
                case "999":
                    //$lst->prepend('SAH','3 - Pengesahan');
                    $lst->prepend('VERIFIKASI','2 - Verifikasi');
                    $lst->prepend('ENTRI','1 - Entri');
                    if($tambahSemua)
                    {
                        $lst->prepend('999','--- SEMUA ---');
                    }
                    break;
                default:
                    break;
            }

        // if($tambahSemua)
        // {
        //     $lst->prepend(999,'-- Semua --');
        // }
        return $lst;
    }

    public static function getKategoriProgram(){
        $tbl = "rf_kategori_program";

        $res =  DB::table($tbl)
        ->select('kd_kategori', 'nm_kategori')
        ->get();

        return $res;
    }

    public static function getGolAkun($kdJenisAkun=''){
        $tbl = "ac_sys_gol_akun";
        $kd = 'kd_gol';
        $nm = 'nm_gol';

        $res =  DB::table($tbl)
        ->select($kd, $nm);
        if($kdJenisAkun!='')
        {
            $res = $res->where('kd_jenis',$kdJenisAkun);
        }
        $res = $res->orderBy('no_urut')
        ->get();

        return $res;
    }

    public static function getTurunan($kdInduk){
        $tbl = "ac_makun";
        $kd = 'turunan';
        $nm = '';

        $res =  DB::table($tbl)
        ->select($kd)
        ->where('kd_akun',$kdInduk)
        ->value('turunan');

        return $res;
    }

    public static function getProgram($aktif=true, $tambahSemua=false,$orderBy='nm_program'){
        $tbl = "mprogram";

        $res =  DB::table($tbl)->selectRaw('rtrim(kd_program) as kd_program,nm_program, nilai');
        if($aktif==true) {
            $res = $res->where('aktif',$aktif);
        }
        $res = $res->orderBy('nm_program')->get();

        if($tambahSemua)
        {
            if (count($res)>1)
            {
                $obj = new \stdClass;
                $obj->kd_program = '999';
                $obj->nm_program ='--- SEMUA ---';
                $res->prepend($obj,0);
            }
        }
        return $res;
    }

    public static function getRfProgram($aktif=true, $tambahSemua=false,$orderBy='nm_program'){
        $tbl = "rf_kategori_program";

        $res =  DB::table($tbl)->selectRaw('rtrim(kd_kategori) as kd_kategori,nm_kategori');
        if($aktif==true) {
            $res = $res->where('aktif',$aktif);
        }
        $res = $res->orderBy('nm_kategori')->get();

        if($tambahSemua)
        {
            if (count($res)>1)
            {
                $obj = new \stdClass;
                $obj->kd_kategori = '999';
                $obj->nm_kategori ='--- SEMUA ---';
                $res->prepend($obj,0);
            }
        }
        return $res;
    }

    public static function isProgramWAP($kdProgram)
    {
        $res=false;
        $q = DB::table('mprogram');
        $q = $q->where('kd_program','=', $kdProgram)->value('kd_kategori');

        if(trim($q) == KategoriProgram::$WAP)
        {
            $res = true;
        }
    }

    public static function getProject($aktif=true, $tambahSemua=false){
        $tbl = "mproject";
        $kd = 'kd_project';
        $nm = 'nm_project';

        $res =  DB::table($tbl)
        ->selectRaw('rtrim(kd_project) as kd_project,nm_project')
        ->where('aktif',$aktif)
        ->orderBy($nm)
        // if($kdProgram!='')
        // {
        //     $res = $res->where('kd_program',$kdProgram);
        // }
        // $res = $res->orderBy('no_urut')
        ->get();

        return $res;
    }

    public static function getProjectByProgram($kdProgram='')
    {
        $q = DB::table('mproject')
             ->selectRaw('kd_project, nm_project,kd_program')
             ->orderBy('nm_project')
             ->where('aktif', 1);

        if(trim($kdProgram)!='')
        {
            $q = $q->where('kd_program',$kdProgram);
        }

        $q = $q->get()->toArray();
        return $q;
        // $str='';
        // foreach ($q as $val)
        // {
        //     if($str!='') $str=$str.'#';
        //     $str = $str . trim($val->kd_project) . "   [" . trim($val->nm_project) . ']';
        // }
        // return $str;
    }

    public static function getNmProgram($kd){
        $tbl = "mprogram";

        $res =  DB::table($tbl)
        ->where('kd_program',$kd)
        ->value('nm_program');

        return $res;
    }

    public static function getNmProject($kd){
        $tbl = "mproject";

        $res =  DB::table($tbl)
        ->where('kd_project',$kd)
        ->value('nm_project');

        return $res;
    }

    public static function getNmJaringan($kd){
        $tbl = "magen";

        $res =  DB::table($tbl)
        ->where('kd_agen',$kd)
        ->value('nm_agen');

        return $res;
    }

    public static function getNmKas($kd){
        $tbl = "mkas";

        $res =  DB::table($tbl)
        ->where('kd_kas',$kd)
        ->value('nm_kas');

        return $res;
    }

    public static function getKas($aktif=true, $tambahSemua=false) {
        $tbl = "mkas";
        $kd_kas = 'kd_kas';
        $nm_kas = 'nm_kas';

        $res =  DB::table($tbl)
            ->select($kd_kas, $nm_kas)
            ->where('aktif', $aktif)
            ->orderBy('nm_kas')
            ->get();

        if($tambahSemua)
        {
            $obj = new \stdClass;
            $obj->kd_kas = '999';
            $obj->nm_kas ='--- SEMUA ---';
            $res->prepend($obj,0);
        }
        return $res;
    }

    public static function getCabang($roleDataCabang='', $tambahSemua=false){
        $tbl = "mcabang";

        $res =  DB::table($tbl)
            ->select('ID as kd_cabang', 'Nm as nm_cabang')
            ->orderBy('Nm')
            ->get();

        if($roleDataCabang != '')
        {
            $arrCabang = explode(';',$roleDataCabang);
            $res = $res->whereIn('kd_cabang',$arrCabang);
        }

        if($tambahSemua)
        {
            if (count($res)>1)
            {
                $obj = new \stdClass;
                $obj->kd_cabang = '999';
                $obj->nm_cabang ='--- SEMUA ---';
                $res->prepend($obj,0);
            }
        }

        return $res;
    }

    public static function getJaringan($aktif=true, $tambahSemua=false,$kdCabang='') {
        $tbl = "magen";
        $kd = 'kd_agen';
        $nm = 'nm_agen';

        $res =  DB::table($tbl)
            ->select($kd, $nm)
            //->where('aktif', $aktif)
            ->orderBy($nm);
        if($kdCabang!='')
        {
            $res = $res->where('CabangID','=',$kdCabang);
        }
        $res = $res->get();

        if($tambahSemua)
        {
            $obj = new \stdClass;
            $obj->kd_agen = '999';
            $obj->nm_agen ='--- SEMUA ---';
            $res->prepend($obj,0);
        }
        return $res;
    }

    public static function getSales($aktif=true, $tambahSemua=false,$kdCabang='') {
        $tbl = "msales";
        $kd = 'kd_sales';
        $nm = 'nm_sales';

        $res =  DB::table($tbl)
            ->select($kd, $nm)
            //->where('aktif', $aktif)
            ->orderBy($nm);
        if($kdCabang!='')
        {
            $res = $res->where('kd_cabang','=',$kdCabang);
        }
        $res = $res->get();

        if($tambahSemua)
        {
            $obj = new \stdClass;
            $obj->kd_sales = '999';
            $obj->nm_sales ='--- SEMUA ---';
            $res->prepend($obj,0);
        }
        return $res;
    }

    public static function getScGroup(){
        $tbl = "sc_group";

        $res =  DB::table($tbl)
        ->select('kd_group', 'nm_group')
        ->orderBy('nm_group')
        ->get();

        return $res;
    }

    public static function getDonaturByHp($noHp)
    {
            $tbl = "mpelanggan";

            $res =  DB::table($tbl)
               ->select('kd_pelanggan', 'nm_lengkap', 'alamat', 'kota', 'pos', 'propinsi', 'hp','email')
                ->where('hp','=', $noHp )
                ->first();
            return $res;
    }
    public static function getDonaturByEmail($email)
    {
            $tbl = "mpelanggan";

            $res =  DB::table($tbl)
               ->select('kd_pelanggan', 'nm_lengkap', 'alamat', 'kota', 'pos', 'propinsi', 'hp','email')
                ->where('email','=',  $email )
                ->first();
            return $res;
    }

    public static function getDonasi($noKwitansi)
    {
        $tbl = "tdonasi";

        $res =  DB::table($tbl)
        ->selectRaw('no_kwitansi, alur_kerja, no_kwitansi,
            tgl_transaksi, tgl, tgl_tambah, kd_kas,
            sah, kd_agen, kd_cabang, kd_sales, kd_pelanggan,
            total, biaya_bank')
        ->where('no_kwitansi',$noKwitansi)
        ->first();

        if ($res!=null){

            $res->tgl = date_format(date_create($res->tgl),"d/m/Y");
            $res->tgl_transaksi = date_format(date_create($res->tgl_transaksi),"d/m/Y");

            $dtl = DB::table('tdonasi_dtl')
                ->selectRaw('tdonasi_dtl.kd_program, tdonasi_dtl.kd_project, nm_project,tdonasi_dtl.nm_wakif,
                    qty, 0 as dana, jmh')
                ->leftJoin('mproject','tdonasi_dtl.kd_project','=','mproject.kd_project')
                ->where('no_kwitansi',$noKwitansi)
                ->get();

            $res->items = $dtl;

            $donatur = DB::table('mpelanggan')
                ->selectRaw('kd_pelanggan, nm_lengkap, alamat, kota, pos,
                    telp, propinsi, hp, email')
                ->where('kd_pelanggan',trim($res->kd_pelanggan))
                ->first();

            $res->donatur =  $donatur;

        }
        return $res;
    }
    public static function getNewDonasi()
    {
            $donasi = new \stdClass();
            $donasi->no_kwitansi='';
            $donasi->id='';
            //$donasi->alur_kerja='';
            $donasi->no_kwitansi='';
            $donasi->tgl_transaksi='';
            $donasi->tgl='';
            $donasi->tgl_tambah='';
            $donasi->kd_kas='';
            $donasi->sah='';
            $donasi->kd_agen='';
            $donasi->kd_cabang='';
            $donasi->kd_sales='';
            $donasi->kd_pelanggan='';
            $donasi->total=0;
            $donasi->biaya_bank=0;
            //$donasi->nm_wakif='';

            $donasi->bb_deskripsi='';//deskripsi diambil dari buku-bank (modul verifikasi)

            $donasi->donatur = new \stdClass();
            $donasi->donatur->kd_pelanggan='';
            $donasi->donatur->nm_lengkap='';
            $donasi->donatur->alamat='';
            $donasi->donatur->kota='';
            $donasi->donatur->pos='';
            $donasi->donatur->telp='';
            $donasi->donatur->propinsi=0;
            $donasi->donatur->hp='';
            $donasi->donatur->email='';

            $dtl = new \stdClass();
            $dtl->kd_program='';
            $dtl->kd_project='';$dtl->nm_project='';$dtl->nm_wakif='';
            $dtl->qty=0; $dtl->dana=0;$dtl->jmh=0;

            $donasi->items[0] = $dtl;

            return $donasi;
    }

    public static function getNoKwitansiAuto(){

        $sPrefix = 'AUTO' . Carbon::now()->format('y');
        $q = DB::select(DB::raw('select next value for dbo.PencacahNoKwitansi'));
        $arr = json_decode(json_encode($q[0]), true);
        $seq = $arr[''];

        $hasil = $sPrefix . str_pad($seq,6,'0',STR_PAD_LEFT);

        return $hasil;
    }


}
