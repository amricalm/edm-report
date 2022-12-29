<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MPropinsi extends Model
{
    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "rf_propinsi";
    protected $primaryKey = 'kd_propinsi';
    protected $fillable = ['nm_propinsi','uid','uid_edit'];
}
