<?php

namespace App\Http\Controllers\EstadoCivil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\EstadoCivil;
use \Crypt;

class EstadoCivilController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\EstadoCivil::listAll($search);

    	//$consulta = \App\EstadoCivil::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('estadocivil.index', compact('menuAtivo','search','consulta'));
    }

    public function showFormCadastra()
    {
        $menuAtivo = "administracao";
    	return view('estadocivil.create', compact('menuAtivo'));
    }

    public function create(Request $request)
    {	
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 20 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta = \App\EstadoCivil::where('descricao', $validatedData['nome'])->count();
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe Descrição com esse nome!', 'class'=>'danger']);
        //$id = Crypt::encrypt($request->id);
        return redirect()->route('estadocivil.create');
        }
        $EstadoCivil = new EstadoCivil;  
    	//$uf->sigla = $validatedData['sigla'];   	 	
    	$EstadoCivil->descricao = $validatedData['nome'];
    	$EstadoCivil->save();
        $menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('estadocivil.index', compact('menuAtivo'));
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\EstadoCivil::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
        return view('estadocivil.edit', compact('menuAtivo','consulta'));
    }
    public function update(Request $request)
    {
        //dd($request);
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 20 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);   
        $consulta = \App\EstadoCivil::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe Descrição com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('estadocivil.edit', compact('id'));
        }
        $consulta = \App\EstadoCivil::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        $menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('estadocivil.index', compact('menuAtivo'));

    	//return view('indexuf');
    }




    public function destroy($id)
    {
    	$consulta = \App\EstadoCivil::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        return redirect()->route('estadocivil.index', compact('menuAtivo'));
    }
}
