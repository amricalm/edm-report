<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Tkm;
use App\Models\Tkm_dtl;
use App\Adn;

class TemporaryController extends Controller
{

    //Temporary ======================================
    //Menu Donasi
    public function test()
    {
        $app['judul'] = "test";
        return view('pages.tmp.test', $app);
    }
    public function donasi()
    {
        $app['judul'] = "Daftar Donasi";
        return view('pages.tmp.donasi', $app);
    }
    public function create()
    {
        $app['judul'] = "Tambah Donasi";
        return view('pages.tmp.donasicreate', $app);
    }
    public function bukubank()
    {
        $app['judul'] = "Buku Bank";
        return view('pages.tmp.bukubank', $app);
    }
    public function bukuedc()
    {
        $app['judul'] = "Buku EDC";
        return view('pages.tmp.bukuedc', $app);
    }
    public function konfirmasi()
    {
        $app['judul'] = "Konfirmasi Donasi";
        return view('pages.tmp.konfirmasidonasi', $app);
    }

    //Menu Donatur
    public function donatur()
    {
        $app['judul'] = "Daftar Donatur";
        return view('pages.tmp.donatur', $app);
    }
    //Temporary End =================================

    private $tbl;

    public function __construct()
    {
        $this->tbl = 'ac_tkm';
    }

    public function index()
    {
        $app['judul'] = "Daftar Donasi";
        return view('pages.donasi.index', $app);
    }

    public function save(Request $req)
    {
        db::beginTransaction();
        try {
            if ($req->mode=='TAMBAH')
            {
                DB::table($this->tbl)->insert([
                    'kd_tkm'=>$req->kd_tkm,
                    'tgl'=>$req->tgl,
                    'dr'=>$req->dr,
                    'deskripsi'=>$req->deskripsi,
                    'kd_jurnal'=>$req->kd_tkm,
                    'uid'=>session('UserID'),
                    'uid_edit'=>session('UserID')
                ]);
                DB::table('ac_tjurnal')->insert([
                    'kd_jurnal'=>$req->kd_tkm,
                    'tgl'=>$req->tgl,
                    'deskripsi'=>$req->deskripsi,
                    'uid'=>session('UserID'),
                    'uid_edit'=>session('UserID')
                ]);
            }
            else{
                DB::table($this->tbl)
                ->where('kd_tkm', "=", $req->kd_tkm )
                ->update([
                    'kd_tkm'=>$req->kd_tkm,
                    'tgl'=>$req->tgl,
                    'dr'=>$req->dr,
                    'deskripsi'=>$req->deskripsi,
                    'kd_jurnal'=>$req->kd_tkm,
                    'uid_edit'=>session('UserID')
                ]);
                DB::table('ac_tjurnal')
                ->where('kd_jurnal', "=", $req->kd_tkm )
                ->update([
                    'kd_jurnal'=>$req->kd_tkm,
                    'tgl'=>$req->tgl,
                    'deskripsi'=>$req->deskripsi,
                    'uid_edit'=>session('UserID')
                ]);
            }

            if ($req->mode=='EDIT')
            {
                DB::table('ac_tkm_dtl')->where('kd_tkm','=',$req->kd_tkm)->delete();
                DB::table('ac_tjurnal_dtl')->where('kd_jurnal','=',$req->kd_tkm)->delete();
                // DB::enableQueryLog();
                // Log::info(DB::getQueryLog());

            }
                // $response= Adn::Response(false,'test');
                // return response()->json($response);
            for($i=0;$i<count($req->items);$i++)
            {
                DB::table('ac_tkm_dtl')->insert([
                'kd_tkm'=> $req->kd_tkm,
                'kd_akun '=> $req->items[$i]['kd_akun'],
                'no_urut'=>$i,
                'debet'=>$req->items[$i]['debet'],
                'kredit'=>$req->items[$i]['kredit'],
                'kd_program'=>$req->items[$i]['kd_program'],
                'kd_project '=>$req->items[$i]['kd_project'],
                'memo'=>$req->items[$i]['memo']
                ]);
                DB::table('ac_tjurnal_dtl')->insert([
                    'kd_jurnal'=> $req->kd_tkm,
                    'kd_akun '=> $req->items[$i]['kd_akun'],
                    'no_urut'=>$i,
                    'debet'=>$req->items[$i]['debet'],
                    'kredit'=>$req->items[$i]['kredit'],
                    'kd_program'=>$req->items[$i]['kd_program'],
                    'kd_project '=>$req->items[$i]['kd_project'],
                    'memo'=>$req->items[$i]['memo']
                    ]);
            }


            db::commit();
            $response= Adn::Response(true,"Sukses");

        }
        catch(\PDOException $e)
        {
            db::rollBack();
            $response= Adn::Response(false,$e->getMessage());
        }
        catch (\Error $e) {
            db::rollBack();
            $response= Adn::Response(false,$e->getMessage());
        }

        //$r = Adn::Response(true,"Test");
        return response()->json($response);
    }

