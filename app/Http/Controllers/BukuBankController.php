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

class BukuBankController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'ac_tbuku_bank';
    }

    public function index(Request $req)
    {
        $app['judul']   = "Daftar Donasi";
        $app['kas']         = MGM::getKas(true,true);

        $app['judul'] = "Buku Bank";
        return view('pages.bukubank.index', $app);
    }

    public function getTabel(Request $req)
    {
        $sort = $req->sort;
        $sortField = $req->sortField;
        $output ='
        <table class="table table-bordered card-table table-vcenter bottom-bordered" style="font-size:10px;" width="100%">
        <thead>
          <tr class="border-top">
            <th class="py-2 padat-kecil">Tgl</th>
            <th class="py-2 padat-kecil">Deskripsi</th>
            <th class="py-2 padat-kecil">Donasi</th>
            <th class="py-2 padat-kecil">Debet</th>
            <th class="py-2 padat-kecil">Kredit</th>
            <th class="py-2 padat-kecil">Fundraiser</th>
            <th class="py-2 padat-kecil">Keterangan</th>
            <th class="py-2"></th>

          </tr>
        </thead>
        <tbody>';

        $page = (isset($req->page))?$req->page:1;
        $limit = session('TampilBarisTabel');
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;
        $tglDr= $req->tglDr;
        $tglSd= $req->tglSd;
        $cari = $req->cari;
        $tglSd= ($tglSd == '') ? $tglDr:$tglSd;

        $q = DB::table($this->tbl)
            ->join("mkas as kas", $this->tbl.".kd_kas","=","kas.kd_kas");

        if($tglDr!='')
        {
            $q= $q->where('tgl','>=', $tglDr);

            if($tglSd!=''){
                $q= $q->where('tgl','<', Adn::setTglSd($tglSd));
            }
        }
        if(trim($req->kas)!='999')
        {
            $q= $q->where('kas.kd_kas','=',trim($req->kas));
        }

        if(trim($req->cari)!='')
        {
            // $q= $q->where('id_tr','like', '%'.trim($req->cari).'%');
            $jmh= $q->where(function($query) use ($cari) {
                $query->where('kas.kd_kas','like', '%'.trim($cari).'%')
                    ->orWhere('tgl','like', '%'.trim($cari).'%')
                    ->orWhere('deskripsi','like', '%'.trim($cari).'%')
                    ->orWhere('ket','like', '%'.trim($cari).'%')
                    ->orWhere('debet','like', '%'.trim($cari).'%')
                    ->orWhere('no_kwitansi_donasi','like', '%'.trim($cari).'%')
                    ->orWhere('sales','like', '%'.trim($cari).'%')
                    ->orWhere('sales_ket','like', '%'.trim($cari).'%');
            });
        }
        //$q= $q->offset($limit_start)
        // ->orderBy($sortField, $sort)
        //->limit($limit)->get();

        //-----------------
        // $jmh = DB::table($this->tbl);
        // if($tglDr!='')
        // {
        //     $jmh= $jmh->where('tgl','>=', $tglDr);
        //     if($tglSd!=''){
        //         $jmh= $jmh->where('tgl','<', Adn::setTglSd($tglSd));
        //     }
        // }

        // if(trim($req->kas)!='')
        // {
        //     $jmh= $jmh->where('kd_kas','=', $req->kas);
        // }
        // if(trim($req->cari)!='')
        // {
        //     $jmh= $jmh->where(function($query) use ($cari) {
        //         $query->where('kd_kas','like', '%'.trim($cari).'%')
        //             ->orWhere('bank_id','like', '%'.trim($cari).'%')
        //             ->orWhere('tgl','like', '%'.trim($cari).'%')
        //             ->orWhere('deskripsi','like', '%'.trim($cari).'%')
        //             ->orWhere('ket','like', '%'.trim($cari).'%')
        //             ->orWhere('debet','like', '%'.trim($cari).'%')
        //             ->orWhere('id_tr','like', '%'.trim($cari).'%')
        //             ->orWhere('sales','like', '%'.trim($cari).'%')
        //             ->orWhere('sales_ket','like', '%'.trim($cari).'%');
        //     });
        // }

        $total_records = $q->count();
        $q = $q->selectRaw("kas.kd_kas, kas.nm_kas,CONVERT(date, tgl) as tgl,cast(DATEPART(HOUR,tgl) as varchar(2))+':'+ cast(DATEPART(MINUTE,tgl) as varchar(4)) as jam,
            deskripsi,ket,debet,kredit,id_tr,sales,sales_ket,no_kwitansi_donasi, kd_sales, kd_cabang, kd_agen, hp, email");
        $q= $q->offset($limit_start)
        //->orderBy('hdr.id', $sort)
            ->limit($limit)->get();

        $kelas_baris_akhir ='';
        $kolomUpdateFundraising='';
        $tr = '';
                
        foreach ($q as $row) {
            $debet = 0;
            if(trim($row->ket)=='PENDING')
            {
                $debet = '---';
            }
            else
            {
                number_format($row->debet,0,',','.');
            }

            // if($akses->baca)
            // {
            //     $kolomUpdateFundraising ='<td class="py-1"><a href="javascript:void(0)" id="edit">'. $row->id_tr.'</a></td>';
            // } else
            // {
            //     $kolomUpdateFundraising ='<td class="py-1">-</td>';
            // }
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="py-1 padat-kecil text-nowrap width="10%"">'.$row->tgl.'<br/>'.$row->jam.'</td>
              <td class="py-1 padat-kecil">'. $row->deskripsi .'</td>
              <td class="py-1 padat-kecil">'. $row->no_kwitansi_donasi .'</td>
              <td class="py-1 padat-kecil text-right">'. number_format($row->debet,0,',','.') .'</td>
              <td class="py-1 padat-kecil text-right">'. number_format($row->kredit,0,',','.')  .'</td>
              <td class="py-1 padat-kecil">'. $row->sales.'</td>
              <td class="py-1 padat-kecil">'. $row->sales_ket.'</td>
              <td class="px-0 py-1 text-center" width="5%">
                    <button type="button" class="btn py-0 px-0 btn-edit"
                        data-idBukuBank="'.$row->id_tr.'"  
                        data-kdKas="'.$row->kd_kas.'"  
                        data-kwitansi="'.$row->no_kwitansi_donasi.'"  
                        data-deskripsi="'. $row->deskripsi .'" 
                        data-debet="'.$row->debet.'"  
                        data-sales="'. $row->sales.'" 
                        data-kd_sales="'. $row->kd_sales.'" 
                        data-kd_agen="'. $row->kd_agen.'" 
                        data-kd_cabang="'. $row->kd_cabang.'" 
                        data-hp="'. $row->hp.'" 
                        data-email="'. $row->email.'" 
                        data-tgl="'.substr($row->tgl,0,10).'"><i class="fe fe-edit text-success"></i></button>
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

                $jumlah_page = ($total_records!=0 && $limit!=0) ? ceil($total_records / $limit) : 0;
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


}
