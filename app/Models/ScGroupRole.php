<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScGroupRole extends Model
{
    use HasFactory;

    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "sc_group_role";
    protected $primaryKey = ['kd_obj', 'kd_group'];
    protected $keyType = 'string';
    protected $fillable = ['baca','tambah','edit','hapus','role_entri','role_cabang','uid','uid_edit'];
}
