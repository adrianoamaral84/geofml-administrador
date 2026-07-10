<?php

namespace App\Http\Controllers\GrupoDestinacao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use \App\GrupoDestinacao;

class GrupoDestinacaoController extends Controller
{

  public function __construct()
    {
        $this->middleware('auth');
    }

   public function index(){


   	$consulta = GrupoDestinacao::paginate(20);
   	return view('grupodestinacao.index', compact('consulta'));
   }

   public function edit($id){
    
    $id = Crypt::decrypt($id);
   	$consulta = GrupoDestinacao::find($id);
   	return view('grupodestinacao.edit', compact('consulta'));
   }

   public function delete($id){
    
    
    $id = Crypt::decrypt($id);
   	$consulta = GrupoDestinacao::find($id);
   	$consulta->delete();
   	\Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('grupo_destinacao.index');
   	
   }

   public function create(){
    
    return view('grupodestinacao.create');
   }

   public function store(Request $request){
    
    
   		$customMessages = [
            'nome.min' => 'Descrição deve ter no min 2 caracteres',
            'nome.max' => 'Descrição deve ter no max 200 caracteres',            
            'nome.required' => 'Campo obrigatório',
         
        ];

        $validatedData = [
            'nome' => 'required|max:200|min:2',
           
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta1 = GrupoDestinacao::where('descricao', $request->nome)->count();
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe um grupo com esse Nome!", 'class'=>'danger']);
        	return redirect()->back();
        }
        $consulta = new GrupoDestinacao();
   		$consulta->descricao = $validatedData['nome'];
   		$consulta->save();
   		\Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
    	return redirect()->route('grupo_destinacao.index');
   }

   public function update(Request $request){
    
    //dd($request->all());
    //$id = Crypt::decrypt($id);
   	
   	//dd($consulta);
   	$customMessages = [
            'nome.min' => 'Descrição deve ter no min 2 caracteres',
            'nome.max' => 'Descrição deve ter no max 200 caracteres',            
            'nome.required' => 'Campo obrigatório', 
        
        ];

        $validatedData = [
            'nome' => 'required|max:200|min:2',
            
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta1 = GrupoDestinacao::where('descricao', $request->nome)
        ->where('id', '<>', $request->id)
        ->count();
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe um grupo com esse Nome!", 'class'=>'danger']);
        	return redirect()->back();
        }
        $consulta = GrupoDestinacao::find($request->id);
   		$consulta->descricao = $validatedData['nome'];
   		$consulta->update();
   		\Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
   		return redirect()->route('grupo_destinacao.index');
   }
}
