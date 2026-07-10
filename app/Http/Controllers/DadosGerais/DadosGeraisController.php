<?php

namespace App\Http\Controllers\DadosGerais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DadosGerais;
use \Crypt;

class DadosGeraisController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
   	
   			//dd('index');

    $consulta = \App\DadosGerais::paginate(10);
    //dd($consulta);
   	return view('dadosgerais.index', compact('consulta'));
   	}

   	public function create(){
   	
   	return view('dadosgerais.create');
   	
   	}

   	public function store(Request $request){
   	
   	//dd($request);
   	$customMessages = [
            'cabecalho.max' => 'Campo Cabeçalho deve ter no max 255 caracteres',
            'cabecalho.min' => 'Campo Cabeçalho deve ter no min 2 caracteres',
            'cabecalho.required' => 'Campo obrigatório', 

            'nome_secao.max' => 'Campo Nome Seção deve ter no max 100 caracteres',
            'nome_secao.min' => 'Campo Nome Seção deve ter no min 2 caracteres',
            'nome_secao.required' => 'Campo obrigatório',          

            'assinatura.max' => 'Campo Assinatura deve ter no max 100 caracteres',
            'assinatura.min' => 'Campo Assinatura deve ter no min 2 caracteres',
            'assinatura.required' => 'Campo obrigatório',  
        ];

        $validatedData = [
            'cabecalho' => 'required',
            'nome_secao' => 'required|min:3|max:100',
            'assinatura' => 'required|min:3|max:100',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);

        $DadoGerais = new DadosGerais;
    	$DadoGerais->cabecalho = $validatedData['cabecalho'];
    	$DadoGerais->nome_secao = $validatedData['nome_secao'];
    	$DadoGerais->assinatura = $validatedData['assinatura'];
        $DadoGerais->save();

    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);

   		return redirect()->route('dadosgerais.index');
   	
   	}

   	public function update(Request $request){
   	
   	//dd($request);
   	$customMessages = [
            'cabecalho.max' => 'Campo Cabeçalho deve ter no max 255 caracteres',
            'cabecalho.min' => 'Campo Cabeçalho deve ter no min 2 caracteres',
            'cabecalho.required' => 'Campo obrigatório', 

            'nome_secao.max' => 'Campo Nome Seção deve ter no max 100 caracteres',
            'nome_secao.min' => 'Campo Nome Seção deve ter no min 2 caracteres',
            'nome_secao.required' => 'Campo obrigatório',          

            'assinatura.max' => 'Campo Assinatura deve ter no max 100 caracteres',
            'assinatura.min' => 'Campo Assinatura deve ter no min 2 caracteres',
            'assinatura.required' => 'Campo obrigatório',  
        ];

        $validatedData = [
            'cabecalho' => 'required',
            'nome_secao' => 'required|min:3|max:100',
            'assinatura' => 'required|min:3|max:100',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);

        $DadoGerais = DadosGerais::find($request->id);
    	$DadoGerais->cabecalho = $validatedData['cabecalho'];
    	$DadoGerais->nome_secao = $validatedData['nome_secao'];
    	$DadoGerais->assinatura = $validatedData['assinatura'];
        $DadoGerais->update();

    	\Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);

   		return redirect()->route('dadosgerais.index');
   	
   	}

		public function edit($id)
    	{   
        
        $id = Crypt::decrypt($id);        
        $consulta = \App\DadosGerais::find($id);       
        //dd($consulta);
        return view('dadosgerais.edit', compact('consulta'));
    }


   	public function delete($id)
    {
    	$id = Crypt::decrypt($id);  
    	//dd($id);
    	$consulta = DadosGerais::find($id);
        //dd($consulta);
        
        $consulta->delete();
        //$menuAtivo = "administracao";
    	\Session::flash('message', ['msg'=>'Deletado com sucesso!', 'class'=>'success']);

        return redirect()->route('dadosgerais.index');
    }


}
