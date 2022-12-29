<?php

namespace App;
use Illuminate\Support\Facades\DB;

class GL{

    public static function getJenisAkun(){
        $tbl = "ac_sys_jn_akun";

        $res =  DB::table($tbl)
        ->select('kd_jenis', 'nm_jenis')
        ->get();

        return $res;
    }

    public static function getGolAkun($kdJenisAkun=''){
        $tbl = "ac_sys_gol_akun";
        $kd = 'kd_gol';
        $nm = 'nm_gol';

        $res =  DB::table($tbl)
        ->select($kd, $nm);
        if($kdJenisAkun!='')
        {
            $res = $res->where('kd_jenis',$kdJenisAkun);
        }
        $res = $res->orderBy('no_urut')
        ->get();

        return $res;
    }

    public static function getTurunan($kdInduk){
        $tbl = "ac_makun";
        $kd = 'turunan';
        $nm = '';

        $res =  DB::table($tbl)
        ->select($kd)
        ->where('kd_akun',$kdInduk)
        ->value('turunan');

        return $res;
    }

    public static function getProgram(){
        $tbl = "mprogram";

        $res =  DB::table($tbl)
        ->select('kd_program', 'nm_program')
        ->orderBy('nm_program')
        ->get();

        return $res;
    }

    public static function getProject(){
        $tbl = "mproject";
        $kd = 'kd_project';
        $nm = 'nm_project';

        $res =  DB::table($tbl)
        ->select($kd, $nm)
        ->orderBy($nm)
        // if($kdProgram!='')
        // {
        //     $res = $res->where('kd_program',$kdProgram);
        // }
        // $res = $res->orderBy('no_urut')
        ->get();

        return $res;
    }

    public static function getNmProgram($kd){
        $tbl = "mprogram";

        $res =  DB::table($tbl)
        ->where('kd_program',$kd)
        ->value('nm_program');

        return $res;
    }

    public static function getNmProject($kd){
        $tbl = "mproject";

        $res =  DB::table($tbl)
        ->where('kd_project',$kd)
        ->value('nm_project');

        return $res;
    }
    

}