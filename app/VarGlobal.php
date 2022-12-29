<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VarGlobal extends Model
{
    //Admin
    const ADMIN                 = 10;
    const DONATUR_DIABAIKAN     = 0;
    const DONATUR_AKTIF         = 1;
    const DONATUR_TIDAK_AKTIF   = 9;

    public function bulan($month)
    {
        $bulan = array(
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        );
        return ($month=='') ? $bulan : $bulan[$month];
    }
    public function romawi($angka='')
    {
        $romawi = array('1'=>"I",'2'=>"II",'3'=>"III",'4'=>"IV",'5'=>"V",'6'=>"VI",'7'=>"VII",'8'=>"VIII",'9'=>"IX",'10'=>"X",'11'=>"XI",'12'=>"XII");
        return ($angka=='') ? $romawi : $romawi[$angka];
    }
    public function iconfile($ext='')
    {
        $icon = array(
                'jpg'=>'fa fa-file-image-o',
                'gif'=>'fa fa-file-image-o',
                'png'=>'fa fa-file-image-o',
                'jpeg'=>'fa fa-file-image-o',
                'doc'=>'fa fa-file-word-o',
                'docx'=>'fa fa-file-word-o',
                'ppt'=>'fa fa-file-powerpoint-o',
                'pptx'=>'fa fa-file-powerpoint-o',
                'xls'=>'fa fa-file-excel-o',
                'xlsx'=>'fa fa-file-excel-o',
                'zip'=>'fa fa-file-zip-o',
                'rar'=>'fa fa-file-zip-o',
                'pdf'=>'fa fa-file-pdf-o',
                'txt'=>'fa fa-file-text',
                'other'=>'fa fa-file',
            );

        if($ext!='')
        {
            if(array_key_exists($ext,$icon)==1)
            {
                return $icon[$ext];
            }
            else
            {
                return $icon['other'];
            }
        }
        else
        {
            return $icon;
        }
    }

   
    public function Slug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }


    public function build_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($b->$key, $a->$key);
        };
    }
    

}



