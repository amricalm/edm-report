<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\MGM;
use App\Models\ScPengguna;
use App\Adn;

class ScPenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware('adn.auth');
        $this->tbl = 'sc_pengguna';
    }

    public function index(Request $req)
    {
        $app['judul'] = "Daftar Pengguna";
        $app['status'] =  MGM::getStatusAktif(true);
        $app['group']= MGM::getScGroup();
        return view('pages.keamanan.index', $app);
    }

    public function get(Request $req)
    {
        $obj = ScPengguna::where('nm_login',$req->kd)->get();
        return response()->json($obj);
    }

    public function save(Request $req)
    {
        try {
            $obj = new ScPengguna;
            if ($req->mode=='EDIT')
            {
                $obj = ScPengguna::find($req->kd);
                if($obj==null){
                    $response= Adn::Response(false,"Data Pengguna Tidak Ditemukan.");
                    return response()->json($response);
                }
            }
            else
            {
                $obj->uid=session('UserID');
            }

            $obj->nm_login=$req->kd;
            $obj->kd_group=$req->kd_group;
            $obj->aktif=!($req->aktif);
            $obj->pwd=($req->password);
            $obj->uid_edit=session('UserID');
            $obj->save();

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

    public function delete(Request $req)
    {
        try {
            ScPengguna::where('nm_login',$req->kd)->delete();
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

    public static function getKd()
    {
        $q = DB::table('sc_pengguna')
             ->selectRaw('nm_login')
             ->where('aktif', 1)
             ->orderBy("nm_login")
             ->get()->toArray();

        $str='';
        foreach ($q as $row)
        {
            if($str!='') $str=$str.',';
            $str = $str . trim($row->nm_login);
        }
        return $str;
    }

    public function getTabel(Request $req){
        $sort = $req->sort;
        $sortField = $req->sortField;
        $output ='
        <table class="table table-bordered card-table table-vcenter text-nowrap" width="100%">
        <thead>
          <tr class="border-top">
            <th class="py-2" width="20%">Nama Login <span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'nm_login').'" href="#" data-sort-field="nm_login" data-sort="'.Adn::setSortData($sort,$sortField,'nm_login').'"><i class="'.Adn::setSortIcon($sort,$sortField,'nm_login').'"></i></span></a></th>
            <th class="py-2">Nama Group <span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'nm_group').'" href="#" data-sort-field="nm_group" data-sort="'.Adn::setSortData($sort,$sortField,'nm_group').'"><i class="'.Adn::setSortIcon($sort,$sortField,'nm_group').'"></i></span></a></th>
            <th class="py-2">Status</th>
            <th class="py-2" colspan="2" width="6%"></th>
          </tr>
        </thead>
        <tbody>';

        //$status = (trim($req->status))!='1'?0:1;
        $status = $req->status;
        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $where = '';

        $q = DB::table($this->tbl)
        ->join('sc_group', 'sc_pengguna.kd_group', '=', 'sc_group.kd_group');

        if ($status != 999)//Semua
        {
            $where = 'aktif = ' . $status;
            $q= $q->whereRaw($where);
        }

        if(trim($req->group)!='')
        {
            $q= $q->whereRaw("sc_pengguna.kd_group = '".trim($req->group) ."'");
        }

        if(trim($req->txSearch)!='')
        {
            $q= $q->whereRaw("nm_login like '%".trim($req->txSearch) ."%'");
        }
        $total_records =$q->count();

        $q = $q->offset($limit_start)
            ->selectRaw("nm_login, nm_group,aktif")
            ->orderBy($sortField, $sort)
            ->limit($limit)->get();

        // $jmh = DB::table($this->tbl);
        // if ($status != 999)//Semua
        // {
        //     $jmh= $jmh->whereRaw($where);
        // }

        // if(trim($req->txSearch)!='')
        // {
        //     $jmh= $jmh->whereRaw("nm_login like '%".trim($req->txSearch) ."%'");
        // }

        // $total_records =$jmh->count();

        $kelas_baris_akhir ='';
        $tr = '';
        $status = 'AKTIF';
        foreach ($q as $row) {
            $status = ($row->aktif==1)?'AKTIF':'TIDAK AKTIF';
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1">'. $row->nm_login .'</td>
              <td class="py-1">'. $row->nm_group .'</td>
              <td class="py-1">'. $status .'</td>

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
                <nav class="mb-5">
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

    public function isExist(Request $req)
    {
        $result =false;
        $q = ScPengguna::where('nm_login','=',$req->kd)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }
}
