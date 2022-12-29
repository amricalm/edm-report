<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAgen extends Model
{
    use HasFactory;


    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "magen";
    protected $primaryKey = 'kd_agen';
    protected $keyType = 'integer';
    protected $fillable = ['CabangID','nm_agen','alamat','kota','pos','kd_propinsi','telp','hp','email','ket','uid','uid_edit'];
}
