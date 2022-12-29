<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Response;
use Validator;
use App\MGM;
use App\Adn;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SebastianBergmann\Environment\Console;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->tbl = 'tdonasi';
    }
    //DONASI ---------------------------------------
    public function donasi(Request $req)
    {
        $app['judul']   = "Laporan Donasi";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        $app['program']     = MGM::getProgram(false,false);
        $app['jaringan']    = MGM::getJaringan(true,true,'');
        return view('pages.laporan.laporandonasi', $app);
    }

    public function getLapDonasi(Request $req)
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

        $kdCabang   = trim($req->kdCabang);
        $program    = $req->kdProgram;
        $kdJaringan = trim($req->kdJaringan);
        $kdKas      = trim($req->kdKas);
        $jenisPeriode= trim($req->jenisPeriode);

        $sFilter            = "";
        $sKolomProgram      = "";
        $sSumKolomProgram   = "";
        $where              = "";
        $whereProgram       = "";

        // get Program ----------------------------------------
        if(!empty($program))
        {
            $sql = "select kd_program,nm_program from mprogram ";
            if (!empty($program))
            {
                $whereProgram = "(";
                foreach ($program as $item)
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

            $sql .= " ORDER BY nm_program ";

            $rdr = DB::select($sql);
            $rdr = json_decode(json_encode($rdr), true);

            $arrKdProgram = array();
            foreach($rdr AS $rdr)
            {
                if ($sKolomProgram != "")
                {
                    $sKolomProgram = $sKolomProgram.",";
                    $sSumKolomProgram = $sSumKolomProgram.",";
                }

                $sKolomProgram = $sKolomProgram."case when dtl.kd_program = '".$rdr['kd_program']."' then sum(dtl.jmh) end AS kol".$barisProgram;
                $sSumKolomProgram = $sSumKolomProgram."ifnull(sum(kol".$barisProgram."),0)kol".$barisProgram;

                // //Tambah Kolom
                // tbl.Columns.Add("d" .$barisProgram.ToString(), typeof(Decimal));

                $barisProgram++;
                $arrKdProgram[$barisProgram] = $rdr['kd_program'];
            }
            $sql = "select hdr.no_kwitansi ";
            if($sKolomProgram != "")
            {
                $sql .= ','.$sKolomProgram;
            }

            $sql .= " from tdonasi AS hdr "
                ." inner join tdonasi_dtl dtl"
                ."     on hdr.no_kwitansi = dtl.no_kwitansi "
                ." inner join mprogram prg "
                ."     on dtl.kd_program = prg.kd_program ";

                // if (KdKategori.ToString().Trim() != "")
                // {
                //     sql += "     and prg.kd_kategori = '" .KdKategori.ToString().Trim() ."'";
                // }

                if ($sFilter != "")
                {
                    $sql .= "WHERE " .$sFilter;
                }
                $sql .= " group by hdr.no_kwitansi, dtl.kd_program ";
        }
        // end get Program ----------------------------------------

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold text-nowrap">#</th>
            <th class="padat-kecil fw-bold text-nowrap">Tgl Setor<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'kd_tkm').'" href="#" data-sort-field="kd_tkm" data-sort="'.Adn::setSortData($sort,$sortField,'kd_tkm').'"><i class="'.Adn::setSortIcon($sort,$sortField,'kd_tkm').'"></i></span></a> </th>
            <th class="padat-kecil fw-bold">Tgl Trs<span class="float-end"><a class="sort" id="'.Adn::setSortAktif($sortField,'tgl').'" href="#" data-sort-field="tgl" data-sort="'.Adn::setSortData($sort,$sortField,'tgl').'"><i class="'.Adn::setSortIcon($sort,$sortField,'tgl').'"></i></span></a> </th>
            <th class="padat-kecil fw-bold">No. Kwitansi</th>
            <th class="padat-kecil fw-bold">Pendaftar</th>
            <th class="padat-kecil fw-bold">a.n. Wakif</th>
            <th class="padat-kecil fw-bold">Alamat</th>
            <th class="padat-kecil fw-bold">Hp - Email</th>
            <th class="padat-kecil fw-bold angka">Jumlah</th>';
            if($barisProgram!=0) {
                foreach($arrKdProgram as $hdrProgram) {
                    $output .='<th class="padat-kecil fw-bold angka">'. $hdrProgram .'</th>';
                }
            }
        $output .='</tr>
        </thead>
        <tbody>';

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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "select sah, kd_agen, tgl,tgl_transaksi, hdr.no_kwitansi, hdr.nm_wakif, hdr.kd_pelanggan, nm_lengkap, alamat,telp,hp,email, hdr.total ";

        if ($sSumKolomProgram != "")
        {
            $q .= ",". $sSumKolomProgram;
        }

        $q .= " from tdonasi AS hdr "
        . " inner join mpelanggan plg"
        . "     on hdr.kd_pelanggan = plg.kd_pelanggan ";
        if(!empty($program)) {
            $q .= " inner join ( ";
                $q .= $sql;
            $q .= " ) trn "
                . " on hdr.no_kwitansi = trn.no_kwitansi ";
        }
        if ($sFilter != "")
        {
            $q .= " WHERE " .$sFilter;
        }
        $q .= " group by sah,kd_agen,hdr.no_kwitansi, hdr.nm_wakif,tgl,tgl_transaksi, hdr.kd_pelanggan, nm_lengkap, alamat,telp,hp,email, hdr.total ";
        $jmh = DB::select($q);

        $q .= " limit ".$limit." offset ".$limit_start;
        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        //->orderBy('hdr.id', $sort)
        $total_records = count($jmh);



        $qTotal = "select sum(hdr.total) AS total ";
        if ($sSumKolomProgram != "")
        {
            $qTotal .= ",". $sSumKolomProgram;
        }
        $qTotal .= " from tdonasi AS hdr "
        . " inner join mpelanggan plg"
        . " on hdr.kd_pelanggan = plg.kd_pelanggan ";
        if(!empty($program)) {
            $qTotal .= " inner join ( ";
                $qTotal .= $sql;
            $qTotal .= " ) trn "
                . " on hdr.no_kwitansi = trn.no_kwitansi ";
        }
        if ($sFilter != "")
        {
            $qTotal .= " WHERE " .$sFilter;
        }
        $qTotal = DB::select($qTotal);

        $kelas_baris_akhir ='';
        $tr = '';

        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. $no .'</td>
              <td class="padat-kecil">'. substr($row['tgl'],0,10) .'</td>
              <td class="padat-kecil">'. substr($row['tgl_transaksi'],0,10) .'</td>
              <td class="padat-kecil id-link">'. $row['no_kwitansi'] .'</td>
              <td class="padat-kecil">'. $row['nm_lengkap'] .'</td>
              <td class="padat-kecil">'. $row['nm_wakif'] .'</td>
              <td class="padat-kecil">'. $row['alamat'] .'</td>
              <td class="padat-kecil">'. $row['hp'] .'</br>'. $row['email'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['total'],0,',','.') .'</td>';
              for ($i = 0; $i < $barisProgram; $i++)
              {
                $tr .='<td class="padat-kecil angka fw-bold">'. number_format($row['kol'.$i],0,',','.') .'</td>';
              }
            $tr .='</tr>';

            $no++;
            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="8">TOTAL</td>';
        foreach ($qTotal as $row) {
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($row->total,0,',','.') .'</td>';
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $r = (array)$row;
                $tr .='<td class="padat-kecil angka fw-bold">'. number_format($r['kol'.$i],0,',','.') .'</td>';
            }
          }
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

    public function donasiExportXls(Request $req)
    {
        $tglDr=$req->query('tglDr');
        $tglSd=$req->query('tglSd');
        $kdCabang =$req->query('kdCabang');
        $getProgram = $req->query('kdProgram');
        $program = explode(',', $getProgram);
        $kdJaringan =$req->query('kdJaringan');
        $kdKas =$req->query('kdKas');
        $jenisPeriode =$req->query('jenisPeriode');
        // ------------------------------------------------------------------

        $sFilter            = "";
        $sKolomProgram      = "";
        $sSumKolomProgram   = "";
        $where              = "";
        $whereProgram       = "";
        $barisProgram       = 0;
        // get Program ----------------------------------------
        if(!empty($program))
        {
            $sql = "select kd_program,nm_program from mprogram ";
            if (!empty($program))
            {
                $whereProgram = "(";
                foreach ($program as $item)
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

            $sql .= " ORDER BY nm_program ";

            $rdr = DB::select($sql);
            $rdr = json_decode(json_encode($rdr), true);

            $arrKdProgram = array();
            foreach($rdr AS $rdr)
            {
                if ($sKolomProgram != "")
                {
                    $sKolomProgram = $sKolomProgram.",";
                    $sSumKolomProgram = $sSumKolomProgram.",";
                }

                $sKolomProgram = $sKolomProgram."case when dtl.kd_program = '".$rdr['kd_program']."' then sum(dtl.jmh) end AS kol".$barisProgram;
                $sSumKolomProgram = $sSumKolomProgram."ifnull(sum(kol".$barisProgram."),0)kol".$barisProgram;

                // //Tambah Kolom
                // tbl.Columns.Add("d" .$barisProgram.ToString(), typeof(Decimal));

                $barisProgram++;
                $arrKdProgram[$barisProgram] = $rdr['kd_program'];
            }
            $sql = "select hdr.no_kwitansi ";
            if($sKolomProgram != "")
            {
                $sql .= ','.$sKolomProgram;
            }

            $sql .= " from tdonasi AS hdr "
                ." inner join tdonasi_dtl dtl"
                ."     on hdr.no_kwitansi = dtl.no_kwitansi "
                ." inner join mprogram prg "
                ."     on dtl.kd_program = prg.kd_program ";

                // if (KdKategori.ToString().Trim() != "")
                // {
                //     sql += "     and prg.kd_kategori = '" .KdKategori.ToString().Trim() ."'";
                // }

                if ($sFilter != "")
                {
                    $sql .= "WHERE " .$sFilter;
                }
                $sql .= " group by hdr.no_kwitansi, dtl.kd_program ";
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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "select sah, kd_agen, tgl,tgl_transaksi, hdr.no_kwitansi, hdr.nm_wakif, hdr.kd_pelanggan, nm_lengkap, alamat,telp,hp,email, hdr.total ";

        if ($sSumKolomProgram != "")
        {
            $q .= ",". $sSumKolomProgram;
        }

        $q .= " from tdonasi AS hdr "
        . " inner join mpelanggan plg"
        . " on hdr.kd_pelanggan = plg.kd_pelanggan ";
        if(!empty($program)) {
            $q .= " inner join ( ";
                $q .= $sql;
            $q .= " ) trn "
                . " on hdr.no_kwitansi = trn.no_kwitansi ";
        }
        if ($sFilter != "")
        {
            $q .= " WHERE " .$sFilter;
        }

        $q .= " group by sah,kd_agen,hdr.no_kwitansi, hdr.nm_wakif,tgl,tgl_transaksi, hdr.kd_pelanggan, nm_lengkap, alamat,telp,hp,email, hdr.total ";

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);

        // ------------------------------------------------------------------
        $nmJaringan = MGM::getNmJaringan($kdJaringan);
        $nmKas = MGM::getNmKas($kdKas);
        $huruf  = array_slice(range('K', 'Z'), 0, $barisProgram);
        $huruf = array();
        for($col = 'K'; $col!='AZ'; $col++) {
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
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        $sheet->getColumnDimension('J')->setWidth(13);
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

        $sheet->setCellValue('A'.$r, 'Rekapitulasi Dana Wakaf');
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

        $sheet->setCellValue('A'.$r, 'Pembayaran');
        $sheet->setCellValue('C'.$r, ': ' . $kdKas .' ['. trim($nmKas) .']');
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setSize(10);
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setBold(true);
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $sheet->setCellValue('A'.$r, 'Jaringan');
        $sheet->setCellValue('C'.$r, ': ' .$kdJaringan. ' [' . $nmJaringan.']');
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setSize(10);
        $sheet->getStyle('A'.$r.':C'.$r)->getFont()->setBold(true);
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $sheet->setCellValue('A'.$r, 'No');
        $sheet->setCellValue('B'.$r, 'Tgl. Stor');
        $sheet->setCellValue('C'.$r, 'Tgl. Transaksi');
        $sheet->setCellValue('D'.$r, 'No Kwitansi');
        $sheet->setCellValue('E'.$r, 'Nama Pendaftar');
        $sheet->setCellValue('F'.$r, 'Nama Wakif');
        $sheet->setCellValue('G'.$r, 'Alamat');
        $sheet->setCellValue('H'.$r, 'Telp');
        $sheet->setCellValue('I'.$r, 'Email');
        $sheet->setCellValue('J'.$r, 'Jumlah');

        $hr     = $barisProgram!=0 ? $huruf[$barisProgram-1] : 'C';
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if($barisProgram!=0) {
            $n = 0;
            foreach($arrKdProgram as $key=>$hdrProgram) {
                $hr     = $huruf[$n];
                $sheet->setCellValue($hr.$r, $hdrProgram);
                $n++;
            }
        }
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $no = 1;
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach($q as $row)
        {
            $sheet->setCellValue('A'.$r, $no);
            $sheet->setCellValue('B'.$r, substr($row['tgl'],0,10));
            $sheet->setCellValue('C'.$r, substr($row['tgl_transaksi'],0,10));
            $sheet->setCellValue('D'.$r, $row['no_kwitansi']);
            $sheet->setCellValue('E'.$r, $row['nm_lengkap']);
            $sheet->setCellValue('F'.$r, $row['nm_wakif']);
            $sheet->setCellValue('G'.$r, $row['alamat']);
            $sheet->setCellValue('H'.$r, $row['hp']);
            $sheet->setCellValue('I'.$r, $row['email']);
            $sheet->setCellValue('J'.$r, $row['total']);
            $sheet->getStyle('B'.$r.':I'.$r)->getAlignment()->setWrapText(true);
            $sheet->getStyle('J'.$r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('A'.$r.':J'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            $arrHuruf = array();
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $hr     = $huruf[$i];
                $sheet->setCellValue($hr.$r, $row['kol'.$i]);
                $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                $arrHuruf[] = $hr;
                $donasiPerProgram[$i] += $row['kol'.$i];
            }
            $no++;
            $r++;
            $totalDonasi += $row['total'];
        }
        // dd($donasiPerProgram);

        $sheet->getStyle('B'.$r)->getNumberFormat()->setFormatCode('DD/MM/YYYY');
        $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('DD/MM/YYYY');
        $sheet->getStyle('F'.$r)->getAlignment()->setWrapText(true);
        $sheet->getStyle('G'.$r)->getAlignment()->setWrapText(true);
        $sheet->getStyle('I'.$r)->getAlignment()->setWrapText(true);

        $sheet->setCellValue('B'.$r, 'TOTAL :');
        $sheet->setCellValue('J'.$r, $totalDonasi);
        $sheet->getStyle('J'.$r)->getNumberFormat()->setFormatCode('#,##0');

        for ($k=0; $k < count($donasiPerProgram) ; $k++) {
            $hr     = $huruf[$k];
            $sheet->setCellValue($hr.$r, $donasiPerProgram[$k]);
            $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
        }
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //==== end TOTAL ===========================================

        $fileName = "Donasi";
        $type ='xlsx';
        $sheet->setTitle($fileName);

        $fileName = $fileName.date('Y',strtotime($tglDr)).'-'.date('n',strtotime($tglDr)).''.date('j',strtotime($tglDr)).'-'.date('j',strtotime($tglSd)).'.'.$type;
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/')."/".$fileName);
    }

    //DONASI PER REKENING
    public function donasiPerRekening(Request $req)
    {
        $app['judul']   = "Daftar Donasi Per Rekening";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        $app['program']     = MGM::getProgram(false,false);
        $app['jaringan']    = MGM::getJaringan(true,true,'');
        return view('pages.laporan.laporandonasiperrekening', $app);
    }

    public function getLapDonasiPerRekening(Request $req)
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

        $kdCabang   = trim($req->kdCabang);
        $kdProgram  = $req->kdProgram;
        $kdJaringan = trim($req->kdJaringan);
        $kdKas      = trim($req->kdKas);
        $jenisPeriode= trim($req->jenisPeriode);

        $sFilter            = "";
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

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold" style="padding-left:0.5rem;">#</th>
            <th class="padat-kecil fw-bold">No. Rekening</th>
            <th class="padat-kecil fw-bold angka">Jumlah</th>';

            if($barisProgram!=0) {
                foreach($arrKdProgram as $hdrProgram) {
                    $output .='<th class="padat-kecil fw-bold angka">'. $hdrProgram .'</th>';
                }
            }
        $output .='</tr>
        </thead>
        <tbody>';

        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $sFilter = " tgl >= ".DB::raw("'".$tglDr."'");
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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "SELECT hdr.kd_kas, nm_kas AS nm_rekening, SUM(hdr.total) AS total ";

        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }

        $q .= " FROM tdonasi AS hdr "
            . " INNER JOIN mkas AS kas "
            . " ON hdr.kd_kas = kas.kd_kas ";
        if(!empty($kdProgram)) {
            $q .= " INNER JOIN "
                ." ( ";
            $q .= " SELECT kd_kas ";
            if ($sSumKolomProgram != "")
            {
                $q .= ",". $sSumKolomProgram;
            }
            $q .= " FROM"
                ." ( "
                ." SELECT hdr.kd_kas ";

            if($sKolomProgram!="")
            {
                $q .= ','.$sKolomProgram;
            }

            $q .= " FROM tdonasi AS hdr "
            ." INNER JOIN tdonasi_dtl dtl"
            ." on hdr.no_kwitansi = dtl.no_kwitansi "
            ." INNER JOIN mprogram prg "
            ." on dtl.kd_program = prg.kd_program ";

            // if ($KdKategori != "")
            // {
            //     $q .= " and prg.kd_kategori = '". $KdKategori ."'";
            // }

            if ($sFilter != "")
            {
                $q .= "WHERE " .$sFilter;
            }

            $q .= " group by hdr.kd_kas,dtl.kd_program "
            .") tmp "
            ." group by kd_kas "
            .") trn "
            ." on hdr.kd_kas = trn.kd_kas ";
        }
        if ($sFilter != "")
        {
            $q .= "WHERE " .$sFilter;
        }

        $q .= " group by hdr.kd_kas, nm_kas ";
        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }
        $q .= " order by nm_kas ";

        $jmh = DB::select($q);
        if(count($jmh)) {
            $q .= " limit ".$limit." offset ".$limit_start;
        }

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        //->orderBy('hdr.id', $sort)
        $total_records = count($jmh);

        //Total Donasi Per Rekening ------------------
        $qTotal = " SELECT ";
                    if ($sSumKolomProgram != "")
                    {
                        $qTotal .= $sSumKolomProgram;
                    }
        $qTotal .= " FROM ( "
                    . " SELECT ";
                        if($sKolomProgram!="")
                        {
                            $qTotal .= $sKolomProgram;
                        }
        $qTotal .= " FROM tdonasi AS hdr "
                . " INNER JOIN tdonasi_dtl AS dtl "
                . " ON hdr.no_kwitansi = dtl.no_kwitansi "
                . " INNER JOIN mprogram prg "
                . " ON dtl.kd_program = prg.kd_program ";
                if ($sFilter != "")
                {
                    $qTotal .= " WHERE " .$sFilter;
                }
        $qTotal .= " GROUP BY dtl.kd_program  "
                . " ) tmp ";

        $qTotal = DB::select($qTotal);
        //End Total Donasi Per Rekening ------------------

        $kelas_baris_akhir ='';
        $tr = '';
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil" style="padding-left:0.5rem;">'. $no .'</td>
              <td class="padat-kecil id-link">'. $row['nm_rekening'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['total'],0,',','.') .'</td>';
              for ($i = 0; $i < $barisProgram; $i++)
              {
                $tr .='<td class="padat-kecil angka">'. number_format($row['kol'.$i],0,',','.') .'</td>';
                $donasiPerProgram[$i] += $row['kol'.$i];
            }
            $tr .='</tr>';
            $no++;
            $totalDonasi += $row['total'];

            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="2">TOTAL</td>';
        foreach ($qTotal as $row) {
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalDonasi,0,',','.') .'</td>';
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $r = (array)$row;
                $tr .='<td class="padat-kecil angka fw-bold">'. number_format($r['kol'.$i],0,',','.') .'</td>';
            }
          }
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

    public function donasiPerRekeningExportXls(Request $req)
    {
        $barisProgram = 0;

        $tglDr      = $req->query('tglDr');
        $tglSd      = $req->query('tglSd');
        $tglSd      = ($tglSd == '') ? $tglDr:$tglSd;

        $kdCabang   = $req->query('kdCabang');
        $getProgram  = $req->query('kdProgram');
        $kdProgram  = explode(',', $getProgram);
        $kdJaringan = $req->query('kdJaringan');
        $kdKas      = $req->query('kdKas');
        $jenisPeriode= $req->query('jenisPeriode');

        $sFilter            = "";
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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "SELECT hdr.kd_kas, nm_kas AS nm_rekening, SUM(hdr.total) AS total ";

        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }

        $q .= " FROM tdonasi AS hdr "
            . " INNER JOIN mkas AS kas "
            . " ON hdr.kd_kas = kas.kd_kas ";
        if(!empty($kdProgram)) {
            $q .= " INNER JOIN "
                ." ( ";
            $q .= " SELECT kd_kas ";
            if ($sSumKolomProgram != "")
            {
                $q .= ",". $sSumKolomProgram;
            }
            $q .= " FROM"
                ." ( "
                ." SELECT hdr.kd_kas ";

            if($sKolomProgram!="")
            {
                $q .= ','.$sKolomProgram;
            }

            $q .= " FROM tdonasi AS hdr "
            ." INNER JOIN tdonasi_dtl dtl"
            ." on hdr.no_kwitansi = dtl.no_kwitansi "
            ." INNER JOIN mprogram prg "
            ." on dtl.kd_program = prg.kd_program ";

            // if ($KdKategori != "")
            // {
            //     $q .= " and prg.kd_kategori = '". $KdKategori ."'";
            // }

            if ($sFilter != "")
            {
                $q .= "WHERE " .$sFilter;
            }

            $q .= " group by hdr.kd_kas,dtl.kd_program "
            .") tmp "
            ." group by kd_kas "
            .") trn "
            ." on hdr.kd_kas = trn.kd_kas ";
        }
        if ($sFilter != "")
        {
            $q .= "WHERE " .$sFilter;
        }

        $q .= " group by hdr.kd_kas, nm_kas ";
        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }
        $q .= " order by nm_kas ";

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);

        //Total Donasi Per Rekening ------------------
        $qTotal = " SELECT ";
        if ($sSumKolomProgram != "")
        {
            $qTotal .= $sSumKolomProgram;
        }
        $qTotal .= " FROM ( "
        . " SELECT ";
            if($sKolomProgram!="")
            {
                $qTotal .= $sKolomProgram;
            }
        $qTotal .= " FROM tdonasi AS hdr "
        . " INNER JOIN tdonasi_dtl AS dtl "
        . " ON hdr.no_kwitansi = dtl.no_kwitansi "
        . " INNER JOIN mprogram prg "
        . " ON dtl.kd_program = prg.kd_program ";
        if ($sFilter != "")
        {
        $qTotal .= " WHERE " .$sFilter;
        }
        $qTotal .= " GROUP BY dtl.kd_program  "
        . " ) tmp ";
        // dd($qTotal);
        $qTotal = DB::select($qTotal);
        //End Total Donasi Per Rekening ------------------

        // ------------------------------------------------------------------
        $huruf  = array_slice(range('K', 'Z'), 0, $barisProgram);
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

        $sheet->setCellValue('A'.$r, 'Rekapitulasi Dana Wakaf Per Jaringan');
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
        $sheet->setCellValue('B'.$r, 'No. Rekening');
        $sheet->setCellValue('C'.$r, 'Jumlah');

        $hr     = $barisProgram!=0 ? $huruf[$barisProgram-1] : 'C';
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        if($barisProgram!=0) {
            $n = 0;
            foreach($arrKdProgram as $key=>$hdrProgram) {
                $hr     = $huruf[$n];
                $sheet->setCellValue($hr.$r, $hdrProgram);
                $n++;
            }
        }
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $no = 1;
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach($q as $row)
        {
            $sheet->setCellValue('A'.$r, $no);
            $sheet->setCellValue('B'.$r, $row['nm_rekening']);
            $sheet->setCellValue('C'.$r, $row['total']);
            $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('B'.$r)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('C'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            $arrHuruf = array();
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $hr     = $huruf[$i];
                $sheet->setCellValue($hr.$r, $row['kol'.$i]);
                $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                $arrHuruf[] = $hr;
                $donasiPerProgram[$i] += $row['kol'.$i];
            }
            $no++;
            $r++;
            $totalDonasi += $row['total'];
        }
        // dd($donasiPerProgram);

        $sheet->getStyle('C'.$r)->getAlignment()->setWrapText(true);
        $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->setCellValue('B'.$r, 'TOTAL :');
        $sheet->setCellValue('C'.$r, $totalDonasi);

        for ($k=0; $k < count($donasiPerProgram) ; $k++) {
            $hr     = $huruf[$k];
            $sheet->setCellValue($hr.$r, $donasiPerProgram[$k]);
            $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
        }
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //==== end TOTAL ===========================================

        $fileName = "Donasi per Rekening";
        $type ='xlsx';
        $sheet->setTitle($fileName);

        $fileName = $fileName.date('Y',strtotime($tglDr)).'-'.date('n',strtotime($tglDr)).''.date('j',strtotime($tglDr)).'-'.date('j',strtotime($tglSd)).'.'.$type;
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/')."/".$fileName);
    }

    //DONASI PER JARINGAN
    public function donasiPerJaringan(Request $req)
    {
        $app['judul']   = "Daftar Donasi Per Jaringan";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        $app['program']     = MGM::getProgram(false,false);
        return view('pages.laporan.laporandonasiperjaringan', $app);
    }

    public function getLapDonasiPerJaringan(Request $req)
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

        $kdCabang   = trim($req->kdCabang);
        $kdProgram  = $req->kdProgram;
        $kdJaringan = trim($req->kdJaringan);
        $kdKas      = trim($req->kdKas);
        $jenisPeriode= trim($req->jenisPeriode);

        $sFilter            = "";
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

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold">#</th>
            <th class="padat-kecil fw-bold">Jaringan</th>
            <th class="padat-kecil fw-bold angka">Jumlah</th>';

            if($barisProgram!=0) {
                foreach($arrKdProgram as $hdrProgram) {
                    $output .='<th class="padat-kecil fw-bold angka">'. $hdrProgram .'</th>';
                }
            }
        $output .='</tr>
        </thead>
        <tbody>';

        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $sFilter = " tgl >= ".DB::raw("'".$tglDr."'");
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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "SELECT hdr.kd_agen, nm_agen, SUM(hdr.total) AS total ";

        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }

        $q .= " FROM tdonasi AS hdr "
            . " INNER JOIN magen AS agen "
            . " ON hdr.kd_agen = agen.kd_agen ";
        if(!empty($kdProgram)) {
            $q .= " INNER JOIN "
                ." ( ";
            $q .= " SELECT kd_agen ";
            if ($sSumKolomProgram != "")
            {
                $q .= ",". $sSumKolomProgram;
            }
            $q .= " FROM"
                ." ( "
                ." SELECT hdr.kd_agen ";

            if($sKolomProgram!="")
            {
                $q .= ','.$sKolomProgram;
            }

            $q .= " FROM tdonasi AS hdr "
            ." INNER JOIN tdonasi_dtl dtl"
            ." on hdr.no_kwitansi = dtl.no_kwitansi "
            ." INNER JOIN mprogram prg "
            ." on dtl.kd_program = prg.kd_program ";

            // if ($KdKategori != "")
            // {
            //     $q .= " and prg.kd_kategori = '". $KdKategori ."'";
            // }

            if ($sFilter != "")
            {
                $q .= "WHERE " .$sFilter;
            }

            $q .= " group by hdr.kd_agen,dtl.kd_program "
            .") tmp "
            ." group by kd_agen "
            .") trn "
            ." on hdr.kd_agen = trn.kd_agen ";
        }
        if ($sFilter != "")
        {
            $q .= "WHERE " .$sFilter;
        }

        $q .= " group by hdr.kd_agen, nm_agen ";
        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }
        $q .= " order by nm_agen ";

        $jmh = DB::select($q);
        if(count($jmh)) {
            $q .= " limit ".$limit." offset ".$limit_start;
        }

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        //->orderBy('hdr.id', $sort)
        $total_records = count($jmh);

        //Total Donasi Per Jaringan ------------------
        $qTotal = " SELECT ";
                    if ($sSumKolomProgram != "")
                    {
                        $qTotal .= $sSumKolomProgram;
                    }
        $qTotal .= " FROM ( "
                    . " SELECT ";
                        if($sKolomProgram!="")
                        {
                            $qTotal .= $sKolomProgram;
                        }
        $qTotal .= " FROM tdonasi AS hdr "
                . " INNER JOIN tdonasi_dtl AS dtl "
                . " ON hdr.no_kwitansi = dtl.no_kwitansi "
                . " INNER JOIN mprogram prg "
                . " ON dtl.kd_program = prg.kd_program ";
                if ($sFilter != "")
                {
                    $qTotal .= " WHERE " .$sFilter;
                }
        $qTotal .= " GROUP BY dtl.kd_program  "
                . " ) tmp ";

        $qTotal = DB::select($qTotal);
        //End Total Donasi Per Jaringan ------------------

        $kelas_baris_akhir ='';
        $tr = '';
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. $no .'</td>
              <td class="padat-kecil id-link">'. $row['nm_agen'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['total'],0,',','.') .'</td>';
              for ($i = 0; $i < $barisProgram; $i++)
              {
                $tr .='<td class="padat-kecil angka">'. number_format($row['kol'.$i],0,',','.') .'</td>';
                $donasiPerProgram[$i] += $row['kol'.$i];
              }
            $tr .='</tr>';
            $no++;
            $totalDonasi += $row['total'];

            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="2">TOTAL</td>';
        foreach ($qTotal as $row) {
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalDonasi,0,',','.') .'</td>';
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $r = (array)$row;
                $tr .='<td class="padat-kecil angka fw-bold">'. number_format($r['kol'.$i],0,',','.') .'</td>';
            }
          }
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

    public function donasiPerJaringanExportXls(Request $req)
    {
        $barisProgram = 0;

        $tglDr      = $req->query('tglDr');
        $tglSd      = $req->query('tglSd');
        $tglSd      = ($tglSd == '') ? $tglDr:$tglSd;

        $kdCabang   = $req->query('kdCabang');
        $getProgram  = $req->query('kdProgram');
        $kdProgram  = explode(',', $getProgram);
        $kdJaringan = $req->query('kdJaringan');
        $kdKas      = $req->query('kdKas');
        $jenisPeriode= $req->query('jenisPeriode');

        $sFilter            = "";
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
                if ($sFilter != "")
                {
                    $sFilter .= " AND ";
                }
                $sFilter .= " kd_cabang = ".$kdCabang;
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

        $q = "SELECT hdr.kd_agen, nm_agen, SUM(hdr.total) AS total ";

        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }

        $q .= " FROM tdonasi AS hdr "
            . " INNER JOIN magen AS agen "
            . " ON hdr.kd_agen = agen.kd_agen ";
        if(!empty($kdProgram)) {
            $q .= " INNER JOIN "
                ." ( ";
            $q .= " SELECT kd_agen ";
            if ($sSumKolomProgram != "")
            {
                $q .= ",". $sSumKolomProgram;
            }
            $q .= " FROM"
                ." ( "
                ." SELECT hdr.kd_agen ";

            if($sKolomProgram!="")
            {
                $q .= ','.$sKolomProgram;
            }

            $q .= " FROM tdonasi AS hdr "
            ." INNER JOIN tdonasi_dtl dtl"
            ." on hdr.no_kwitansi = dtl.no_kwitansi "
            ." INNER JOIN mprogram prg "
            ." on dtl.kd_program = prg.kd_program ";

            // if ($KdKategori != "")
            // {
            //     $q .= " and prg.kd_kategori = '". $KdKategori ."'";
            // }

            if ($sFilter != "")
            {
                $q .= "WHERE " .$sFilter;
            }

            $q .= " group by hdr.kd_agen,dtl.kd_program "
            .") tmp "
            ." group by kd_agen "
            .") trn "
            ." on hdr.kd_agen = trn.kd_agen ";
        }
        if ($sFilter != "")
        {
            $q .= "WHERE " .$sFilter;
        }

        $q .= " group by hdr.kd_agen, nm_agen ";
        if ($sKolom!="")
        {
            $q .= ",".$sKolom;
        }
        $q .= " order by nm_agen ";

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);

        //Total Donasi Per Rekening ------------------
        $qTotal = " SELECT ";
        if ($sSumKolomProgram != "")
        {
            $qTotal .= $sSumKolomProgram;
        }
        $qTotal .= " FROM ( "
        . " SELECT ";
            if($sKolomProgram!="")
            {
                $qTotal .= $sKolomProgram;
            }
        $qTotal .= " FROM tdonasi AS hdr "
        . " INNER JOIN tdonasi_dtl AS dtl "
        . " ON hdr.no_kwitansi = dtl.no_kwitansi "
        . " INNER JOIN mprogram prg "
        . " ON dtl.kd_program = prg.kd_program ";
        if ($sFilter != "")
        {
        $qTotal .= " WHERE " .$sFilter;
        }
        $qTotal .= " GROUP BY dtl.kd_program  "
        . " ) tmp ";
        // dd($qTotal);
        $qTotal = DB::select($qTotal);
        //End Total Donasi Per Rekening ------------------

        // ------------------------------------------------------------------
        $huruf  = array_slice(range('K', 'Z'), 0, $barisProgram);
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

        $sheet->setCellValue('A'.$r, 'Rekapitulasi Dana Wakaf Per Jaringan');
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

        $hr     = $barisProgram!=0 ? $huruf[$barisProgram-1] : 'C';
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        if($barisProgram!=0) {
            $n = 0;
            foreach($arrKdProgram as $key=>$hdrProgram) {
                $hr     = $huruf[$n];
                $sheet->setCellValue($hr.$r, $hdrProgram);
                $n++;
            }
        }
        $sheet->getRowDimension($r)->setRowHeight(15);
        $r++;

        $no = 1;
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach($q as $row)
        {
            $sheet->setCellValue('A'.$r, $no);
            $sheet->setCellValue('B'.$r, $row['nm_agen']);
            $sheet->setCellValue('C'.$r, $row['total']);
            $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('B'.$r)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('C'.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            $arrHuruf = array();
            for ($i = 0; $i < $barisProgram; $i++)
            {
                $hr     = $huruf[$i];
                $sheet->setCellValue($hr.$r, $row['kol'.$i]);
                $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($hr.$r)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                $arrHuruf[] = $hr;
                $donasiPerProgram[$i] += $row['kol'.$i];
            }
            $no++;
            $r++;
            $totalDonasi += $row['total'];
        }

        $sheet->getStyle('C'.$r)->getAlignment()->setWrapText(true);
        $sheet->getStyle('C'.$r)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->setCellValue('B'.$r, 'TOTAL :');
        $sheet->setCellValue('C'.$r, $totalDonasi);

        for ($k=0; $k < count($donasiPerProgram) ; $k++) {
            $hr     = $huruf[$k];
            $sheet->setCellValue($hr.$r, $donasiPerProgram[$k]);
            $sheet->getStyle($hr.$r)->getNumberFormat()->setFormatCode('#,##0');
        }
        $sheet->getStyle('A'.$r.':'.$hr.$r)->getFont()->setBold(true);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$r.':'.$hr.$r)
            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //==== end TOTAL ===========================================

        $fileName = "Donasi per Jaringan";
        $type ='xlsx';
        $sheet->setTitle($fileName);

        $fileName = $fileName.date('Y',strtotime($tglDr)).'-'.date('n',strtotime($tglDr)).''.date('j',strtotime($tglDr)).'-'.date('j',strtotime($tglSd)).'.'.$type;
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/')."/".$fileName);
    }


    //DONASI PER FUNDRAISER
    public function donasiPerFundraiser(Request $req)
    {
        $app['judul']   = "Daftar Donasi Per Fundraiser";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        $app['program']     = MGM::getProgram(false,false);
        $app['sales']       = MGM::getSales(false,false,'');
        // dd($app['sales']);
        return view('pages.laporan.laporandonasiperfundraiser', $app);
    }

    public function getLapDonasiPerFundraiser(Request $req)
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

        $kdCabang   = trim($req->kdCabang);
        $kdProgram  = $req->kdProgram;
        $kdJaringan = trim($req->kdJaringan);
        $kdKas      = trim($req->kdKas);
        $kdSales    = $req->sales;
        $jenisPeriode= trim($req->jenisPeriode);

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

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold text-nowrap">#</th>
            <th class="padat-kecil fw-bold">Fundraiser</th>
            <th class="padat-kecil fw-bold angka">Jumlah</th>';
        $output .='</tr>
        </thead>
        <tbody>';

        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $sFilter = " tgl >= ".DB::raw("'".$tglDr."'");
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
            $q .= " WHERE " .$sWhere;
        }

        $q .= " group by sal.kd_sales ";
        $q .= " order by nm_sales ";
        // dd($q);
        $jmh = DB::select($q);
        if(count($jmh)) {
            $q .= " limit ".$limit." offset ".$limit_start;
        }

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        //->orderBy('hdr.id', $sort)
        $total_records = count($jmh);

        $kelas_baris_akhir ='';
        $tr = '';
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. $no .'</td>
              <td class="padat-kecil id-link">'. $row['nm_sales'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['total'],0,',','.') .'</td>';
            $tr .='</tr>';
            $no++;
            $totalDonasi += $row['total'];

            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="2">TOTAL</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalDonasi,0,',','.') .'</td>';
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

    public function donasiPerFundraiserExportXls(Request $req)
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


    //DONASI PER PROJECT
    public function donasiPerProject(Request $req)
    {
        $app['judul']   = "Daftar Donasi Per Project";
        $app['alurDonasi']  = MGM::getAlurDonasi(session('RoleEntriDonasi'),true);
        $app['kas']         = MGM::getKas(true,true);
        $app['cabang']      = MGM::getCabang(session('UserRoleCabang'),true);
        $app['program']     = MGM::getProgram(false,true);
        $app['project']    = MGM::getProject(true,true,'');
        return view('pages.laporan.laporandonasiperproject', $app);
    }

    public function getLapDonasiPerProject(Request $req)
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

        $kdCabang   = trim($req->kdCabang);
        $kdProgram  = $req->kdProgram;
        $kdJaringan = trim($req->kdJaringan);
        $kdKas      = trim($req->kdKas);
        $kdSales    = $req->sales;
        $jenisPeriode= trim($req->jenisPeriode);

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
                if ($sKolom != "")
                {
                    $sKolom             = $sKolom." OR ";
                }

                $sKolom             = $sKolom." dtl.kd_program = '".$rdr['kd_program']."'";

                // //Tambah Kolom
                // tbl.Columns.Add("d" .$barisProgram.ToString(), typeof(Decimal));

                $barisProgram++;
                $arrKdProgram[$barisProgram] = $rdr['kd_program'];
            }
        }
        // end get Program ----------------------------------------

        $output ='
        <table class="table table-bordered table-striped card-table table-vcenter border-bottom" width="100%">
        <thead>
          <tr class="border-top">
            <th class="padat-kecil fw-bold text-nowrap">#</th>
            <th class="padat-kecil fw-bold">Project</th>
            <th class="padat-kecil fw-bold angka">Jumlah</th>';
        $output .='</tr>
        </thead>
        <tbody>';

        if($tglDr!='')
        {
            if ($jenisPeriode =='PeriodeSetor')
            {
                $sFilter = " tgl >= ".DB::raw("'".$tglDr."'");
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
                $sWhere = " prj.kd_cabang = ".$kdCabang;
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

        $q = "SELECT prj.kd_project, prj.nm_project, IFNULL(SUM(hdr.total),0)total ";

        $q .= " FROM mproject prj "
            . " LEFT JOIN tdonasi_dtl AS dtl "
            . " ON prj.kd_project = dtl.kd_project "
            . " INNER JOIN tdonasi AS hdr "
            . " ON dtl.no_kwitansi = hdr.no_kwitansi ";

        if ($sFilter != "")
        {
            $q .= "AND " .$sFilter;
        }

        if ($sKolom != "")
        {
            $q .= " WHERE " .$sKolom;
        }

        $q .= " GROUP BY prj.kd_project ";
        $q .= " ORDER BY nm_project ";
        // dd($q);
        $jmh = DB::select($q);
        if(count($jmh)) {
            $q .= " limit ".$limit." offset ".$limit_start;
        }

        $q = DB::select($q);
        $q = json_decode(json_encode($q), true);
        //->orderBy('hdr.id', $sort)
        $total_records = count($jmh);

        $kelas_baris_akhir ='';
        $tr = '';
        $totalDonasi = 0;
        $donasiPerProgram = array();
        for ($i = 0; $i < $barisProgram; $i++)
        {
            $donasiPerProgram[$i] = 0;
        }
        foreach ($q as $row) {
            $tr .= '
            <tr ' . $kelas_baris_akhir .'>
              <td class="padat-kecil">'. $no .'</td>
              <td class="padat-kecil id-link">'. $row['nm_project'] .'</td>
              <td class="padat-kecil angka">'. number_format($row['total'],0,',','.') .'</td>';
            $tr .='</tr>';
            $no++;
            $totalDonasi += $row['total'];

            if ($no==($limit_start .$limit))
            {
                $kelas_baris_akhir = 'class="border-bottom"';
            }
        }

        $tr .= '
        <tr ' . $kelas_baris_akhir .'>
          <td class="padat-kecil" colspan="2">TOTAL</td>';
            $tr .='<td class="padat-kecil angka fw-bold">'. number_format($totalDonasi,0,',','.') .'</td>';
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

    public function donasiPerProjectExportXls(Request $req)
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
                if ($sKolom != "")
                {
                    $sKolom             = $sKolom." OR ";
                }

                $sKolom             = $sKolom." dtl.kd_program = '".$rdr['kd_program']."'";

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

        $q = "SELECT prj.kd_project, prj.nm_project, IFNULL(SUM(hdr.total),0)total ";

        $q .= " FROM mproject prj "
            . " LEFT JOIN tdonasi_dtl AS dtl "
            . " ON prj.kd_project = dtl.kd_project "
            . " INNER JOIN tdonasi AS hdr "
            . " ON dtl.no_kwitansi = hdr.no_kwitansi ";

        if ($sFilter != "")
        {
            $q .= "AND " .$sFilter;
        }

        if ($sKolom != "")
        {
            $q .= " WHERE " .$sKolom;
        }

        $q .= " GROUP BY prj.kd_project ";
        $q .= " ORDER BY nm_project ";
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

        $sheet->setCellValue('A'.$r, 'Rekapitulasi Dana Wakaf Per Project');
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
        $sheet->setCellValue('B'.$r, 'Project');
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
            $sheet->setCellValue('B'.$r, $row['nm_project']);
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

        $fileName = "Donasi per Project";
        $type ='xlsx';
        $sheet->setTitle($fileName);

        $fileName = $fileName.date('Y',strtotime($tglDr)).'-'.date('n',strtotime($tglDr)).''.date('j',strtotime($tglDr)).'-'.date('j',strtotime($tglSd)).'.'.$type;
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/')."/".$fileName);
    }
}
