<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAkun extends Model
{
    use HasFactory;


    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "ac_makun";
    protected $primaryKey = 'kd_akun';
    protected $fillable = ['nm_akun','kd_induk','turunan','klasifikasi','dk','kd_gol','kd_dept','anggaran','ket','aktif','uid','uid_edit'];
}
