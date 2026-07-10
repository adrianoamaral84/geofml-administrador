<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;

class HorarioController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){


    	$consulta = \App\Horario::all();

    	return view('horario.index', compact('consulta'));
    }


    public function edit($id){

    	$id = Crypt::decrypt($id);
    	$consulta = \App\Horario::findOrFail($id);
    	//dd($id);
    	return view('horario.edit', compact('consulta'));
    }

    public function update(Request $request){

    	$customMessages = [
           
            'horaentrada.required' => 'Campo obrigatório', 
            'horasaida.required' => 'Campo obrigatório', 

          
        ];

        $validatedData = [
            'horaentrada' => 'required',
            'horasaida' => 'required',
            
        ];
        $validatedData = $request->validate($validatedData, $customMessages);

    	$consulta = \App\Horario::findOrFail($request->id);
    	$consulta->entrada = $validatedData['horaentrada'];
    	$consulta->saida = $validatedData['horasaida'];
    	$consulta->update();

    	 \Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
                
                return redirect()->route('configurarhospedagem.index');
                //return redirect()->route('horario.index');

    }

}
