<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect()
    {
        $queries = http_build_query([
            'client_id'=> '3',
            'redirect_ui'=> 'http://localhost/client/public/oauth/callback',
            'response_type'=>'code',
            'scope'=>'user-scope posts-scope',
        ]);

        return redirect('http://127.0.0.1:8000/oauth/authorize?'.$queries);
    }

    public function callback(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8000/oauth/token',[
            'grant_type'=>'authorization_code',
            'client_id'=>'3',
            'client_secret'=>'kwFPLjUD26nuEXWYbesEIGRSzVLXcmQVu4jOdcRL',
            'code'=>$request->code,
            'redirect_ui'=>'http://localhost/client/public/oauth/callback'
        ]);

        $response = $response->json();

        $token =  $request->user()->token();
        $token->delete();

        $token->create([
            'access_token'=>$response['access_token'],
            'expire_in'=>$response['expires_in'],
            'refresh_token'=>$response['refresh_token']
        ]);

        return redirect('/home');

    }

    public function refresh(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8000/oauth/token',[
            'grant_type'=>'refresh_token',
            'refresh_token'=>$request->user()->token->refresh_token,
            'client_id'=>'3',
            'client_secret'=>'kwFPLjUD26nuEXWYbesEIGRSzVLXcmQVu4jOdcRL',
            'redirect_ui'=>'http://localhost/client/public/oauth/callback',
            'scope'=>'user-scope posts-scope',
        ]);

        $response = $response->json();

        $token =  $request->user()->token();

        $token->update([
            'access_token'=>$response['access_token'],
            'expire_in'=>$response['expires_in'],
            'refresh_token'=>$response['refresh_token']
        ]);

        return redirect('/home');
    }
}
