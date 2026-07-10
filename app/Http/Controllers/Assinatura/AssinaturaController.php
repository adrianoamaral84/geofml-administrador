<?php

namespace App\Http\Controllers\Assinatura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;


class AssinaturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       
    }


    public function edit($id){
    
    $id = Crypt::decrypt($id);
    //dd($id);
   	$assinatura = \App\Assinatura::where('id', $id)->first();
   	$postos = \App\PostoGraduacao::all();
   	//dd($assinatura);
   	return view('assinatura.edit', compact('assinatura', 'postos'));
   }

   public function update(Request $request){

   	$assinatura = \App\Assinatura::where('id', $request->id)->first();
   	$assinatura->nome = strtoupper($request->nome);
   	$assinatura->funcao = $request->funcao;
   	$assinatura->posto_id = $request->posto;
   	$assinatura->update();
   	\Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
     return redirect()->route('email.index');

   

   	//dd($assinatura);

   }
}
