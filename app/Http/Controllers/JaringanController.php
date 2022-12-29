<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\MAgen;
use App\Models\MCabang;
use App\Adn;

class JaringanController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'magen';
        if (!SESSION::has('UserID')) {
            // return redirect()->route('aman');
        }
    }

    public function index(Request $req)
    {
        $app['judul'] = "Daftar Jaringan";
        $app['cabang']= MCabang::get();

        $tampilBarisTabel  = Adn::getSysVar('TampilBarisTabel');
        Session::put('TampilBarisTabel', $tampilBarisTabel);
        return view('pages.jaringan.index', $app);
    }

    public function get(Request $req)
    {
        $data = MAgen::where('kd_agen',$req->kdAgen)->get()->toArray();

        return response()->json($data);
    }

    public function getByCabang(Request $req)
    {
        $data = MAgen::select('kd_agen','nm_agen')->where('CabangID',$req->id)->orderBy('nm_agen')->get()->toArray();
        return Response::json(array('success' => true,'data'=>$data));
    }


    public function save(Request $req)
    {
        try {
            $obj = new MAgen;
            if ($req->mode=='EDIT')
            {
                $obj = MAgen::where('kd_agen',$req->kd_agen)->firstOrFail();
            }
            if($obj==null){
                $response= Adn::Response(false,"Data Jaringan Tidak Ditemukan.");
                return response()->json($response);
            }

            $obj->kd_agen=$req->kd_agen;
            $obj->nm_agen=$req->nm_agen;
            $obj->CabangID=$req->CabangID;
            $obj->uid="adn";
            $obj->uid_edit="adn";

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
            MAgen::where('kd_agen',$req->kdAgen)->delete();
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
        $output ='
        <table class="table table-bordered card-table table-vcenter text-nowrap border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="py-2" width="10%">Kode</th>
            <th class="py-2">Nama Jaringan</th>
            <th class="py-2">Kantor/Cabang</th>
            <th class="py-2" colspan="2" width="6%"></th>
          </tr>
        </thead>
        <tbody>';

        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $q = DB::table($this->tbl)
            ->leftJoin('mcabang','CabangID','=','ID')
            ->select('kd_agen','nm_agen','mcabang.Nm AS nm_cabang');
        if(!empty($req->status)) {
            $q = $q->where('CabangID',$req->status);
        }
        $q = $q->offset($limit_start)->limit($limit);
        $q = $q->orderBy('nm_agen')->get();
        $jmh = DB::table($this->tbl);
        if(!empty($req->status)) {
            $jmh= $jmh->where('CabangID',$req->status);
        }
        $total_records =$jmh->count();

        $kelas_baris_akhir ='';
        $tr = '';
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <input type="hidden" value="'. $row->kd_agen .'">
              <td class="py-1">'. $row->kd_agen .'</td>
              <td class="py-1">'. $row->nm_agen .'</td>
              <td class="py-1">'. $row->nm_cabang .'</td>

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
        $q = MAgen::where('nm_agen','=',$req->kdAgen)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }
}
