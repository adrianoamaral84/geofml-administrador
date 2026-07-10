<?php

namespace App\Http\Controllers\TipoDocumentoMilitar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\TipoDocumentoMilitar;

class TipoDocumentoMilitarController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\TipoDocumentoMilitar::listAll($search);

    	//$consulta = \App\TipoDocumentoMilitar::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('documentomilitar.index', compact('menuAtivo','search', 'consulta'));
    }

    public function showFormCadastra()
    {

        $menuAtivo = "administracao";
    	return view('documentomilitar.create', compact('menuAtivo'));
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
        $consulta = \App\TipoDocumentoMilitar::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe Descrição com esse nome!', 'class'=>'danger']);
        //$id = Crypt::encrypt($request->id);
        return redirect()->route('documentomilitar.create');
        }
        $documentomilitar = new TipoDocumentoMilitar;  
    	//$uf->sigla = $validatedData['sigla'];   	 	
    	$documentomilitar->descricao = $validatedData['nome'];
    	$documentomilitar->save();
        $menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('documentomilitar.index', compact('menuAtivo'));
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\TipoDocumentoMilitar::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
        return view('documentomilitar.edit', compact('menuAtivo','consulta'));
    }
    public function update(Request $request)
    {
    	$customMessages = [
            'nome.max' => 'Campo Documento Militar deve ter no max 100 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:100',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);   
        $consulta = \App\TipoDocumentoMilitar::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe Descrição com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('documentomilitar.edit', compact('id'));
        }

        $consulta = \App\TipoDocumentoMilitar::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        $menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('documentomilitar.index', compact('menuAtivo'));

    	//return view('indexuf');
    }




    public function destroy($id)
    {
    	$consulta = \App\TipoDocumentoMilitar::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'Deletado com sucesso!', 'class'=>'success']);
        return redirect()->route('documentomilitar.index', compact('menuAtivo'));
    }
}
