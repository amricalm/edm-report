<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\VarGlobal;
use App\Adn;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\User;
use Exception;


class AmanController extends Controller
{
    public function index()
    {
        if (!empty(session('UserID'))) {
            return redirect()->route('beranda');
        } else {
            return view('pages/aman');
        }
    }

    public function validasi(Request $request)
    {
        $username = trim($request->username);
        $password = trim($request->password);
        $lst = Adn::getScPengguna($username,$password);

        if($lst->count()>0)
        {
            $scPengguna = $lst[0];
            if($scPengguna->aktif == '1')
            {
                $sess['UserID'] = $username;
                $sess['UserLogin'] = $username;
                $sess['UserGroup'] = trim($scPengguna->kd_group);
                $sess['TampilBarisTabel'] = Adn::getSysVar('TampilBarisTabel');

                $sess['UserRoleObj'] = Adn::getScRoleObj('MgmDonasi',$sess['UserGroup']);
                $sess['UserRoleCabang'] = Adn::getScRoleCabang($sess['UserGroup']);

                $objRoleDonasi = $sess['UserRoleObj']->firstWhere('kd_obj', 'FDonasi');
                //$sess['RoleEntriDonasi'] = $objRoleDonasi->role_entri;
                session($sess);
                return redirect()->route('beranda');
            }
            else
            {
                return Redirect::back()->withErrors(['msg' => 'Username Tidak Aktif!']);
            }
        }
        return Redirect::back()->withErrors(['msg' => 'Username atau Password Salah!']);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('aman');
    }
}
