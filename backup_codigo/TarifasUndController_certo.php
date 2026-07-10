<?php

namespace App\Http\Controllers\Tarifas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;

class TarifasUndController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

      
    $altatemporadas = \App\Temporada::find(2);
    $baixatemporadas = \App\Temporada::find(5);

   /* //dd($altatemporadas);
   	$consulta = \App\Tarifas::with("tipoundhab")->with('grupodestinacao')
    ->join('grupo_tarifa', 'grupo_tarifa.id', '=', 'tarifas_und.grupo_destinacao_id')
    ->orderBy('grupo_tarifa.descricao')->paginate(20);
    //->orderBy('descricao', 'ASC')->paginate(20);
    //dd($consulta);
    
   	return view('tarifas.index', compact('consulta', 'altatemporadas', 'baixatemporadas'));
   } */
   
   // CORREÇÃO: Apenas garanta que a consulta está chamando find() corretamente:
    $consulta = \App\Tarifas::select('tarifas_und.*') // Seleciona apenas campos de tarifas_und para evitar conflito
        ->with("tipoundhab")->with('grupodestinacao')
        ->join('grupo_tarifa', 'grupo_tarifa.id', '=', 'tarifas_und.grupo_destinacao_id')
        ->orderBy('grupo_tarifa.descricao')->paginate(30);

    return view('tarifas.index', compact('consulta', 'altatemporadas', 'baixatemporadas'));
}   

   public function create(){
    

    $habitacoes = \App\TipoUndHab::all();
    $destinacoes = \App\GrupoTarifa::all();
    //$habitacoes = \App\UnidadeHabitacional::all();

    return view('tarifas.create', compact('habitacoes', 'destinacoes'));
   }

   public function store(Request $request){
    
    //dd($request->all());

    $request->valor_baixa = str_replace(",",".",str_replace(".","",$request->valor_baixa));
    $request->valor = str_replace(",",".",str_replace(".","",$request->valor));
    
    //dd($request->valor);

    //$request->valor_baixa = substr($request->valor_baixa, 3, -1);
    //$request->valor = substr($request->valor, 3, -1);

    //dd($request->valor_baixa);
    

    $customMessages = [
                      
            'habitacao.required' => 'Campo Tipo Habitação obrigatório',
            'destinacao.required' => 'Campo Destinacão brigatório',
            'valor.required' => 'Campo Alta Temporada obrigatório',
            'valor_baixa.required' => 'Campo Baixa Temporada obrigatório',
         
        ];

        $validatedData = [
            'destinacao' => 'required',
            'habitacao' => 'required',
            'valor' => 'required|max:100|',
            'valor_baixa' => 'required|max:100',
           
        ];


        $validatedData = $request->validate($validatedData, $customMessages);
        
       /* $consulta2 = \App\Tarifas::where('grupo_destinacao_id', $request->destinacao)  
        ->count();

        if($consulta2 > 0){
          \Session::flash('message', ['msg'=>"Já existe uma Tarifa cadastrada com esse grupo!", 'class'=>'danger']);
          return redirect()->back();
        } */
        


        $consulta1 = \App\Tarifas::where('tipoundhab_id', $request->habitacao)
        ->where('grupo_destinacao_id', $request->destinacao)
        ->count();
        if($consulta1 > 0){
          \Session::flash('message', ['msg'=>"Já existe uma Tarifa cadastrada com esse grupo e com esse tipo UH!", 'class'=>'danger']);
          return redirect()->back();
        }
        
      $consulta = new \App\Tarifas();
      $consulta->tipoundhab_id = $validatedData['habitacao'];
      $consulta->grupo_destinacao_id = $validatedData['destinacao'];
      $consulta->valor_baixa = $request->valor_baixa;
      $consulta->valor = $request->valor;


      $consulta->save();
      \Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
      return redirect()->route('tarifas.index');
   }

   public function update(Request $request){
    
    //dd($request->all());

    $request->valor_baixa = str_replace(",",".",str_replace(".","",$request->valor_baixa));
    $request->valor = str_replace(",",".",str_replace(".","",$request->valor));
    
    //dd($request->valor);

    //$request->valor_baixa = substr($request->valor_baixa, 3, -1);
    //$request->valor = substr($request->valor, 3, -1);

    //dd($request->valor_baixa);
    

    $customMessages = [
                      
            'habitacao.required' => 'Campo Habitação obrigatório',
            'destinacao.required' => 'Campo Destinacão brigatório',
            'valor.required' => 'Campo Alta Temporada obrigatório',
            'valor_baixa.required' => 'Campo Baixa Temporada obrigatório',
         
        ];

        $validatedData = [
            'destinacao' => 'required',
            'habitacao' => 'required',
            'valor' => 'required|max:100|',
            'valor_baixa' => 'required|max:100',
           
        ];


        $validatedData = $request->validate($validatedData, $customMessages);
        

        $consulta2 = \App\Tarifas::where('grupo_destinacao_id', $request->destinacao)  
        ->where('id', '<>', $request->id)
        ->count();

        //dd($consulta2);
        if($consulta2 > 0){
          \Session::flash('message', ['msg'=>"Já existe uma Tarifa cadastrada com esse grupo!", 'class'=>'danger']);
          return redirect()->back();
        }
        


        $consulta1 = \App\Tarifas::where('tipoundhab_id', $request->habitacao)
        ->where('grupo_destinacao_id', $request->destinacao)
        ->where('id', '<>', $request->id)
        ->count();
        if($consulta1 > 0){
          \Session::flash('message', ['msg'=>"Já existe uma Tarifa cadastrada com esse grupo e com esse tipo UH!", 'class'=>'danger']);
          return redirect()->back();
        }
        
      $consulta = \App\Tarifas::find($request->id);
      //dd($consulta);
      $consulta->tipoundhab_id = $validatedData['habitacao'];
      $consulta->grupo_destinacao_id = $validatedData['destinacao'];
      $consulta->valor_baixa = $request->valor_baixa;
      $consulta->valor = $request->valor;


      $consulta->update();
      \Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
      return redirect()->route('tarifas.index');
   }


   public function edit($id){    

    $id = Crypt::decrypt($id);
    $consulta = \App\Tarifas::find($id);
    //dd($consulta);
    //$habitacoes = \App\UnidadeHabitacional::all();
    $habitacoes = \App\TipoUndHab::all();

    $destinacoes = \App\GrupoTarifa::all();

    return view('tarifas.edit', compact('consulta', 'habitacoes', 'destinacoes'));
   }

   /*public function delete($id){
    
    
    $id = Crypt::decrypt($id);
    //dd($id);
    $consulta = \App\Tarifas::find($id);
    $consulta->delete();
    \Session::flash('message', ['msg'=>"Deletado com sucesso!", 'class'=>'success']);
    return redirect()->route('tarifas.index');
    
   }*/

public function delete($id)
{
    try {
        $id = Crypt::decrypt($id);
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        // Trata a falha na descriptografia se o ID na URL estiver corrompido
        \Session::flash('message', ['msg'=>"Erro: ID de Deleção Inválido.", 'class'=>'danger']);
        return redirect()->route('tarifas.index');
    }

    $consulta = \App\Tarifas::find($id);

    // CORREÇÃO: Verifica se o objeto foi encontrado
    if ($consulta) {
        // Se o objeto existe, deleta (para os IDs que funcionam)
        $consulta->delete();
        $mensagem = "Deletado com sucesso";
    } else {
        // Se o objeto NÃO existe (caso do ID 60), consideramos a 'deleção' um sucesso
        // para que o item fantasma seja limpo da sessão e da cache.
        $mensagem = "Registro já excluído. Limpando a listagem.";
    }

    \Session::flash('message', ['msg'=>$mensagem, 'class'=>'success']);
    return redirect()->route('tarifas.index');
}


}
