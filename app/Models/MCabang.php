<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCabang extends Model
{
    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "mcabang";
    protected $primaryKey = 'ID';
    protected $fillable = ['Nm','Ket','uid','uid_edit'];
    

    
}
