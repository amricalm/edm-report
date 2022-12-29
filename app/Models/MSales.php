<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSales extends Model
{
    use HasFactory;


    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "msales";
    protected $primaryKey = 'kd_sales';
    //protected $keyType = 'string';
    protected $fillable = ['kd_cabang','alias','nm_sales','kd_link','jenis','aktif','nm_login','uid','uid_edit'];

    public static function getByCabang($kdCabang='')//Fundraiser
    {
        $res = MSales::select('kd_sales','nm_sales')
            ->where('aktif',True);

        if (trim($kdCabang)!='')
        {   
            $res = $res->where('kd_cabang','=',$kdCabang);
        }

        $res = $res->orderBy('nm_sales')
            ->get();

        return $res;
    }


}
