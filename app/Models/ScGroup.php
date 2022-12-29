<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScGroup extends Model
{
    use HasFactory;

    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "sc_group";
    protected $primaryKey = 'kd_group';
    protected $keyType = 'string';
    protected $fillable = ['kd_group','nm_group','uid','uid_edit'];
}
