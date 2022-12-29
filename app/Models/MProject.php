<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProject extends Model
{
    use HasFactory;


    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "mproject";
    protected $primaryKey = 'kd_project';
    protected $keyType = 'string';
    protected $fillable = ['kd_project','nm_project','tgl_mulai', 'ditutup','aktif','uid','uid_edit'];
}
