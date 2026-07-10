<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Area;
use \Crypt;

class AreaController extends Controller
{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {   

        //$search = isset($request['search']) && $request['search'] != null ? $request['search'] : null;
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        //dd($search);
        //$search = 'admin';
/*  
        if (isset($request['search'])) {
            $search = $request['search'];
            $request->session()->put('search', $request['search']);
        } else {
            $search = $request->session()->get('search');
        }
    */
        
        $consulta = \App\Area::listAll($search);
        //dd($consulta);
    	//$consulta = \App\Area::paginate(10);
    	
        $menuAtivo = "administracao";
    	return view('area.lista', compact('consulta', 'search',  'menuAtivo'));
    }

    public function buscaarea(Request $request)
    {
        
        
    }

    
    public function showFormCadastraArea()
    {
        $menuAtivo = "administracao";

    	return view('area.cadastro', compact('menuAtivo'));
    }

    public function deleteArea($id)
    {
    	//$id = Crypt::decrypt($id);
    	//dd($id);
        $consulta = \App\Area::find($id);
        //dd($consulta);
        $consulta->delete();
        $menuAtivo = "administracao";

        return redirect()->route('listArea', compact('menuAtivo'));

    }

    public function showarea($id)
    {
    	$consulta = \App\Area::find($id);
    	dd($consulta);
    	return 'showarea';
    }

    public function cadastraarea(Request $request)
    {	
    	$customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
            
        ];

        $validatedData = [
            'nome' => 'required|min:3',
        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
        $consulta = \App\Area::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        if($consulta >= 1){
        //dd($consulta);
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('showFormCadastraArea');
        }
    	$area = new Area;
    	//dd($area);   	
    	$area->descricao = $validatedData['nome'];
    	$area->save();
        $menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Area cadastrada com sucesso.', 'class'=>'success']);
        return redirect()->route('listArea', compact('menuAtivo'));
    }
    
    public function editarea()
    {
    	return 'Editar';
    }

    public function updatearea($id)
    {

        
        //$id = $request->id;
        $id = Crypt::decrypt($id);

        $consulta = \App\Area::find($id);
        $menuAtivo = "administracao";

        //dd($consulta);
        return view('area.edit', compact('consulta', 'menuAtivo')); 
        //dd($consulta);


        /*
        $update = new Area;
        $update->id = $id;
        $update->update();
        dd($update);
        */
        //return 'updatearea';
    }

    public function areaupdate(Request $request)
    {

        $customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
            
        ];

        $validatedData = [
            'nome' => 'required|min:3',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);   
         $consulta = \App\Area::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        if($consulta >= 1){
        //dd($consulta);
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('updatearea', compact('id'));
        }

        $consulta = \App\Area::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        $consulta->update();
        
        $menuAtivo = "administracao";
        
        \Session::flash('message', ['msg'=>'Nome da Area Atualizado com sucesso.', 'class'=>'success']);
        return redirect()->route('listArea', compact('menuAtivo'));
    }
}
