<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProgram extends Model
{
    use HasFactory;

    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "mprogram";
    protected $primaryKey = 'kd_program';
    protected $keyType = 'string';
    protected $fillable = ['kd_program','nm_program','kd_akun_program','kd_akun_operasi','kd_akun_pembinaan','kd_kategori','alias','nilai','tgl_efektif','persen_program','persen_operasi','persen_pembinaan','stok','aktif','uid','uid_edit'];
}
