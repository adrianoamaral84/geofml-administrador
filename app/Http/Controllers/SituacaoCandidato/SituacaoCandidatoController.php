<?php

namespace App\Http\Controllers\SituacaoCandidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\SituacaoCandidato;

class SituacaoCandidatoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\SituacaoCandidato::listAll($search);

    	//$consulta = \App\SituacaoCandidato::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('situacaocandidato.index', compact('menuAtivo','search','consulta'));
    }

    public function showFormCadastra()
    {
        $menuAtivo = "administracao";
    	return view('situacaocandidato.create', compact('menuAtivo'));
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
        $consulta = \App\SituacaoCandidato::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('situacaocandidato.create.show', compact('menuAtivo'));
        }
        $situacaocandidato = new SituacaoCandidato;  
    	//$uf->sigla = $validatedData['sigla'];   	 	
    	$situacaocandidato->descricao = $validatedData['nome'];
    	$situacaocandidato->save();
        //$menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('situacaocandidato.index', compact('menuAtivo'));
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\SituacaoCandidato::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
        return view('situacaocandidato.edit', compact('menuAtivo','consulta'));
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
        $consulta = \App\SituacaoCandidato::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('situacaocandidato.index', compact('menuAtivo'));
        }
        $consulta = \App\SituacaoCandidato::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('situacaocandidato.index', compact('menuAtivo'));

    	//return view('indexuf');
    }




    public function destroy($id)
    {
    	$consulta = \App\SituacaoCandidato::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        return redirect()->route('situacaocandidato.index', compact('menuAtivo'));
    }
}
