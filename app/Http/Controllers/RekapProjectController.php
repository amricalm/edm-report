<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Adn;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\MGM;

class RekapProjectController extends Controller
{
    public function index(Request $req)
    {
        $app['judul']   = "Rekap Keuangan Berdasarkan Proyek";
        $app['program'] = MGM::getRfProgram(false,false);
        return view('pages.rekap_project.rekapkeuanganproject', $app);
    }

    public function get(Request $req)
    {
        $sort = $req->sort;
        $sortField = $req->sortField;
        $barisProgram = 0;

        $page       = (isset($req->page))?$req->page:1;
        $limit      = session('TampilBarisTabel');
        $limit_start= ($page - 1) * $limit;
        $no         = $limit_start+1;
        $tglDr      = $req->tglDr;
        $tglSd      = $req->tglSd;
        $tglSd      = ($tglSd == '') ? $tglDr:$tglSd;
        $kdProgram  = $req->kdProgram;
        $sFilter    = "";

        if (!empty($kdProgram))
        {
            $whereProgram = "(";
            foreach ($kdProgram as $item)
            {
                if ($whereProgram != "(")
                {
                    $whereProgram .= " OR ";
                }
                $whereProgram .= " rf.kd_kategori ='".$item."'";
            }
            $whereProgram .= ")";
        }
        $kategori = isset($whereProgram) ? $whereProgram : '';



        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold text-nowrap">#</th>
            <th class="padat-kecil fw-bold" rowspan="2">Kategori</th>
            <th class="padat-kecil fw-bold angka">Program</th>
            <th class="padat-kecil fw-bold angka">Project</th>
            <th class="padat-kecil fw-bold angka">Pendapatan</th>
            <th class="padat-kecil fw-bold angka">Pengeluaran</th>
            <th class="padat-kecil fw-bold angka">Mutasi Masuk</th>
            <th class="padat-kecil fw-bold angka">Mutasi Keluar</th>
            <th class="padat-kecil fw-bold angka">Saldo</th>
        </tr>';

        $output .='</thead><tbody>';

        if($tglDr!='')
        {
            $sFilter .= " jur.tgl >= ".DB::raw("'".$tglDr."'");
            if($tglSd!=''){
                $sFilter .= " AND jur.tgl < ".DB::raw("'".Adn::setTglSd($tglSd)."'");
            }
        }

        $q = "select kd_kategori, nm_kategori,kd_program, nm_program, kd_project, nm_project, "
            ."         sum(pendapatan) pendapatan, sum(pendapatan_program)pendapatan_program, sum(pendapatan_pembinaan)pendapatan_pembinaan,sum(pendapatan_operasi)pendapatan_operasi,  "
            ."         sum(pengeluaran) pengeluaran, sum(pendapatan-pengeluaran) saldo, sum(mutasi_masuk)mutasi_masuk, sum(mutasi_keluar)mutasi_keluar "
            ." from "
            ." ( "
            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , sum(dtl.kredit) pendapatan, 0 as pendapatan_program, 0 as pendapatan_pembinaan, 0 as pendapatan_operasi, 0 as pengeluaran "
            ." , 0 as mutasi_masuk,  0 as mutasi_keluar"
            ." from ac_tjurnal jur "
            ." inner join ac_tkm tkm "
            ." 	on jur.kd_jurnal = tkm.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "

            //Pendapatan Program
            ." UNION  "

            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , 0 as pendapatan, sum(dtl.kredit) pendapatan_program, 0 as pendapatan_pembinaan, 0 as pendapatan_operasi, 0 as pengeluaran "
            ." , 0 as mutasi_masuk,  0 as mutasi_keluar"
            ." from ac_tjurnal jur "
            ." inner join ac_tkm tkm "
            ." 	on jur.kd_jurnal = tkm.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ."     and dtl.kd_akun = prg.kd_akun_program "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "


            //Pendapatan Pembinaan
            ." UNION  "

            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , 0 as pendapatan, 0 as pendapatan_program, sum(dtl.kredit)pendapatan_pembinaan,0 as pendapatan_operasi, 0 as pengeluaran "
            ." , 0 as mutasi_masuk,  0 as mutasi_keluar"
            ." from ac_tjurnal jur "
            ." inner join ac_tkm tkm "
            ." 	on jur.kd_jurnal = tkm.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ."     and dtl.kd_akun = prg.kd_akun_pembinaan "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "


            //Pendapatan Operasi
            ." UNION  "

            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , 0 as pendapatan, 0 as pendapatan_program, 0 as pendapatan_pembinaan, sum(dtl.kredit)pendapatan_operasi, 0 as pengeluaran "
            ."     , 0 as mutasi_masuk,  0 as mutasi_keluar"
            ." from ac_tjurnal jur "
            ." inner join ac_tkm tkm "
            ." 	on jur.kd_jurnal = tkm.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ."     and dtl.kd_akun = prg.kd_akun_operasi "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "



            ." UNION  "

            ." select rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project, "
            ." 0 as pendapatan, 0 as pendapatan_program, 0 as pendapatan_pembinaan,0 as pendapatan_operasi, sum(dtl.debet)  as pengeluaran "
            ." , 0 as mutasi_masuk,  0 as mutasi_keluar"
            ." from ac_tjurnal jur "
            ." inner join ac_tkk tkk "
            ." 	on jur.kd_jurnal = tkk.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl  "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "

            //Mutasi Masuk
            ." UNION  "

            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , 0 as pendapatan, 0 as pendapatan_program, 0 as pendapatan_pembinaan, 0 as pendapatan_operasi, 0 as pengeluaran "
            ."     , sum(dtl.debet) mutasi_masuk, 0 as mutasi_keluar "
            ." from ac_tjurnal jur "
            ." inner join ac_tkk tkk "
            ." 	on jur.kd_jurnal = tkk.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program = prg.kd_program "
            ."     and dtl.kd_program_sumber_dana != '' "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program, prg.nm_program, dtl.kd_project, pry.nm_project "


            //Mutasi Keluar
            ." UNION  "
            ." select rf.kd_kategori, rf.nm_kategori,  dtl.kd_program_sumber_dana as kd_program, prg.nm_program "
            ."     , dtl.kd_project, pry.nm_project "
            ."     , 0 as pendapatan, 0 as pendapatan_program, 0 as pendapatan_pembinaan, 0 as pendapatan_operasi, 0 as pengeluaran "
            ."     ,  0 as mutasi_masuk, sum(dtl.debet) mutasi_keluar "
            ." from ac_tjurnal jur "
            ." inner join ac_tkk tkk "
            ." 	on jur.kd_jurnal = tkk.kd_jurnal "
            ." inner join ac_tjurnal_dtl dtl "
            ." 	on jur.kd_jurnal = dtl.kd_jurnal "
            ." inner join mprogram prg "
            ." 	on dtl.kd_program_sumber_dana = prg.kd_program "
            ." inner join rf_kategori_program rf "
            ."     on prg.kd_kategori = rf.kd_kategori "
            ." left outer join mproject pry "
            ." on dtl.kd_project = pry.kd_project ";
            if ($sFilter != "")
            {
                $q .= " WHERE " .$sFilter;
            }

            if ($kategori != "")
            {
                $q .= " AND ".$kategori;
            }

            $q .= " group by rf.kd_kategori, rf.nm_kategori, dtl.kd_program_sumber_dana, prg.nm_program, dtl.kd_project, pry.nm_project "

            ." ) tr  "
            ." group by kd_kategori, nm_kategori,kd_program, nm_program"
            ." order by nm_kategori,nm_program";






        $jmh = DB::select($q);
        if(count($jmh)) {
            $q .= " limit ".$limit." offset ".$limit_start;
        }

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        $total_records = count($jmh);

        $kelas_baris_akhir ='';
        $tr = '';
        $totalPendapatan = 0;
        $totalPengeluaran = 0;
        $totalMutasiMasuk = 0;
        $totalMutasiKeluar = 0;
        $totalSaldo = 0;
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. $no .'</td>
              <td class="padat-kecil id-link">'. $row['nm_kategori'] .'</td>
              <td class="padat-kecil angka">'. $row['nm_program'] .'</td>
              <td class="padat-kecil angka">'. $row['nm_project'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['pendapatan'],0,',','.') .'</td>
              <td class="padat-kecil angka">'. number_format($row['pengeluaran'],0,',','.') .'</td>
              <td class="padat-kecil angka">'. number_format($row['mutasi_masuk'],0,',','.') .'</td>
              <td class="padat-kecil angka">'. number_format($row['mutasi_keluar'],0,',','.') .'</td>
              <td class="padat-kecil angka">'. number_format($row['saldo'],0,',','.') .'</td>';

            $tr .='</tr>';
            $no++;
            $totalPendapatan += $row['pendapatan'];
            $totalPengeluaran += $row['pengeluaran'];
            $totalMutasiMasuk += $row['mutasi_masuk'];
            $totalMutasiKeluar += $row['mutasi_keluar'];
            $totalSaldo += $row['saldo'];

            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="2">TOTAL</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalPendapatan,0,',','.') .'</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalPengeluaran,0,',','.') .'</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalMutasiMasuk,0,',','.') .'</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalMutasiKeluar,0,',','.') .'</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalSaldo,0,',','.') .'</td>';
        $tr .='</tr>';

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
                $jumlah_page = $limit!='' ? ceil($total_records / $limit) : 1;
                $jumlah_number = 3; //jumlah halaman ke kanan dan kiri dari halaman yang aktif
                $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
                $end_number = ($page < ($jumlah_page - $jumlah_number))? $page .$jumlah_number : $jumlah_page;

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
                    $link_next = ($page < $jumlah_page)? $page +1 : $jumlah_page;
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

    public function exportXls(Request $req)
    {
        $barisProgram = 0;

        $tglDr      = $req->query('tglDr');
        $tglSd      = $req->query('tglSd');
        $tglSd      = ($tglSd == '') ? $tglDr:$tglSd;

        $kdCabang   = $req->query('kdCabang');
        $getProgram = $req->query('kdProgram');
        $kdProgram  = explode(',', $getProgram);
        $kdJaringan = $req->query('kdJaringan');
        $kdKas      = $req->query('kdKas');
        $getSales   = $req->query('sales');
        $kdSales    = explode(',', $getSales);
        $jenisPeriode= $req->query('jenisPeriode');

        $sFilter            = "";
        $sWhere             = "";
        $sKolomProgram      = "";
        $sSumKolomProgram   = "";
        $sKolom             = "";
        $where              = "";
        $whereProgram       = "";

        // get Program ----------------------------------------
        if(!empty($kdProgram))
        {
            $sql = "SELECT kd_program,nm_program FROM mprogram ";
            if (!empty($kdProgram))
            {
                $whereProgram = "(";
                foreach ($kdProgram as $item)
                {
                    if ($whereProgram != "(")
                    {
                        $whereProgram .= " OR ";
                    }
                    $whereProgram .= " kd_program ='".$item."'";
                }
                $whereProgram .= ")";
            }

            $where = $whereProgram;
            if ($where != "")
            {
                $sql .= " where ".$where;
            }

            // if ($KdKategori != "")
            // {
            //     $sql .= " AND kd_kategori ='".$KdKategori."'";
            // }

            $sql .= " ORDER BY nm_program ";

            $rdr = DB::select($sql);
            $rdr = json_decode(json_encode($rdr), true);
            $arrKdProgram = array();
            foreach($rdr AS $rdr)
            {
                if ($sKolomProgram != "")
                {
                    $sKolom             = $sKolom.",";
                    $sKolomProgram      = $sKolomProgram.",";
                    $sSumKolomProgram   = $sSumKolomProgram.",";
                }

                $sKolom             = $sKolom."kol".$barisProgram;
                $sKolomProgram      = $sKolomProgram."case when dtl.kd_program = '".$rdr['kd_program']."' then sum(dtl.jmh) end AS kol".$barisProgram;
                $sSumKolomProgram   = $sSumKolomProgram."ifnull(sum(kol".$barisProgram."),0)kol".$barisProgram;

                // //Tambah Kolom
                // tbl.Columns.Add("d" .$barisProgram.ToString(), typeof(Decimal));

                $barisProgram++;
                $arrKdProgram[$barisProgram] = $rdr['kd_program'];
            }
        }
        // end get Program ----------------------------------------

        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $sFilter .= " tgl >= ".DB::raw("'".$tglDr."'");
                if($tglSd!=''){
                    $sFilter .= " AND tgl < ".DB::raw("'".Adn::setTglSd($tglSd)."'");
                }
            }
            else
            {
                $sFilter .= " tgl_transaksi >= ".DB::raw("'".$tglDr."'");

                if($tglSd!=''){
                    $sFilter .= " AND tgl_transaksi < ".DB::raw("'".Adn::setTglSd($tglSd)."'");
                }
            }
        }

        if($kdCabang!='')
        {
            if($kdCabang!='999')
            {
                $sWhere = " sal.kd_cabang = ".$kdCabang;
            }
        }

        if($kdJaringan!='')
        {
            if($kdJaringan!='999')
            {
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_agen = ".$kdJaringan;
            }
        }

        if($kdKas!='')
        {
            if($kdKas!='999')
            {
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_kas = ".$kdKas;
            }
        }

        $q = "SELECT sal.kd_sales, sal.nm_sales, IFNULL(SUM(hdr.total),0)total ";

        $q .= " FROM msales sal "
            . " LEFT JOIN tdonasi AS hdr "
            . " ON hdr.kd_sales = sal.kd_sales ";
        if ($sFilter != "")
        {
            $q .= "AND " .$sFilter;
        }

        if ($sWhere != "")
        {
            $q .= "WHERE " .$sWhere;
        }

        $q .= " group by sal.kd_sales ";
        $q .= " order by nm_sales ";
        // dd($q);
        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);

        // ------------------------------------------------------------------
        $huruf  = array_slice(range('D', 'Z'), 0, $barisProgram);
        $huruf = array();
        for($col = 'D'; $col!='AZ'; $col++) {
            $huruf[] = $col;
        }
        $spreadsheet = new Spreadsheet();
        $styleArray = array(
            'font'  => array(
                'size'  => 7,
                'name'  => 'Arial Narrow'
            ));

        $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getPageSetup()->setVerticalCentered(true);

        $r = 1;
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setWorksheet($sheet);
        $drawing->setName('logo');
        $drawing->setDescription('logo');
        $drawing->setPath('./assets/images/brand/logo.png');
        $drawing->setCoordinates('A1');
        $drawing->setHeight(50);

        $sheet->getRowDimension($r)->setRowHeight(40);
        $r++;

        $sheet->setCellValue('A'.$r, 'Rekapitulasi Dana Wakaf Per Fundraiser');
        $sheet->getStyle('A'.$r)->getFont()->setSize(10);
        $sheet->getStyle('A'.$r)->getFont()->setBold(true);
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $sheet->setCellValue('A'.$r, preg_replace('/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/', ' $0', $jenisPeriode));
        $sheet->setCellValue('C'.$r, ': ' . date("d F Y", strtotime($tglDr)). ' - '. date("d F Y", strtotime($tglSd)));
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setSize(10);
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setBold(true);
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;
        $r++;

        $sheet->setCellValue('A'.$r, 'No');
        $sheet->setCellValue('B'.$r, 'Jaringan');
        $sheet->setCellValue('C'.$r, 'Jumlah');

        $hr     = 'C'; // $barisProgram!=0 ? $huruf[$barisProgram-1] : 'C';
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $no = 1;
        $totalDonasi = 0;
        foreach($q as $row)
        {
            $sheet->setCellValue('A'.$r, $no);
            $sheet->setCellValue('B'.$r, $row['nm_sales']);
            $sheet->setCellValue('C'.$r, $row['total']);
            $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('B'.$r)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('C'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            $no++;
            $r++;
            $totalDonasi += $row['total'];
        }

        $sheet->getStyle('C'.$r)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('B'.$r, 'TOTAL :');
        $sheet->setCellValue('C'.$r, $totalDonasi);
        $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');

        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //==== end TOTAL ===========================================

        $fileName = "Donasi per Fundraiser";
        $type ='xlsx';
        $sheet->setTitle($fileName);

        $fileName = $fileName.date('Y',strtotime($tglDr)).'-'.date('n',strtotime($tglDr)).''.date('j',strtotime($tglDr)).'-'.date('j',strtotime($tglSd)).'.'.$type;
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/')."/".$fileName);
    }
}
