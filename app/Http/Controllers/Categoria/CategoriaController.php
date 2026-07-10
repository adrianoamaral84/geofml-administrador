<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Categoria;
use \Crypt;

class CategoriaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {	
        //dd($request);
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\Categoria::listAll($search);

    	//$consulta = \App\Categoria::paginate(10);
    	return view('categoria.index', compact('consulta', 'search', 'consulta'));
    }

	public function showFormCadastra()
    {
        //$menuAtivo = "administracao";

    	return view('categoria.create');
    }


    public function create(Request $request)
    {
        $customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.max' => 'Nome deve ter no max 50 caracteres',
            'nome.required' => 'Campo obrigatório',
            'sigla.min' => 'Nome deve ter no min 3 caracteres',
            'sigla.max' => 'Nome deve ter no max 20 caracteres',
            'sigla.required' => 'Campo obrigatório',                       
        ];
        $validatedData = [
            'nome' => 'required|min:3|max:50',
            'sigla' => 'required|min:3|max:20',
        ];
        $validatedData = $request->validate($validatedData, $customMessages); 
        $consulta = \App\Categoria::where('descricao', $validatedData['nome'])->count();
    	if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('categoria.create.show');
        }
        $categoria = new \App\Categoria;
        $categoria->sigla = $validatedData['sigla'];
        $categoria->descricao = $validatedData['nome'];
    	$categoria->save();
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('categoria.index');
    	//return view('categoria.create');
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\Categoria::find($id);
        
        //dd($consulta);
        return view('categoria.edit', compact('consulta'));
    }
    
    public function update(Request $request)
    {
    	//dd($request->all());
    	$customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.max' => 'Nome deve ter no max 50 caracteres',
            'nome.required' => 'Campo obrigatório',
            'sigla.min' => 'Nome deve ter no min 3 caracteres',
            'sigla.max' => 'Nome deve ter no max 20 caracteres',
            'sigla.required' => 'Campo obrigatório',                       
        ];
        $validatedData = [
            'nome' => 'required|min:3|max:50',
            'sigla' => 'required|min:3|max:20',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);   
        $consulta1 = \App\Categoria::where('sigla', $validatedData['sigla'])->count();
        $consulta = \App\Categoria::where('descricao', $validatedData['nome'])->count();
        //dd($request->all('id'));
    	if($consulta >= 1 or $consulta1 >= 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('categoria.edit', compact('id'));
        }

        $consulta = \App\Categoria::find($request->id);
        $consulta->sigla = $validatedData['sigla'];   
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        //$menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('categoria.index');

    	//return view('indexuf');
    }

    public function destroy($id)
    {
    	$consulta = \App\Categoria::find($id);
        //dd($id);        
        $consulta->delete();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'Deletado com sucesso!', 'class'=>'success']);
        return redirect()->route('categoria.index');
    }



}
