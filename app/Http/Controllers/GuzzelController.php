<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class GuzzelController extends Controller
{
    public function getRequest($url){
        $url = str_replace(".","/",$url);
//dd($url);
        $response = Http::get('http://localhost:8000'.$url);

        return $response;
    }
}
