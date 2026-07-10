<?php

namespace App\Http\Controllers\UnidadeHabitacional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use \App\UnidadeHabitacional;

class UnidadeHabitacionalController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
   	$consulta = UnidadeHabitacional::all();
    
   	return view('unidadehabitacional.index', compact('consulta'));
   }

   public function edit($id){
    
    $id = Crypt::decrypt($id);
    $tipos = \App\TipoUndHab::all();
   	$classes = \App\ClasseHabitacional::all(); 
   	$destinos = \App\GrupoDestinacao::all();
   	$consulta = UnidadeHabitacional::find($id);
   	return view('unidadehabitacional.edit', compact('consulta', 'tipos', 'classes', 'destinos'));
   }

   public function delete($id){
    
    
    $id = Crypt::decrypt($id);
   	$consulta = UnidadeHabitacional::find($id);
   	$consulta->delete();
   	\Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('uh.index');
   	
   }

   public function create(){
   
   $tipos = \App\TipoUndHab::all();
   $classes = \App\ClasseHabitacional::all(); 
   $destinos = \App\GrupoDestinacao::all();

   return view('unidadehabitacional.create', compact('tipos', 'classes', 'destinos'));
   }

   public function store(Request $request){
    
    //dd($request->all());
   		$customMessages = [
            'numero.min' => 'Numero deve ter no min 2 caracteres',
            'numero.max' => 'Numero deve ter no max 50 caracteres',            
            'numero.required' => 'Campo obrigatório',
            'tipo.required' => 'Campo obrigatório',
            'classe.required' => 'Campo obrigatório',
            'destino.required' => 'Campo obrigatório',
            'sala.required' => 'Campo obrigatório',
            'cozinha.required' => 'Campo obrigatório',
            'quartos.required' => 'Campo obrigatório',
            'capacidade.required' => 'Campo obrigatório',
            'pet.required' => 'Campo obrigatório',
            'disponivel.required' => 'Campo obrigatório',
            'observacao.required' => 'Campo obrigatório',
			      'observacao.min' => 'Observação deve ter no min 2 caracteres',
            'observacao.max' => 'Observação deve ter no max 50 caracteres',  
            'descricao.required' => 'Campo obrigatório',

           

        ];

        $validatedData = [
            'tipo' => 'required',
            'classe' => 'required',
            'destino' => 'required',
            'numero' => 'required|max:50|min:2',
            'sala' => 'required',
            'cozinha' => 'required',
            'quartos' => 'required',
            'capacidade' => 'required',
            'pet' => 'required',
            'disponivel' => 'required',
            'observacao' => 'required|max:50|min:2',
           

        ];
        $validatedData = $request->validate($validatedData, $customMessages);
      /*
        $consulta1 = UnidadeHabitacional::where('sigla', $request->numero)->count();
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe esse campo número!", 'class'=>'danger']);
        	return redirect()->back();
        }
      */  
      $consulta = new UnidadeHabitacional();
   		$consulta->tipo_und_hab_id = $validatedData['tipo'];
   		$consulta->classe_habitacional_id = $validatedData['classe'];
   		$consulta->grupo_destinacao_id = $validatedData['destino'];
   		$consulta->sigla = $validatedData['numero'];
   		$consulta->sala = $validatedData['sala'];
   		$consulta->cozinha = $validatedData['cozinha'];
   		$consulta->quartos = $validatedData['quartos'];
   		$consulta->capacidade_ocupacao = $validatedData['capacidade'];
   		$consulta->pet = $validatedData['pet'];
   		$consulta->disponivel = $validatedData['disponivel'];
   		$consulta->observacao = $validatedData['observacao'];
   		//$consulta->descricao = $validatedData['descricao'];

   		$consulta->save();
   		\Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
    	return redirect()->route('uh.index');
   }

   public function update(Request $request){
    
    	$customMessages = [
            'numero.min' => 'Numero deve ter no min 2 caracteres',
            'numero.max' => 'Numero deve ter no max 50 caracteres',            
            'numero.required' => 'Campo obrigatório',
            'tipo.required' => 'Campo obrigatório',
            'classe.required' => 'Campo obrigatório',
            'destino.required' => 'Campo obrigatório',
            'sala.required' => 'Campo obrigatório',
            'cozinha.required' => 'Campo obrigatório',
            'quartos.required' => 'Campo obrigatório',
            'capacidade.required' => 'Campo obrigatório',
            'pet.required' => 'Campo obrigatório',
            'disponivel.required' => 'Campo obrigatório',
            'observacao.required' => 'Campo obrigatório',
			      'observacao.min' => 'Observação deve ter no min 2 caracteres',
            'observacao.max' => 'Observação deve ter no max 50 caracteres',
            'descricao.required' => 'Campo obrigatório',

           

        ];

        $validatedData = [
            'tipo' => 'required',
            'classe' => 'required',
            'destino' => 'required',
            'numero' => 'required|max:50|min:2',
            'sala' => 'required',
            'cozinha' => 'required',
            'quartos' => 'required',
            'capacidade' => 'required',
            'pet' => 'required',
            'disponivel' => 'required',
            'observacao' => 'required|max:50|min:2',
            //'descricao' => 'required|max:50|min:2',
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        //dd($request->id);
        /*
        $consulta1 = UnidadeHabitacional::where('sigla', $request->numero)
        ->where('id', '!=', $request->id)
        ->count();
        //dd($consulta1);
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe esse campo número!", 'class'=>'danger']);
        	return redirect()->back();
        }
        */
      $consulta = UnidadeHabitacional::find($request->id);
   		$consulta->tipo_und_hab_id = $validatedData['tipo'];
   		$consulta->classe_habitacional_id = $validatedData['classe'];
   		$consulta->grupo_destinacao_id = $validatedData['destino'];
   		$consulta->sigla = $validatedData['numero'];
   		$consulta->sala = $validatedData['sala'];
   		$consulta->cozinha = $validatedData['cozinha'];
   		$consulta->quartos = $validatedData['quartos'];
   		$consulta->capacidade_ocupacao = $validatedData['capacidade'];
   		$consulta->pet = $validatedData['pet'];
   		$consulta->disponivel = $validatedData['disponivel'];
   		$consulta->observacao = $validatedData['observacao'];
   		//$consulta->descricao = $validatedData['descricao'];
   		$consulta->update();
   		\Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
   		return redirect()->route('uh.index');
   }
}
