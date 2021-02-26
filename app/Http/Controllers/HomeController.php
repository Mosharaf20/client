<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = [];

        $token = auth()->user()->token;

        if($token && $token->hasExpired()){
            return  redirect('/oauth/refresh');
        }

        if($token){
            $response = Http::withHeaders([
               'Accept'=>'application/json',
               'Authorization'=>'Bearer '.$token->access_token,
            ])->get('http://127.0.0.1:8000/api/posts');

            if($response->status() == 200){
                $posts =$response->json();

            }
        }

        return view('home',compact('posts'));
    }
}
