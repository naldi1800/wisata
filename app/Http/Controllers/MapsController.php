<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapsController extends Controller
{
    private $namepage = "Map";
    public function index(){
        $namepage = $this->namepage;
        return view("map.index", compact('namepage'));
    }
}
