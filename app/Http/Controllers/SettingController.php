<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adn;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $tbl;
    public function __construct()
    {
        $this->tbl = 'sys_val';
    }

    public function index()
    {   
        $time = strtotime(session('PeriodeMulai'));

        $app['judul'] = 'Pengaturan';
        $app['periodeMulai'] =  date('Y-m-d',$time) ;
        $app['tampilBaris']=session('TampilBarisTabel');
        return view('pages.setting.index', $app);
    }


    /**
     * Store value in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        try {
            Adn::setSysVar('TampilBarisTabel',$req->tampilBaris);
            session()->put('TampilBarisTabel',$req->tampilBaris);
            
            // Adn::setSysVar('periode_mulai',$req->periodeMulai);
            // session()->put('PeriodeMulai',$req->periodeMulai);

            $response= Adn::Response(true,"Sukses");
            
        }
        catch(\PDOException $e)
        {
            $response= Adn::Response(false,$e->getMessage());
        }
        catch (\Error $e) {
            $response= Adn::Response(false,$e->getMessage());
        }
        //$r = Adn::Response(true,"Test");
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
