<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScPengguna extends Model
{
    use HasFactory;

    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "sc_pengguna";
    protected $primaryKey = 'nm_login';
    protected $keyType = 'string';
    protected $fillable = ['nm_login','kd_group','pwd','aktif','uid','uid_edit'];
}
