<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Adn;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $tbl;
    public function __construct()
    {
        $this->tbl = '';
    }

    public function test()
    {   
       
        //$response = Http::get('http://www.andhana.com/jakarta/');
        //dd($response);
    
        $response = Http::post(
            'https://waba.ivosights.com/api/v1/messages/send-text-message',
            [
                'headers' => [
                    'X-API-KEY' => '8m0iD621f358a0f4fd1.26261265WsQu',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'wa_id' => '628128639653',
                    'text' => 'hello',
                ],
            ]
        );
        $body = $response->getBody();
        dd(json_decode((string) $body));

        return view('pages.notifikasi', $app);
    }

    public function index()
    {   
       
        $response = Http::get('https://www.republika.co.id/');
        dd($response);
    
        // $response = Http::post(
        //     'https://waba.ivosights.com/api/v1/messages/send-text-message',
        //     [
        //         'headers' => [
        //             'X-API-KEY' => '8m0iD621f358a0f4fd1.26261265WsQu',
        //             'Accept' => 'application/json',
        //         ],
        //         'json' => [
        //             'wa_id' => '628128639653',
        //             'text' => 'hello',
        //         ],
        //     ]
        // );
        // $body = $response->getBody();
        // dd(json_decode((string) $body));

        return view('pages.notifikasi', $app);
    }



}
