<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\MSales;
use App\Models\MCabang;
use App\Adn;

class MSalesController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'msales';
        if (!SESSION::has('UserID')) {
            // return redirect()->route('aman');
        }
    }

    public function index(Request $req)
    {
        $app['judul'] = "Daftar Fundraiser";
        $app['cabang']= MCabang::orderBy('Nm')->get();

        $tampilBarisTabel  = Adn::getSysVar('TampilBarisTabel');
        Session::put('TampilBarisTabel', $tampilBarisTabel);
        return view('pages.salesman.index', $app);
    }

    public function get(Request $req)
    {
        $sales = MSales::where('kd_sales',$req->kdSales)->get()->toArray();
        return response()->json($sales);
    }

    public function getByCabang(Request $req)
    {
        $q = MSales::getByCabang($req->kd);
        return response()->json($q);
    }

    public function save(Request $req)
    {
        try {
            $obj = new MSales;
            if ($req->mode=='EDIT')
            {
                $obj = MSales::where('kd_sales',$req->kd_sales)->firstOrFail();
            }
            if($obj==null){
                $response= Adn::Response(false,"Data Fundraiser Tidak Ditemukan.");
                return response()->json($response);
            }

            //$obj->kd_sales=$req->kd_sales;
            $obj->nm_sales=$req->nm_sales;
            $obj->alias='-';
            $obj->kd_cabang = $req->cabang;
            $obj->aktif=!($req->aktif);
            $obj->uid=session('UserID');
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
            MSales::where('kd_sales',$req->kdSales)->delete();
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
            <th class="py-2">Nama</th>
            <th class="py-2">Cabang/Divisi</th>
            <th class="py-2">Status</th>
            <th class="py-2" colspan="2" width="6%"></th>
          </tr>
        </thead>
        <tbody>';

        $status = (trim($req->status))!='1'?0:1;
        $kdCabang = $req->cabang;

        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $q = DB::table($this->tbl)
        ->selectRaw("kd_sales, nm_sales, Nm as nm_cabang,aktif")
        ->leftJoin('mcabang','ID','=','kd_cabang')
        ->where('aktif',$status);

        if(trim($kdCabang)!='')
        {
            $q= $q->where('kd_cabang',$kdCabang);
        }

        $q= $q->orderBy('nm_sales')
        ->offset($limit_start)
        ->limit($limit)->get();

        $jmh = DB::table($this->tbl);
        $jmh= $jmh->where('aktif',$status);
        if(trim($kdCabang)!='')
        {
            $jmh= $jmh->where('kd_cabang',$kdCabang);
        }
        $total_records =$jmh->count();

        $kelas_baris_akhir ='';
        $tr = '';
        $status = 'AKTIF';
        foreach ($q as $row) {
            $status = ($row->aktif==1)?'AKTIF':'TIDAK AKTIF';
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <input type="hidden" value="'. $row->kd_sales .'">

              <td class="py-1">'. $row->nm_sales .'</td>
              <td class="py-1">'. $row->nm_cabang .'</td>
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
        $q = MSales::where('kd_program','=',$req->kdProgram)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }
}
