<?php

namespace App\Http\Controllers\TipoPublicacao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\TipoPublicacao;

class TipoPublicacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\TipoPublicacao::listAll($search);

    	//$consulta = \App\SituacaoCandidato::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('tipopublicacao.index', compact('menuAtivo','search','consulta'));
    }

    public function showFormCadastra()
    {
        $menuAtivo = "administracao";
    	return view('tipopublicacao.create', compact('menuAtivo'));
    }

    public function create(Request $request)
    {	
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 100 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:100',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);
        $menuAtivo = "administracao";
        $consulta = \App\TipoPublicacao::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('tipopublicacao.create.show', compact('menuAtivo'));
        }
        $tipopublicacao = new TipoPublicacao;  
    	//$uf->sigla = $validatedData['sigla'];   	 	
    	$tipopublicacao->descricao = $validatedData['nome'];
    	$tipopublicacao->save();
        //$menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('tipopublicacao.index', compact('menuAtivo'));
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\TipoPublicacao::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
        return view('tipopublicacao.edit', compact('menuAtivo','consulta'));
    }
    public function update(Request $request)
    {
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 100 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:100',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);
        $menuAtivo = "administracao";    
        $consulta = \App\TipoPublicacao::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('tipopublicacao.index', compact('menuAtivo'));
        }
        $consulta = \App\TipoPublicacao::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('tipopublicacao.index', compact('menuAtivo'));

    	//return view('indexuf');
    }




    public function destroy($id)
    {
        //dd($id);
    	
    	$consulta = \App\TipoPublicacao::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        return redirect()->route('tipopublicacao.index', compact('menuAtivo'));
    }
}
