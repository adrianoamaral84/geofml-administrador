<?php
namespace App\Http\Controllers\SubArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Crypt;

class SubAreaController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {

        $search = isset($request->search) && $request->search != null ? $request->search : null;
       	$id = Crypt::decrypt($id); 
        $consulta = \App\SubArea::listAll($search, $id);

    	$area = \App\Area::find($id);
    	//dd($area->descricao);
    	//$consulta = $area->subarea()->paginate(10);
    	$descricao = $area->descricao;
    	$menuAtivo = "administracao";
    	return view('area.subarea.index', compact('consulta',  'search', 'menuAtivo', 'id', 'descricao'));

	

	}

	public function ShowFormCreate($id)
    {	
    	//$id = 1;
    	//$id = Crypt::decrypt($id);
    	$menuAtivo = "administracao";
    	return view('area.subarea.create', compact('menuAtivo', 'id'));
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
    	//dd($request);
    	//dd($id);
        $area = \App\Area::find(Crypt::decrypt($request->id));                
        $consulta = $area->subarea()->where('descricao', $validatedData['nome'])->count();        
        //dd($consulta);        
        if($consulta >= 1){
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);        
        $id = $request->id;
        //dd($id);
        //$id = Crypt::encrypt($request->id);
        //dd($id);
        //$iduf = Crypt::encrypt($request->iduf);

        return redirect()->route('subarea.create.show', compact('id'));
        }

    	$id = Crypt::decrypt($request->id);
    	//dd($request->descricao);
    	$area = \App\Area::find($id);    	
    	
    	$a = $area->subarea()->create([
    		'descricao' => $validatedData['nome'],
    	]);

    	$id = Crypt::encrypt($id);
    	//dd($id);
    	$menuAtivo = "administracao";

    	//dd($a);
		return redirect()->route('subarea.index', compact('menuAtivo', 'id'));
    	//dd($a);
    	
    }


    public function edit($id)
    {
    	$id = Crypt::decrypt($id); 
    	$consulta = \App\SubArea::find($id);
    	$menuAtivo = "administracao";
        
    	return view('area.subarea.edit', compact('consulta', 'menuAtivo'));
    }

    
    public function subareaupdate(Request $request)
    {
    	//dd($request->all());
    	$customMessages = [
            'nome.min' => 'Nome deve ter no min 3 caracteres',
            'nome.required' => 'Campo obrigatório',
            
        ];

        $validatedData = [
            'nome' => 'required|min:3',
        ];

        $validatedData = $request->validate($validatedData, $customMessages); 
        $area = \App\Area::find($request->id_area);                       
        //dd($area);  
        $consulta = $area->subarea()->where('descricao', $validatedData['nome'])->count();        
              
        if($consulta >= 1){
        \Session::flash('message', ['msg'=>'Existe registro com esse nome!', 'class'=>'danger']);        
        $id = Crypt::encrypt($request->id);
        return redirect()->route('subarea.create.show', compact('id'));
        }

        $consulta = \App\SubArea::find($request->id);     
        $consulta->descricao = $validatedData['nome'];
        $consulta->update();
        $id = $consulta->area_id;
        $id = Crypt::encrypt($id);
        //dd($id);
        //dd();
        $menuAtivo = "administracao";
        \Session::flash('message', ['msg'=>'SubArea Atualizada com sucesso.', 'class'=>'success']);
        return redirect()->route('subarea.index', compact('id','menuAtivo'));
    }

    public function deleteSubArea($id)
    {
    	//$id = Crypt::decrypt($id);
    	//dd($id);
        $consulta = \App\SubArea::find($id);
        //dd($area_id);
        $id = Crypt::encrypt($consulta->area_id);
        $consulta->delete();
        //dd($id);
        
        $menuAtivo = "administracao";

        return redirect()->route('subarea.index', compact('menuAtivo', 'id'));

    }
}
