<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfBank extends Model
{
    use HasFactory;

    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "rf_bank";
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    protected $fillable = ['bank','alias','urutan','aktif','uid','uid_edit'];
}
