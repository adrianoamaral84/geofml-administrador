<?php

namespace App\Http\Controllers\Forca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Forca;
use \Crypt;
use Auth;

class ForcaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        /*
        if(!\Gate::allows('isadministrador')){
            abort(403, "Desculpa, você não tem autorização!");
        }
        */
        
        if(!Auth::user()->hasRole('administrador_geral')){
            abort(403, "Desculpa, você não tem autorização!");
        }
        
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\Forca::listAll($search);

    	//$consulta = \App\Uf::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('forca.index', compact('menuAtivo','search', 'consulta'));
    }

    public function forcadelete($id)
    {
    	$consulta = \App\Forca::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        return redirect()->route('forca.index', compact('menuAtivo'));
    }


    public function forcacadastra(Request $request)
    {	
    	$customMessages = [
            'nome.max' => 'Campo Nome deve ter no max 50 caracteres',
            'nome.min' => 'Campo Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',

           
        ];

        $validatedData = [
            'nome' => 'required|min:3|max:50',
          
        ];
        $validatedData = $request->validate($validatedData, $customMessages); 
        //dd($validatedData['nome']);
        $consulta = \App\Forca::where('forca', $validatedData['nome'])->count();
        //$consulta1 = \App\Uf::where('descricao', $validatedData['nome'])->count();
        //dd($consulta1);
        //dd($request->all('id'));


        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe Força com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('forca.create');
        }

      
        
        $uf = new Forca;  
    	$uf->forca = $validatedData['nome'];
    	$uf->save();
        $menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('forca.index', compact('menuAtivo'));
    }

    public function forcacreate()
    {	
    	$menuAtivo = "administracao";
    	return view('forca.create', compact('menuAtivo'));
    }

    public function forcaedit($id)
    {	


    	$id = Crypt::decrypt($id);        
        $consulta = \App\Forca::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
    	return view('forca.edit', compact('menuAtivo','consulta'));
    }

    public function forcaupdate(Request $request)
    {
    	$customMessages = [
            'nome.max' => 'Campo Nome deve ter no max 50 caracteres',
            'nome.min' => 'Campo Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
             
        ];
        $validatedData = [
            'nome' => 'required|min:3|max:50',
           
        ];

        $validatedData = $request->validate($validatedData, $customMessages);   
        
        $consulta1 = \App\Forca::where('forca', $validatedData['nome'])->count();


        if($consulta1 == 1){
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        
        $consulta = \App\Forca::find($request->id);
        $id = Crypt::encrypt($request->id);

        return redirect()->route('forca.edit', compact('id'));
        }

        $consulta = \App\Forca::find($request->id);     
        $consulta->forca = $validatedData['nome'];        
        $consulta->update();
        
        $menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('forca.index', compact('menuAtivo'));

    	//return view('indexuf');
    }
}
