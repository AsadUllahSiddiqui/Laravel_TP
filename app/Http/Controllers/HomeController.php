<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index (){
        $user=  Auth::guard('admin')->user();
        echo 'welcome' .$user->name. '<a href="'.route('admin.logout').'">  Logout </a>';
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
