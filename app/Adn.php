<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Adn{
    private $IsSuccess;
    private $Message;
    private $Obj;

    public static function Response($aSuccess,$aMessage,$aObj=null) {
        $paket['IsSuccess'] = $aSuccess;
        $paket['Message']= $aMessage;
        $paket['Obj'] = $aObj;
        return $paket;
    }

    public static function SetYMD($aDate) {
        $result = Date("Y-m-d",$aDate);
        return $result;
    }

    public static function setTglSd($aStrDate){
        $result =Date('Y-m-d', strtotime($aStrDate. ' + 1 days'));
        return $result;
    }
    
    public static function getSysVar($col){
        $result = DB::table('sys_var')
                ->where('sys_col','=',$col)
                ->value('sys_val');
        return $result;
    }

    public static function setSysVar($col,$val){
        $affected = DB::table('sys_var')
                    ->where('sys_col','=',$col)
                    ->update(['sys_val' => $val]);
        
        //DB::enableQueryLog();
        //Log::info(DB::getQueryLog());
        
        return $affected;
    }

    public static function setSortIcon($sort,$sortField, $kolom)
    {
        if($sortField==$kolom)
        {
            if($sort=='asc')
            {
                return 'fa fa-sort-asc';
            }
            else
            {
                return 'fa fa-sort-desc';
            }
        }
        else
        {
            return 'fa fa-sort';
        }
    }
    public static function setSortData($sort,$sortField, $kolom)
    {
        if($sortField==$kolom)
        {
            return $sort;
        }
        else
        {
            return '';
        }
    }
    public static function setSortAktif($sortField, $kolom)
    {
        if($sortField==$kolom)
        {
            return 'sortAktif';
        }
        else
        {
            return 'sort';
        }
    }
    public static function getScPengguna($nmLogin, $password)
    {
        $res = DB::table('sc_pengguna')
        ->where('nm_login', trim($nmLogin))
        ->where('pwd', trim($password))
        ->get();

        return $res; 
    }
    public static function getScRoleObj($lingkup,$kdGroup){
        $tbl = "sc_obj";

        $res =  DB::table($tbl)
            ->select('role.kd_obj', 'nm_obj', 'baca','role_entri' )
            ->join('sc_group_role as role','sc_obj.kd_obj','=','role.kd_obj')
            ->where('role.kd_group','=',$kdGroup);
        if ($lingkup!=''){
            $res = $res->where('lingkup','=',$lingkup);
        }
        $res= $res->get();
        return $res;
    }

    public static function getAksesMenu($koleksiScRoleObj, $kdObj){
        $akses = false;
        $res = $koleksiScRoleObj->firstWhere('kd_obj',$kdObj);

        if($res!=null)
        {
            $akses = $res->baca;
        }
        return $akses;
    }

    public static function getScRoleCabang($kdGroup){
        $tbl = "sc_group_data";
        $res =  DB::table($tbl)
            ->where('kd_group','=',$kdGroup)
            ->value('cabang');
        return $res;
    }

}