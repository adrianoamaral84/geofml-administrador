<?php

namespace App\Http\Controllers\Temporada;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
class TemporadaController extends Controller
{
	  public function __construct()
    {
        $this->middleware('auth');
       
    }
    public function index()
    {
    	$consulta = \App\Temporada::with('tipotemporadas')->paginate(10);

    	return view('temporada.index', compact('consulta'));
    }

    public function create(){
      $temporadas = \App\TipoTemporada::all();
    return view('temporada.create', compact('temporadas'));
   }

    public function store(Request $request){
    
    
   		$customMessages = [
                  
            'temporada.required' => 'Campo obrigatório',                  
            'dataini.required' => 'Campo obrigatório',
            'dataini.after' => 'A Data início deve ser uma data depois de amanhã',
            'datater.required' => 'Campo obrigatório',        
            'datater.after' => 'Campo Data Término precisa ser depois da data inicial',

                        ];

        $validatedData = [
            'temporada' => 'required',
            'dataini' => 'required|date',
            'datater' => 'required|date',

            
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
        $consulta1 = \App\Temporada::where('tipo_temporada_id', $request->temporada)->count();
        //dd($consulta1);
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe um cadastro com essa Temporada!", 'class'=>'danger']);
        	return redirect()->back();
        }
        $consulta = new \App\Temporada();
   		$consulta->tipo_temporada_id = $validatedData['temporada'];
   		$consulta->data_inicio = $validatedData['dataini'];
   		$consulta->data_termino = $validatedData['datater'];

   		$consulta->save();
   		\Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
      return redirect()->route('configurarhospedagem.index');
    	//return redirect()->route('temporada.index');
   }
   public function edit($id){
    
    $id = Crypt::decrypt($id);
   	$consulta = \App\Temporada::find($id);
   	//dd($consulta);
   	$temporadas = \App\TipoTemporada::all();
   	return view('temporada.edit', compact('consulta', 'temporadas'));
   }
   public function update(Request $request){
    
    //dd($request->all());
   		$customMessages = [
                  
            'temporada.required' => 'Campo obrigatório',                  
            'dataini.required' => 'Campo obrigatório',
            'dataini.after' => 'A Data início deve ser uma data depois de amanhã',
            'datater.required' => 'Campo obrigatório',
         
            'datater.after' => 'Campo Data Término precisa ser depois da data inicial',

        ];

        $validatedData = [
            'temporada' => 'required',
            'dataini' => 'required|date',
            'datater' => 'required|date',

            
        ];
        $validatedData = $request->validate($validatedData, $customMessages);
       
        
        $consulta1 = \App\Temporada::where('tipo_temporada_id', $request->temporada)
        ->where('id', '<>', $request->id)
        ->count();
        //dd($consulta1);
        if($consulta1 > 0){
        	\Session::flash('message', ['msg'=>"Já existe um cadastro com essa Temporada!", 'class'=>'danger']);
        	return redirect()->back();
        }
        

      $consulta = \App\Temporada::find($request->id);
   		$consulta->tipo_temporada_id = $validatedData['temporada'];
   		$consulta->data_inicio = $validatedData['dataini'];
   		$consulta->data_termino = $validatedData['datater'];

   		$consulta->update();
   		\Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
      return redirect()->route('configurarhospedagem.index');
    	//return redirect()->route('temporada.index');
   }
   public function delete($id){
     
    $id = Crypt::decrypt($id);
   	$consulta = \App\Temporada::find($id);
    $tarifas_und = \App\Tarifas::where('temporada_id', $id)->count();
   	
    if($tarifas_und > 0){
      \Session::flash('message', ['msg'=>"Não pode ser deletado. Está sendo usado em TARIFAS ", 'class'=>'danger']);
      return redirect()->back();
    }else{
      $consulta->delete();
    }

   	\Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('temporada.index');
   	
   }
}
