<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $namepage = "Home";
        return view("home", compact('namepage'));
    }
}
