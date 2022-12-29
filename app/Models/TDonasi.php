<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDonasi extends Model
{
    const CREATED_AT = 'tgl_tambah';
    const UPDATED_AT = 'tgl_edit';

    protected $table = "tdonasi";
    protected $primaryKey = 'no_kwitansi';
    protected $fillable = ['id','nm_wakif','kd_kas','kd_pelanggan','kd_agen','tgl','total','sah','kd_tkm','ket','tgl_transaksi','kd_sales','posting','sumber','kd_cabang','alur_kerja','biaya_bank','konfirmasi','tgl_konfirmasi','catatan_konfirmasi','update_project','uid','uid_edit']; 
}
