<?php

namespace App\Http\Controllers\Ocupacao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\DB;


class OcupacaoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listaPorMes(){


    dd('ocupacao');    
    }


    
    
}