    public function delete(Request $req)
    {
        try {
            Tkm::where('kd_tkm','=',$req->kd)->delete();
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
        <table class="table table-bordered card-table table-vcenter text-nowrap" width="100%">
        <thead>
          <tr class="border-top">
            <th class="py-2">No Kas Masuk<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'kd_tkm').'" href="#" data-sort-field="kd_tkm" data-sort="'.Adn::setSortData($sort,$sortField,'kd_tkm').'"><i class="'.Adn::setSortIcon($sort,$sortField,'kd_tkm').'"></i></span></a> </th>
            <th class="py-2">Tanggal<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'tgl').'" href="#" data-sort-field="tgl" data-sort="'.Adn::setSortData($sort,$sortField,'tgl').'"><i class="'.Adn::setSortIcon($sort,$sortField,'tgl').'"></i></span></a> </th>
            <th class="py-2">Dari</th>
            <th class="py-2">Deskripsi</th>
            <th class="py-2" colspan="2" width="6%"></th>
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

        $q = DB::table($this->tbl)
        ->selectRaw("kd_tkm, tgl, dr, deskripsi");

        if($tglDr!='')
        {
            $q= $q->where('tgl','>=', $tglDr);

            if($tglSd!=''){
                $q= $q->where('tgl','<', Adn::setTglSd($tglSd));
            }
        }

        if(trim($req->noBukti)!='')
        {
            $q= $q->where('kd_tkm','like', '%'.trim($req->noBukti).'%');
        }

        $q= $q->offset($limit_start)
        ->orderBy('kd_tkm', $sort)
        ->limit($limit)->get();
        //-----------------

        $jmh = DB::table($this->tbl);
        if($tglDr!='')
        {
            $jmh= $jmh->where('tgl','>=', $tglDr);
            if($tglSd!=''){
                $jmh= $jmh->where('tgl','<', Adn::setTglSd($tglSd));
            }
        }

        if(trim($req->noBukti)!='')
        {
            $jmh= $jmh->where('kd_tkm','like', '%'.trim($req->noBukti).'%');
        }

        $total_records = $jmh->count();
        $kelas_baris_akhir ='';
        $tr = '';
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1">'. $row->kd_tkm .'</td>
              <td class="py-1">'. substr($row->tgl,0,10) .'</td>
              <td class="py-1">'. $row->dr .'</td>
              <td class="py-1">'. $row->deskripsi .'</td>
              <td class="py-1">
                    <button type="button" class="btn bg-info-transparent py-0 px-2 btn-edit" ><i class="fe fe-edit"></i></button>
                    <button type="button" class="btn bg-danger-transparent py-0 px-2 btn-delete"><i class="fe fe-x-square"></i></button>
                </td>
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

    public function get(Request $req)
    {
        $tkm = Tkm::where('kd_tkm','=',$req->kd)->get();
        return json_encode($tkm);
    }

    public function getEdit(Request $req)
    {
        $m = Tkm::where('kd_tkm','=',$req->kd)->get()->toArray();

        $dtl = DB::table('ac_tkm_dtl')
             ->selectRaw('*')
             ->whereRaw("kd_tkm ='" . $req->kd . "'")
             ->orderby('no_urut')
             ->get()->toArray();

        $m['items'] = $dtl;

        return json_encode($m);
    }

    public function isExist(Request $req)
    {
        $result =false;
        $q = Tkm::where('kd_tkm','=',$req->noBukti)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }

}
