<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\MProject;
use App\Adn;
use App\MGM;
use App\Models\MProgram;

class MProjectController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'mproject';
        if (!SESSION::has('UserID')) {
            // return redirect()->route('aman');
        }
    }

    public function index(Request $req)
    {
        $app['judul'] = "Daftar Project";
        $app['program'] = MGM::getProgram(true);
        $tampilBarisTabel  = Adn::getSysVar('TampilBarisTabel');
        Session::put('TampilBarisTabel', $tampilBarisTabel);
        return view('pages.mproject.index', $app);
    }

    public function get(Request $req)
    {
        $project = MProject::where('kd_project',$req->kdProject)->get()->toArray();

        return response()->json($project);
    }

    public function save(Request $req)
    {
        try {
            $obj = new MProject;
            if ($req->mode=='EDIT')
            {
                $obj = MProject::find($req->kd_project);
            }
            else
            {
                $obj->uid= session('UserID');
            }
            if($obj==null){
                $response= Adn::Response(false,"Data Project Tidak Ditemukan.");
                return response()->json($response);
            }

            $obj->kd_project=$req->kd_project;
            $obj->nm_project=$req->nm_project;
            $obj->kd_program=$req->kd_program;
            $obj->tgl_mulai=$req->tgl_mulai;
            $obj->ditutup=(boolean)$req->ditutup;
            $obj->aktif=!($req->aktif);
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
            MProject::where('kd_project',$req->kdProject)->delete();
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
            <th class="py-2" width="10%">Kode<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'kd_project').'" href="#" data-sort-field="kd_project" data-sort="'.Adn::setSortData($sort,$sortField,'kd_project').'"><i class="'.Adn::setSortIcon($sort,$sortField,'kd_project').'"></i></a></span></th>
            <th class="py-2">Nama Project<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'nm_project').'" href="#" data-sort-field="nm_project" data-sort="'.Adn::setSortData($sort,$sortField,'nm_project').'"><i class="'.Adn::setSortIcon($sort,$sortField,'nm_project').'"></i></a></span></th>
            <th class="py-2">Program</th>
            <th class="py-2">Status</th>
            <th class="py-2" colspan="2" width="6%"></th>
          </tr>
        </thead>
        <tbody class="border-bottom">';

        $status = (trim($req->status))!='1'?0:1;
        
        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $q = DB::table($this->tbl)
        ->selectRaw("*")
        ->where('mproject.aktif',$status);

        if(trim($req->program)!='')
        {
            $q= $q->where('mproject.kd_program','=',$req->program);
        }

        if(trim($req->cari)!='')
        {
            $q= $q->where('nm_project','like', '%'.trim($req->cari).'%');
        }

        $total_records = $q->count();
        $q = $q->selectRaw("kd_project,nm_project,mproject.aktif,mproject.kd_program,nm_program")
            ->leftJoin('mprogram','mproject.kd_program','=','mprogram.kd_program')
            ->offset($limit_start)
            ->orderBy($sortField, $sort)
            ->limit($limit)->get();

        // $jmh = DB::table($this->tbl);
        // $jmh= $jmh->where('aktif',$status);
        // if(trim($req->cari)!='')
        // {
        //     $jmh = $jmh->where('nm_project','like', '%'.trim($req->cari).'%');
        // }

        // $total_records =$jmh->count();

        $kelas_baris_akhir ='';
        $tr = '';
        $status = 'AKTIF';
        foreach ($q as $row) {
            $status = ($row->aktif==1)?'AKTIF':'TIDAK AKTIF';
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1">'. $row->kd_project .'</td>
              <td class="py-1">'. $row->nm_project .'</td>
              <td class="py-1">'. $row->nm_program .'</td>
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
        $q = MProject::where('kd_project','=',$req->kdProject)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }

    public static function getKd()
    {
        $q = DB::table('mproject')
             ->selectRaw('kd_project')
             ->where('aktif', 1)
             ->orderBy("kd_project")
             ->get()->toArray();

        $str='';
        foreach ($q as $row)
        {
            if($str!='') $str=$str.',';
            $str = $str . trim($row->kd_project);
        }
        return $str;
    }

    public static function getProject()
    {
        $q = DB::table('mproject')
             ->selectRaw('kd_project, nm_project')
             ->orderBy('nm_project')
             ->where('aktif', 1)
             ->get()->toArray();

        $str='';
        foreach ($q as $val)
        {
            if($str!='') $str=$str.'#';
            $str = $str . trim($val->kd_project) . "   [" . trim($val->nm_project) . ']';
        }
        return $str;
    }

}
