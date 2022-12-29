<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\VarGlobal;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('adn.auth');
    }

    public function index()
    {
        $app['judul'] = 'Dashboard';
        return view('pages.dashboard', $app);
    }
}
