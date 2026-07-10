<?php

namespace App\Http\Controllers\ClasseHabitacional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use \App\ClasseHabitacional;

class ClasseHabitacionalController extends Controller
{
  
  public function __construct()
    {
        $this->middleware('auth');
    }
  
  public function index(){
   	$consulta = ClasseHabitacional::paginate(20);
   	return view('classehabitacional.index', compact('consulta'));
   }

   public function edit($id){
    
    $id = Crypt::decrypt($id);
   	$consulta = ClasseHabitacional::find($id);
   	return view('classehabitacional.edit', compact('consulta'));
   }

   public function delete($id){
    
    
    $id = Crypt::decrypt($id);
   	$consulta = ClasseHabitacional::find($id);
   	$consulta->delete();
   	\Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('classehabitacao.index');
   	
   }

   public function create(){
    
    return view('classehabitacional.create');
   }

   public function store(Request $request){
    
    
   		$customMessages = [
            'nome.min' => 'Descrição deve ter no min 2 caracteres',
            'nome.max' => 'Descrição deve ter no max 50 caracteres',            
            'nome.required' => 'Campo obrigatório',
            'classe.min' => 'Classe deve ter no min 2 caracteres',
            'classe.max' => 'Classe deve ter no max 10 caracteres',            
            'classe.required' => 'Campo obrigatório',
        ];

        $validatedData = [
            'nome' => 'required|max:50|min:2',
            'classe' => 'required|max:10',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta1 = ClasseHabitacional::where('classe', $request->classe)->count();
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe uma classe com esse Nome!", 'class'=>'danger']);
        	return redirect()->back();
        }
        $consulta = new ClasseHabitacional();
   		$consulta->classe = $validatedData['classe'];
   		$consulta->descricao = $validatedData['nome'];
   		$consulta->save();
   		\Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
    	return redirect()->route('classehabitacao.index');
   }

   public function update(Request $request){
    
    //dd($request->all());
    //$id = Crypt::decrypt($id);
   	
   	//dd($consulta);
   	$customMessages = [
            'nome.min' => 'Descrição deve ter no min 2 caracteres',
            'nome.max' => 'Descrição deve ter no max 50 caracteres',            
            'nome.required' => 'Campo obrigatório', 
            'classe.min' => 'Classe deve ter no min 2 caracteres',
            'classe.max' => 'Classe deve ter no max 10 caracteres',            
            'classe.required' => 'Campo obrigatório', 
        ];

        $validatedData = [
            'nome' => 'required|max:50|min:2',
            'classe' => 'required|max:10',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta1 = ClasseHabitacional::where('classe', $request->classe)->where('id', '<>', $request->id)->count();
        //dd($consulta1);
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe uma classe com esse Nome!", 'class'=>'danger']);
        	return redirect()->back();
        }
        $consulta = ClasseHabitacional::find($request->id);
        $consulta->classe = $validatedData['classe'];
   		$consulta->descricao = $validatedData['nome'];
   		$consulta->update();
   		\Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
   		return redirect()->route('classehabitacao.index');
   }
}
