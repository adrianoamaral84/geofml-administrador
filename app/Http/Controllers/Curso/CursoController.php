<?php

namespace App\Http\Controllers\Curso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use \App\Curso;

class CursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\Curso::listAll($search);

    	//$consulta = \App\SituacaoCandidato::paginate(10);
        $menuAtivo = "administracao";

    	//dd($consulta);
    	return view('curso.index', compact('menuAtivo','search','consulta'));
    }

    public function showFormCadastra()
    {
        $menuAtivo = "administracao";
    	return view('curso.create', compact('menuAtivo'));
    }

    public function create(Request $request)
    {	
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 100 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:100',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);
        $menuAtivo = "administracao";
        $consulta = \App\Curso::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('curso.create.show', compact('menuAtivo'));
        }
        $curso = new Curso;  
    	//$uf->sigla = $validatedData['sigla'];   	 	
    	$curso->descricao = $validatedData['nome'];
    	$curso->save();
        //$menuAtivo = "administracao";

    	//dd($area);
    	\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        return redirect()->route('curso.index', compact('menuAtivo'));
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\Curso::find($id);
        $menuAtivo = "administracao";
        //dd($consulta);
        return view('curso.edit', compact('menuAtivo','consulta'));
    }
    public function update(Request $request)
    {
    	$customMessages = [
            'nome.max' => 'Campo Estado Civil deve ter no max 100 caracteres',
            'nome.min' => 'Campo Estado Civil deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',          

        ];

        $validatedData = [
            'nome' => 'required|min:3|max:100',
        ];

        $validatedData = $request->validate($validatedData, $customMessages);
        $menuAtivo = "administracao";    
        $consulta = \App\Curso::where('descricao', $validatedData['nome'])->count();
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('curso.index', compact('menuAtivo'));
        }
        $consulta = \App\Curso::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('curso.index', compact('menuAtivo'));

    	//return view('indexuf');
    }




    public function destroy($id)
    {
        //dd($id);
    	
    	$consulta = \App\Curso::find($id);
        //dd($consulta);
        
        $consulta->delete();
        $menuAtivo = "administracao";
        return redirect()->route('curso.index', compact('menuAtivo'));
    }
}
