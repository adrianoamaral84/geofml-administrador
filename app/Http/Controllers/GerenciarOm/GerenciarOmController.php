<?php

namespace App\Http\Controllers\GerenciarOm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GerenciarOm;
use \Crypt;
use \App\Forca;
use \App\Cidade;

class GerenciarOmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {   
        //dd($request);
        $forcas = \App\Forca::all();
        $search = isset($request->search) && $request->search != null ? $request->search : null;
        $consulta = \App\GerenciarOm::listAll($search);
        return view('gerenciarom.index', compact('consulta', 'search', 'forcas'));
    }

    public function listOMs($id = null)
    {   
        
        //$id = Crypt::decrypt($id);        
        //dd($id);
        $id = 3;
        $oms = \App\GerenciarOm::where('forca_id', $id)->get();
        $forca = \App\Forca::find($id);
       

        
        //dd($forca);
        
        //dd($oms);
        //return route('');
        return view('gerenciarom.oms.index', compact('oms', 'forca'));
    }

    public function showFormCadastra($id)
    {   
        $id = Crypt::decrypt($id);
        //dd($id);
        //$menuAtivo = "administracao";
        $cidades = Cidade::all();
        //$forcas = Forca::all();


        return view('gerenciarom.oms.create', compact('cidades', 'id'));
    }


    public function create(Request $request)
    {
        
        $customMessages = [
            'sigla.min' => 'Nome deve ter no min 2 caracteres',
            'sigla.max' => 'Nome deve ter no max 100 caracteres',
            'sigla.required' => 'Campo obrigatório',
            'cidade.required' => 'Campo obrigatório',  

        ];
        $validatedData = [
            'sigla' => 'required|min:2|max:100',
            'cidade' => 'required',
            'id' => 'required',
            ];
        $validatedData = $request->validate($validatedData, $customMessages); 
        
        $consulta = \App\GerenciarOm::where('sigla', $validatedData['sigla'])->count();
        $consulta1 = \App\GerenciarOm::where('forca_id', $validatedData['id'])->count();
        $consulta2 = \App\GerenciarOm::where('cidade_id', $validatedData['cidade'])->count();
        $consultaFull = \App\GerenciarOm::where('sigla', $validatedData['sigla'])->where('forca_id', $validatedData['id'])->where('cidade_id', $validatedData['cidade'])->count();
        //dd($consulta2);
        /*
        dd($consulta);
        if($consulta || $consulta1 || $consulta2){
            dd("os 3");
        }
        */
        $id = $request->all('id');
        $id = Crypt::encrypt($id);
        //dd($id);

        if($consultaFull){
        \Session::flash('message', ['msg'=>'Já existe registro cadastrado!', 'class'=>'danger']);
        //return redirect()->back()->withErrors(['Já existe registro cadastrado!']);
        return redirect()->back();
        //return redirect()->route('gerenciarom.create.show', compact('id'));
        
        }
        /*
        if($consulta and $consulta1 and $consulta2){
        
        \Session::flash('message', ['msg'=>'Já existe registro com esses dados!', 'class'=>'danger']);
        //return redirect()->back()->withErrors(['msg', 'The Message']);
        return redirect()->route('gerenciarom.create.show');
        
        }
        */

        /*
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe registro com essa sigla!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.create.show');
        }
        */



        /*
        if($consulta1 == 2){

        \Session::flash('message', ['msg'=>'Esse CODOM já está em uso! Utilize outro!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.create.show');
        }
        */
       
        /*       
        if($consulta == 1){
        \Session::flash('message', ['msg'=>'Existe registro com essa descrição!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.create.show');
        }
        */




        $categoria = new \App\GerenciarOm;
        $categoria->sigla = $validatedData['sigla'];
        $categoria->forca_id = $validatedData['id'];
        $categoria->cidade_id = $validatedData['cidade'];
        
        $categoria->save();
        
        \Session::flash('message', ['msg'=>'Cadastrado com sucesso!', 'class'=>'success']);
        
        return redirect()->route('gerenciarom.listOMs');
        //dd($id);
        //return redirect()->route('gerenciarom.listOMs', compact('id'));
        
        //return view('conselhoprofissional.create');
    }

    public function edit($id)
    {   


        $id = Crypt::decrypt($id);        
        $consulta = \App\GerenciarOm::find($id);

        $cidades = Cidade::all();
        $forcas = Forca::all();

        //dd($consulta);
        return view('gerenciarom.edit', compact('consulta', 'cidades', 'forcas'));
    }
    
    public function update(Request $request)
    {
        //dd($request->all());
        
        $customMessages = [
            'sigla.min' => 'Nome deve ter no min 2 caracteres',
            'sigla.max' => 'Nome deve ter no max 100 caracteres',
            'sigla.required' => 'Campo obrigatório',
            'forca.required' => 'Campo obrigatório',
            'cidade.required' => 'Campo obrigatório',                       
        ];
        $validatedData = [
            'sigla' => 'required|min:2|max:100',
            'cidade' => 'required',
            'forca' => 'required',           
        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
        $consulta = \App\GerenciarOm::where('sigla', $validatedData['sigla'])->count();
        $consulta1 = \App\GerenciarOm::where('forca_id', $validatedData['forca'])->count();
        $consulta2 = \App\GerenciarOm::where('cidade_id', $validatedData['cidade'])->count();

        $consultaFull = \App\GerenciarOm::where('sigla', $validatedData['sigla'])->where('forca_id', $validatedData['forca'])->where('cidade_id', $validatedData['cidade'])->count();

        //dd($consultaFull);
        
        $id = Crypt::encrypt($request->id);

        if($consultaFull){
             \Session::flash('message', ['msg'=>'Já existe registro com esses dados!', 'class'=>'danger']);
        //return redirect()->back()->withErrors(['msg', 'The Message']);
         return redirect()->back();
        }


        /*
        if($consulta and $consulta1 and $consulta2){
        
        \Session::flash('message', ['msg'=>'Já existe registro com esses dados!', 'class'=>'danger']);
        //return redirect()->back()->withErrors(['msg', 'The Message']);
        //return redirect()->route('gerenciarom.edit');
        return redirect()->back();
        
        }
        */


        //dd($request->all('id'));
        /*
        if($consulta >= 1){

        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.edit', compact('id'));
        }
        if($consulta1 >= 1){

        \Session::flash('message', ['msg'=>'Existe registro com essa sigla!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.edit', compact('id'));
        }
        if($consulta2 >= 1){

        \Session::flash('message', ['msg'=>'Esse CODOM já está em uso! Utilize outro!', 'class'=>'danger']);
        return redirect()->route('gerenciarom.edit', compact('id'));
        }
        */

        $consulta = \App\GerenciarOm::find($request->id);
        //dd($consulta);
        $consulta->sigla = $validatedData['sigla'];   
        $consulta->forca_id = $validatedData['forca'];
        $consulta->cidade_id = $validatedData['cidade'];
        //$consulta->sigla = $validatedData['sigla'];        
        $consulta->update();
        
        //$menuAtivo = "administracao"; 
        \Session::flash('message', ['msg'=>'Atualizado com sucesso!', 'class'=>'success']);
        return redirect()->route('gerenciarom.listOMs');

        //return view('indexuf');
    }

    public function destroy($id)
    {
        $consulta = \App\GerenciarOm::find($id);
        //dd($id);        
        $consulta->delete();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'Deletado com sucesso!', 'class'=>'success']);
        return redirect()->route('gerenciarom.listOMs');
    }
}
