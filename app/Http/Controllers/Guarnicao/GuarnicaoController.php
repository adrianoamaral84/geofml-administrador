<?php

namespace App\Http\Controllers\Guarnicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\Guarnicao;
use \App\Cidade;
use \App\Forca;

class GuarnicaoController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
     
    public function index(Request $request)
    {   
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\Guarnicao::listAll($search);

    	//$consulta = \App\Guarnicao::paginate(10);
    	//$menuAtivo = "administracao";
    	return view('guarnicao.index', compact('consulta', 'search'));
    }

    public function showFormCadastra()
    {	
    	$estados = \App\Uf::all();
    	$menuAtivo = "administracao";
    	return view('guarnicao.create', compact('menuAtivo', 'estados'));
    }

    public function create(Request $request)
    {
        //dd($request->all());
        $customMessages = [
            'nome.max' => 'Campo Nome deve ter no max 20 caracteres',
            'nome.min' => 'Campo Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
            'estado.required' => 'Campo obrigatório',

        
        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20', 
            'estado' => 'required',          

        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
    	$consulta = \App\Guarnicao::where('uf_id', $validatedData['estado'])->count();
        $consulta1 = \App\Guarnicao::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        //dd($request->all('id'));


        if($consulta1 >= 1 and $consulta >= 1){
        \Session::flash('message', ['msg'=>'Existe Descrição nessa Guarnição!', 'class'=>'danger']);
        //id = Crypt::encrypt($request->id);
        return redirect()->route('guarnicao.create');
        }
        /*
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe Sigla com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('uf.create');
        }
        */

        //dd($request);
    	//dd($id);
    	//$id = Crypt::decrypt($request->id);
    	//dd($request->descricao);
    	//$uf = \App\Uf::find($id);    	
    	/*

    	$a = $uf->guarnicao()->create([
    		'descricao' => $validatedData['nome'],
    	]);
    	*/
    	$guarnicao = new Guarnicao;
    	$guarnicao->descricao = $validatedData['nome'];
    	$guarnicao->uf_id = $validatedData['estado'];
    	$guarnicao->save();
    	//$id = Crypt::encrypt($id);
    	//dd($id);
    	$menuAtivo = "administracao";

    	//dd($a);
		return redirect()->route('guarnicao.index', compact('menuAtivo'));
    	//dd($a);
    	
    }

    public function edit($id)
    {	
        $id = Crypt::decrypt($id);        
        $estados = \App\Uf::all();
    	//$id = Crypt::decrypt($id);        
        $consulta = \App\Guarnicao::find($id);
        //dd($consulta);
        $menuAtivo = "administracao";
        //dd($consulta);
    	return view('guarnicao.edit', compact('menuAtivo','consulta', 'estados'));
    }

    public function update(Request $request)
    {   
        //dd($request->all());
        $customMessages = [
            'nome.max' => 'Campo Nome deve ter no max 20 caracteres',
            'nome.min' => 'Campo Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
            'estado.required' => 'Campo obrigatório',

        
        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20', 
            'estado' => 'required',          

        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
        $consulta = \App\Guarnicao::where('uf_id', $validatedData['estado'])->count();
        $consulta1 = \App\Guarnicao::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        //dd($request->all('id'));


        if($consulta1 >= 1 and $consulta >= 1){
        \Session::flash('message', ['msg'=>'Existe Descrição nessa Guarnição!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('guarnicao.edit', compact('id'));
        }



        $guarnicao = \App\Guarnicao::find($request->id);     
        //dd($guarnicao);
        //$guarnicao = new Guarnicao;
        $guarnicao->descricao = $validatedData['nome'];
        $guarnicao->uf_id = $validatedData['estado'];
        $guarnicao->update();
        $menuAtivo = "administracao";

        //dd($a);
        return redirect()->route('guarnicao.index', compact('menuAtivo'));
    
    }

    public function destroy($id)
    {
        //$id = Crypt::decrypt($id);
        //dd($id);
        $consulta = \App\Guarnicao::find($id);
        //dd($area_id);
        //$id = Crypt::encrypt($consulta->area_id);
        $consulta->delete();
        //dd($id);
        
        $menuAtivo = "administracao";

        return redirect()->route('guarnicao.index', compact('menuAtivo'));

    }
}
