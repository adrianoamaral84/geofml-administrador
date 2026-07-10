<?php

namespace App\Http\Controllers\Graficos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Forca;
use \Crypt;
use Auth;

class GraficosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
}
