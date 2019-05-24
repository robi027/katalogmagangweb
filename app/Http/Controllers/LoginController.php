<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $pengguna = array('email' => $email, 'password' => $password);

        if(Auth::attempt($pengguna)){
            if(Auth::user()->level == 1){
                return redirect()->intended('/admin/dashboard');
            } elseif(Auth::user()->level == 2){
                return redirect()->intended('/pj/dashboard');
            }
        }else{
            return redirect('login');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect('login');
    }

    public function tidakdipakai(Request $request){
        $pengguna = array();
        $lvlAdmin = 1;
        $lvlPJ = 2;

        $email = $request->input('email');
        $password = $request->input('password');

        // $queryPJ = Pengguna::where(['email' => $email, 
        // 'password' => $password, 'level' => $lvlPJ])->get();

        // $queryAdmin = Pengguna::where(['email' => $email, 
        // 'password' => $password, 'level' => $lvlAdmin])->get();

        $pengguna = array('email' => $email, 'password' => $password);

        
        if(Auth::attempt($pengguna)){
            return redirect()->intended('/pj/dashboard');
        }else{
            return 'login';
        }

        // if(count($queryPJ) > 0){
        //     $pengguna = array('email' => $email, 'password' => $password);
        //     // Auth::guard('web')->loginUsingId($queryPJ[0]->id);
        //     // dd(Auth::user());

        //     if(Auth::attempt($pengguna)){
        //         return "auth";
        //     }else{
        //         return "tidak";
        //     }
        //     // $request->session()->put('key', $pengguna);
        //     // return redirect('/pj/dashboard');
        // }else if(count($queryAdmin) > 0){
        //     $pengguna = array('email' => $email, 'password' => $password);
        //     // Auth::guard('web')->loginUsingId($queryAdmin[0]->id);
        //     if(Auth::attempt($pengguna)){
        //         return "auth";
        //     }else{
        //         return "tidak";
        //     }
        //     // $request->session()->put('key', $pengguna);
        //     // return redirect('/admin/dashboard');
        // }else{
        //     return 'login gagal';
        // }
    }
}
