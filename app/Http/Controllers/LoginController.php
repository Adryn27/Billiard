<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginBackend(){
        return view('backend.v_login.login', [
            'judul'=>'Login',
        ]);
    }
}
