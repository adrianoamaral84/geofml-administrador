<?php

namespace App\Http\Controllers\TarifaExtra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;


class TarifaExtraController extends Controller
{
    
  public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){


   		$consulta = \App\TarifaExtra::paginate(20);
    	return view('tarifaextra.index', compact('consulta'));

    }

    public function create(){
    

   
   
    return view('tarifaextra.create');
   }



   public function store(Request $request){
    
    //dd($request->all());

   
    $request->valor = str_replace(",",".",str_replace(".","",$request->valor));
    
    //dd($request->valor);

    //$request->valor_baixa = substr($request->valor_baixa, 3, -1);
    //$request->valor = substr($request->valor, 3, -1);

    //dd($request->valor_baixa);
    

    $customMessages = [
                      
           
            'tarifaextra.required' => 'Campo Alta Temporada obrigatório',
            'valor.required' => 'Campo Baixa Temporada obrigatório',
         
        ];

        $validatedData = [
            
            'tarifaextra' => 'required|max:100|',
            'valor' => 'required|max:100',
           
        ];


        $validatedData = $request->validate($validatedData, $customMessages);
        

        /*$consulta1 = Tarifas::where('descricao', $request->nome)->count();
        if($consulta1 > 0){
          \Session::flash('message', ['msg'=>"Já existe um grupo com esse Nome!", 'class'=>'danger']);
          return redirect()->back();
        }
        */
      $consulta = new \App\TarifaExtra();
      $consulta->descricao = $validatedData['tarifaextra'];
      $consulta->valor = $request->valor;


      $consulta->save();
      \Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
      return redirect()->route('tarifaextra.index');
   }

   public function edit($id){    


    $id = Crypt::decrypt($id);
    $consulta = \App\TarifaExtra::find($id);
    //dd($consulta);
    return view('tarifaextra.edit', compact('consulta'));


   }

   public function update(Request $request){
    
   // dd($request->all());

   
    $request->valor = str_replace(",",".",str_replace(".","",$request->valor));
    
    //dd($request->valor);

    //$request->valor_baixa = substr($request->valor_baixa, 3, -1);
    //$request->valor = substr($request->valor, 3, -1);

    //dd($request->valor_baixa);
    

     $customMessages = [
                      
           
            'tarifaextra.required' => 'Campo Alta Temporada obrigatório',
            'valor.required' => 'Campo Baixa Temporada obrigatório',
         
        ];

        $validatedData = [
            
            'tarifaextra' => 'required|max:100|',
            'valor' => 'required|max:100',
           
        ];


        $validatedData = $request->validate($validatedData, $customMessages);
        

        /*$consulta1 = Tarifas::where('descricao', $request->nome)->count();
        if($consulta1 > 0){
          \Session::flash('message', ['msg'=>"Já existe um grupo com esse Nome!", 'class'=>'danger']);
          return redirect()->back();
        }
        */
      $consulta = \App\TarifaExtra::find($request->id);
      $consulta->descricao = $validatedData['tarifaextra'];
      $consulta->valor = $request->valor;
      $consulta->update();

      \Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
      return redirect()->route('tarifaextra.index');
   

   }

   public function delete($id){
    
    
    $id = Crypt::decrypt($id);
    //dd($id);
    $consulta = \App\TarifaExtra::find($id);
    //dd($consulta);
    $consulta->delete();
    \Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('tarifaextra.index');
    
   }


}
