<?php

namespace App\Http\Controllers\GerenciarEmails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use App\GerenciarEmails;

class GerenciarEmailss extends Controller
{
    
	public function index(){
	
		$consulta = GerenciarEmails::all();
	    return view('emails.index', compact('consulta', $consulta));
	    
	}

	public function editar($id){


		$id = Crypt::decrypt($id);
		//dd($id);
	    $consulta = GerenciarEmails::FindOrFail($id);
	    //dd($consulta);

	    return view('emails.edit', compact('consulta', $consulta));

	}

	public function atualizar(Request $request){

			$customMessages = [
            'assunto.max' => 'Campo Cabeçalho deve ter no max 255 caracteres',
            'assunto.min' => 'Campo Cabeçalho deve ter no min 10 caracteres',
            'assunto.required' => 'Campo obrigatório', 

            'corpo.max' => 'Campo Mensagem deve ter no max 300 caracteres',
            'corpo.min' => 'Campo Mensagem deve ter no min 2 caracteres',
            'corpo.required' => 'Campo obrigatório',          

          
        ];

        $validatedData = [
            
            'corpo' => 'required|min:10|max:300',
            'assunto' => 'required|min:10|max:255',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);

        $email = GerenciarEmails::where('id', $request->id);
        //dd($email);
        $email->corpo = $validatedData['corpo'];
        $email->assunto = $validatedData['assunto'];
        $email->update();

        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);

   		return redirect()->route('email.index');
		//dd($email);

	}


}
