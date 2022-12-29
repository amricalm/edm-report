<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\MGM;
use App\Models\ScGroup;
use App\Models\ScGroupRole;
use App\Models\MCabang;
use App\Adn;

class ScGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('adn.auth');
        $this->tbl = 'sc_group';
    }

    public function index(Request $req)
    {
        $app['judul'] = "Daftar Group Pengguna";
        return view('pages.keamanan.group', $app);
    }

    public function get(Request $req)
    {
        $obj = ScGroup::where('kd_group',$req->kdGroup)->get()->toArray();

        return response()->json($obj);
    }

    public function save(Request $req)
    {
        try {
            $obj = new ScGroup;
            if ($req->mode=='EDIT')
            {
                $obj = ScGroup::find($req->kd_group);
                if($obj==null){
                    $response= Adn::Response(false,"Data Group Tidak Ditemukan.");
                    return response()->json($response);
                }
            }
            else
            {
                $obj->uid=session('UserID');
            }

            $obj->kd_group=$req->kd_group;
            $obj->nm_group=$req->nm_group;
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
            ScGroup::where('kd_group',$req->kdGroup)->delete();
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
        $q = DB::table('sc_group')
             ->selectRaw('kd_group')
             ->orderBy("kd_group")
             ->get()->toArray();

        $str='';
        foreach ($q as $row)
        {
            if($str!='') $str=$str.',';
            $str = $str . trim($row->kd_group);
        }
        return $str;
    }

    public function getTabel(Request $req){
        $sort = $req->sort;
        $sortField = $req->sortField;

        $output ='
        <table class="table table-bordered card-table table-vcenter text-nowrap border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="py-2" width="10%">Kode</th>
            <th class="py-2">Nama Group<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'nm_group').'" href="#" data-sort-field="nm_group" data-sort="'.Adn::setSortData($sort,$sortField,'nm_group').'"><i class="'.Adn::setSortIcon($sort,$sortField,'nm_group').'"></i></span></a></th>
            <th class="py-2" colspan="2" width="6%"></th>
          </tr>
        </thead>
        <tbody>';

        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $where = '';

        $q = DB::table($this->tbl)
            ->selectRaw("kd_group, nm_group");

        if(trim($req->txSearch)!='')
        {
            $q= $q->where('nm_group','like', '%'.trim($req->txSearch).'%');
        }
        $q= $q->offset($limit_start)
            ->orderBy('nm_group', $sort)
            ->limit($limit)->get();

        $jmh = DB::table($this->tbl);
        if(trim($req->txSearch)!='')
        {
            $jmh= $jmh->where('nm_group','like', '%'.trim($req->txSearch).'%');
        }
        $total_records =$jmh->count();

        $kelas_baris_akhir ='';
        $tr = '';

        foreach ($q as $row) {
            $url = route('scgroup.role', ['kd-group' =>  trim($row->kd_group)]);
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1">'. $row->kd_group .'</td>
              <td class="py-1">'. $row->nm_group .'</td>

              <td class="py-1">
                    <a class="btn bg-warning-transparent py-0 px-2 btn-role" href='. $url . ' title="Pilih Group Role" ><i class="fe fe-check-square"></i></a>
                    <button type="button" class="btn bg-info-transparent py-0 px-2 btn-edit" title="Edit"><i class="fe fe-edit"></i></button>
                    <button type="button" class="btn bg-danger-transparent py-0 px-2 btn-delete" title="Hapus"><i class="fe fe-x-square"></i></button>
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
        $q = ScGroup::where('kd_group','=',$req->kdGroup)->get();
        if($q->count()>0)
        {
            $result = true;
        }
        return json_encode($result);
    }

    public function getRole(Request $req)
    {

        $app['judul'] = "Pengaturan Group Akses";
        $app['kdGroup'] = $req->query('kd-group');
        $app['nmGroup'] = ScGroup::find($app['kdGroup'])->nm_group;
        $app['lstCabang'] =  MCabang::all()->sortBy('Nm');
        $app['strCabang'] = $this->getStringRole($app['kdGroup']);

        return view('pages.keamanan.role', $app);
    }

    public function getTabelRole(Request $req){
        $output ='
        <table class="table  table-bordered table-vcenter card-table text-nowrap border-bottom" width="100%" id="tbl-role">
        <thead>
          <tr class="border-top">
            <th class="py-2" width="5%">No.</th>
            <th class="py-2" width="40%">Modul/Object</th>
            <th class="py-2 text-center" width="5%" >Akses</th>
            <th class="py-2">Tambahan</th>
          </tr>
        </thead>
        <tbody>';

        $aplikasi = $req->aplikasi;
        $page = (isset($req->page))?$req->page:1;
        $limit = 10000;//session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $where = "sc_obj.lingkup='".$aplikasi."'";
        $kdGroup = $req->kdGroup;
        $q = DB::table('sc_obj')
            ->leftJoin('sc_group_role as scr',function($qry)use($kdGroup){
                $qry->on('scr.kd_obj', '=','sc_obj.kd_obj');
                $qry->where('scr.kd_group','=',$kdGroup,'and');
            })
            ->selectRaw("sc_obj.kd_obj, nm_obj, baca,role_entri")
            ->whereRaw($where)
            ->orderBy('sc_obj.nm_obj')
            ->offset($limit_start)
            ->limit($limit)->get();

        $kelas_baris_akhir ='';
        $tr = '';

        foreach ($q as $row) {
            $sTambahan ='';
            $sChecked ='';
            if($row->baca)
            {
                $sChecked='checked';
            }

            if (trim($row->kd_obj) == "FDonasi")
            {
                $sTambahan .=  '
                <select name="RoleEntri[]" class="form-select form-control  form-control-sm  role-entri" title="Pilih Peran" tabindex="1">';
                foreach(MGM::getAlurDonasi('999',true) as $key => $value)
                {
                    $selected = '';
                    if($row->role_entri ==$value)
                    {
                        $selected='selected';
                    }
                    $sTambahan .= '<option value="'.$value.'" ' .$selected .'>'.$key.'</option>';
                }
                $sTambahan .=  ' </select>';
            }
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1">'.$no.'</td>
              <td class="py-1"><input type="hidden" name="kd-obj" class="kd-obj" value="'. $row->kd_obj .'">'.$row->nm_obj.'</td>
              <td class="py-1 text-center"><input name="akses[]" type="checkbox" value="'.$row->baca.'" class="form-check-input cursor-pointer akses" '.$sChecked.'></td>
              <td class="py-1">'.$sTambahan.'</td>
            </tr>';

            $no++;
            if ($no==($limit_start + $limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }
        $output .=  $tr .'</tbody></table>';

        echo $output;
    }

    private function getStringRole($kdGroup)
    {
        $q = DB::table('sc_group_data')
             ->selectRaw('cabang')
             ->where('kd_group', $kdGroup)
             ->get()->toArray();

        $str='';
        foreach ($q as $val)
        {
            if($str!='') $str=$str.',';
            $str = $str . trim($val->cabang);
        }
        return trim($str);
    }

    public function saveRole(Request $req)
    {
        if ($req->lstRole==null)
        {
            $response= Adn::Response(false,"Data Role Tidak Ditemukan.");
            return response()->json($response);
        }

        try {

            DB::transaction(function () use ($req) {
                DB::table('sc_group_role')
                    ->join('sc_obj','sc_group_role.kd_obj','=','sc_obj.kd_obj')
                    ->where('kd_group','=',$req->kdGroup)
                    ->where('lingkup','=',$req->aplikasi)
                    ->delete();

                DB::table('sc_group_data')
                    ->where('kd_group','=',$req->kdGroup)
                    ->delete();

                $dt['kd_group'] = $req->kdGroup;
                $dt['cabang'] = '';
                if($req->lstCabang!=null)
                {
                    $dt['cabang'] = implode(';',$req->lstCabang);
                }
                DB::table('sc_group_data')->insert($dt);

                $dt = [];
                foreach($req->lstRole as $item)
                {
                    $dt['kd_group'] = $req->kdGroup;
                    $dt['kd_obj'] = $item['objID'];
                    $dt['baca'] = $item['akses']='true'?1:0;
                    $dt['edit'] = 0;
                    $dt['hapus'] = 0;
                    $dt['tambah'] = 0;
                    $dt['role_entri'] = $item['roleEntri'];

                    DB::table('sc_group_role')->insert($dt);
                }
            }, 3);
            $response= Adn::Response(true,"Sukses");
            return response()->json($response);


            // $obj = new ScGroup;
            // if ($req->mode=='EDIT')
            // {
            //     $obj = ScGroup::find($req->kdGroup);
            //     if($obj==null){
            //         $response= Adn::Response(false,"Gagal","Data Group Tidak Ditemukan.");
            //         return response()->json($response);
            //     }
            // }
            // else
            // {
            //     $obj->uid=session('UserID');
            // }

            // $obj->kd_group=$req->kdGroup;
            // $obj->nm_group=$req->nmGroup;
            // $obj->uid_edit=session('UserID');

            // $obj->save();

            // $response= Adn::Response(true,"Sukses");
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
}

