<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MDonatur extends Model
{
    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "mpelanggan";
    protected $primaryKey = 'kd_pelanggan';
    protected $keyType = 'string';
    protected $fillable = ['nm_lengkap','alamat','kota','pos','propinsi','telp','hp','email','aktif','status','uid','uid_edit']; 

    public static function getKd(){
        $sPrefix = Carbon::now()->format('y');
        $q = DB::select(DB::raw('select next value for pencacah_kd_donatur as kd'));
        $arr = json_decode(json_encode($q[0]), true);
        $seq = $arr['kd'];

        $hasil = $sPrefix . str_pad($seq,6,'0',STR_PAD_LEFT);

        return $hasil;
    }
}