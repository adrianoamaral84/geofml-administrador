<?php

namespace App\Http\Controllers\Uf\Cidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\Cidade;

class CidadeController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request, $id)
    {


        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $id = Crypt::decrypt($id);      
        $consulta = \App\Cidade::listAll($search, $id);
        //dd($consulta);
    	//dd($id);
        $uf = \App\Uf::find($id);
        //dd($uf);
    	
        //dd($cidade->descricao);
    	//$consulta = $uf->cidade()->paginate(10);
    	
        //dd($consulta);
    	$descricao = $uf->descricao;
    	
        //dd($descricao);'search', 'consulta'
    	//

    	$menuAtivo = "administracao";
    	return view('uf.cidade.index', compact('consulta', 'search', 'menuAtivo', 'id', 'descricao'));

    }

    public function ShowFormCreate($id, $iduf)
    {	

        //dd($id);
    	//$id = 1;
    	//$id = Crypt::decrypt($id);
    	$menuAtivo = "administracao";
        //$iduf = Crypt::decrypt($iduf);
    	return view('uf.cidade.create', compact('menuAtivo', 'id', 'iduf'));
    }

    public function create(Request $request)
    {
        //dd($request->all());
        $customMessages = [
            'nome.max' => 'Campo Nome deve ter no max 20 caracteres',
            'nome.min' => 'Campo Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
        
        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20',          
        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
        $iduf = Crypt::decrypt($request->id);
        $uf = \App\Uf::find($iduf);        
        //dd($uf);
        $consulta = $uf->cidade()->where('descricao', $validatedData['nome'])->count();        
        //dd($consulta);        
        //$consulta = \App\Cidade::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        if($consulta == 1){
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);        
        $id = $request->id;
        //dd($id);
        //$id = Crypt::encrypt($request->id);
        $iduf = Crypt::encrypt($request->iduf);

        return redirect()->route('cidade.create.show', compact('id', 'iduf'));
        }

        //dd($id);
        $id = Crypt::decrypt($request->id);
        //dd($request->descricao);
        $uf = \App\Uf::find($id);       
        
        $a = $uf->cidade()->create([
            'descricao' => $validatedData['nome'],
        ]);

        $id = Crypt::encrypt($id);
        //dd($id);
        $menuAtivo = "administracao";

        //dd($a);
        return redirect()->route('cidade.index', compact('menuAtivo', 'id'));
        //dd($a);
        
    }

    public function edit($id, $iduf)
    {
        //dd($id);

        //$iduf = 1;
        $id = Crypt::decrypt($id); 
        $iduf = Crypt::decrypt($iduf); 
        
        $consulta = \App\Cidade::find($id);
        $menuAtivo = "administracao";
        
        return view('uf.cidade.edit', compact('consulta', 'menuAtivo', 'iduf'));
    }


    public function cidadeupdate(Request $request)
    {
        
        //dd($request);

        $customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.max' => 'Nome deve ter no max 20 caracteres',            
            'nome.required' => 'Campo obrigatório',
            
        ];

        $validatedData = [
            'nome' => 'required|min:3|max:20',
        ];
        //dd($request);
        $validatedData = $request->validate($validatedData, $customMessages);
        $uf = \App\Uf::find($request->iduf);        
        
        $consulta = $uf->cidade()->where('descricao', $validatedData['nome'])->count();
        
        //dd($consulta);
        
        //$consulta = \App\Cidade::where('descricao', $validatedData['nome'])->count();
        //dd($consulta);
        if($consulta == 1){
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);        
        $id = Crypt::encrypt($request->id);
        $iduf = Crypt::encrypt($request->iduf);

        return redirect()->route('cidade.edit', compact('id', 'iduf'));
        }
      



        $consulta = \App\Cidade::find($request->id);     
        //dd($consulta);
        $consulta->descricao = $validatedData['nome'];
        $consulta->update();

        $id = $consulta->uf_id;
        //dd($id);
        $id = Crypt::encrypt($id);
        //dd($id);
        //dd();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'Ciadade Atualizada com sucesso.', 'class'=>'success']);
        return redirect()->route('cidade.index', compact('id','menuAtivo'));
    }

    public function delete($id)
    {
        //$id = Crypt::decrypt($id);
        //dd($id);
        $consulta = \App\Cidade::find($id);
        //dd($area_id);
        $id = Crypt::encrypt($consulta->uf_id);
        $consulta->delete();
        //dd($id);
        
        $menuAtivo = "administracao";

        return redirect()->route('cidade.index', compact('menuAtivo', 'id'));

    }
}
