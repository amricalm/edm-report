<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKas extends Model
{
    use HasFactory;


    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "mkas";
    protected $primaryKey = 'kd_kas';
    protected $keyType = 'string';
    protected $fillable = ['nm_kas','kd_akun','bank_id','aktif','uid','uid_edit'];
}
