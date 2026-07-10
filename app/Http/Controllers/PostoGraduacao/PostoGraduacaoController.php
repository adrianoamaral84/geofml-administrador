<?php

namespace App\Http\Controllers\PostoGraduacao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;
use App\Forca;

class PostoGraduacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {	
        //dd('ok');
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $forcas = \App\Forca::all();

    	//$consulta = \App\Categoria::paginate(10);
    	return view('postograduacao.index', compact('forcas', 'search'));
    }

    public function list($id = null)
    {   
        //$id = Crypt::decrypt($id);        
        $id = 3;
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\PostoGraduacao::where('forca_id', $id)->paginate(50);
        $forca = \App\Forca::find($id);

       
        //$consulta = \App\Categoria::paginate(10);
        return view('postograduacao.indexposto', compact('consulta', 'search', 'forca'));
    }

	public function showFormCadastra($id)
    {
        //$menuAtivo = "administracao";
        $id = Crypt::decrypt($id);        
        $situacoes = \App\Situacao::all();
        //dd($id);
    	return view('postograduacao.create', compact('id', 'situacoes'));
    }


    public function create(Request $request)
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
            'situacao'    => 'required',
            'id'    => 'required',
        ];
        $validatedData = $request->validate($validatedData, $customMessages); 
        
        $consulta = \App\PostoGraduacao::where('descricao', $validatedData['nome'])->count();
    	
        if($consulta == 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        
        return redirect()->back();
        
        }
        $id = $validatedData['id'];
        $categoria = new \App\PostoGraduacao;
        $categoria->sigla = $validatedData['sigla'];
        $categoria->descricao = $validatedData['nome'];
        $categoria->forca_id = $validatedData['id'];
        $categoria->situacao_id = $validatedData['situacao'];        

    	$categoria->save();
    	
        //\Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);

        $consulta = \App\PostoGraduacao::where('forca_id', $validatedData['id'])->paginate(50);
        $forca = \App\Forca::find($validatedData['id']);

        //$consulta = \App\Categoria::paginate(10);
        return view('postograduacao.indexposto', compact('consulta', 'forca', 'id'));
        //return redirect()->route('postograduacao.index');
    	//return view('categoria.create');
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\PostoGraduacao::find($id);
        $situacoes = \App\Situacao::all();
        //dd($consulta);
        return view('postograduacao.edit', compact('consulta', 'situacoes'));
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
        $consulta1 = \App\PostoGraduacao::where('sigla', $validatedData['sigla'])->count();
        $consulta = \App\PostoGraduacao::where('descricao', $validatedData['nome'])->count();
        //dd($request->all('id'));
    	/*
        if($consulta >= 1 or $consulta1 >= 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        $id = Crypt::encrypt($request->id);
        return redirect()->route('postograduacao.edit', compact('id'));
        }
        */
        //$id = Crypt::encrypt($request->id);
        $consulta = \App\PostoGraduacao::find($request->id);
        $consulta->sigla = $validatedData['sigla'];   
        $consulta->descricao = $validatedData['nome'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        //$menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->back();
        //return redirect()->route('postograduacao.index');
        //return redirect()->route('postograduacao.list', compact('id'));

    	//return view('indexuf');
    }

    public function destroy($id)
    {
    	$consulta = \App\PostoGraduacao::find($id);
        //dd($id);        
        $consulta->delete();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'Deletado com sucesso!', 'class'=>'success']);
        return redirect()->route('postograduacao.list');
    }

}
