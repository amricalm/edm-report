<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDonasiDtl extends Model
{
    protected $table = "tdonasi_dtl";
    protected $primaryKey = 'no_kwitansi';
    public $timestamps = false;
    protected $fillable = ['kd','kd_program','kd_project','qty','jmh','fid_program','fid_sub_program','fqty','fharga','frealisasi','fid_detail','sumber'];
}
