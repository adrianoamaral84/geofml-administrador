<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Cidade;
use Auth;
use Crypt;
use \App\User;
use \App\Situacao;
use \App\Nivel;
use Carbon\Carbon;
use \App\Http\Controllers\Mail\MailController;
use Illuminate\Support\Facades\Hash;
use Laratrust;
use Config;
use App\Services\DocumentoService;
use Illuminate\Support\Facades\DB;








class HomeController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth')->except('solicitaacesso', 'pedido', 'reload', 'updatePassword', 'updatePasswordteste');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $message = \App\Message::newMessage(Auth::user()->id);
        $count = \App\Message::countMsg(Auth::user()->id);
        $userCPF = Auth::user()->cpf;
        $hoje = Carbon::today();

        // Resumo operacional da hospedagem
        $totalUnidades = \App\UnidadeHabitacional::count();
        // Regra legada do sistema: checkin = 1 significa hospedado; checkin = 2 significa checkout concluído.
        $quartosOcupados = \App\Hospede::where('checkin', 1)
            ->whereNotNull('und_habitacionais_id')
            ->distinct('und_habitacionais_id')
            ->count('und_habitacionais_id');

        $hospedesAtuais = \App\Hospede::where('checkin', 1)
            ->selectRaw('COALESCE(SUM(adulto), 0) + COALESCE(SUM(crianca), 0) AS total')
            ->value('total');

        $taxaOcupacao = $totalUnidades > 0
            ? round(($quartosOcupados / $totalUnidades) * 100, 1)
            : 0;


        // Movimentos efetivamente realizados, e não apenas previstos no período da reserva.
        $checkinsHoje = \App\Hospede::whereNotNull('checkin_at')
            ->whereDate('checkin_at', $hoje)
            ->count();
        $checkoutsHoje = \App\Hospede::where('checkin', 2)
            ->whereNotNull('checkout_at')
            ->whereDate('checkout_at', $hoje)
            ->count();
        $reservasPendentes = \App\Hospede::where('status', 4)->count();


        // Indicadores financeiros. Como não há data própria de pagamento,
        // os valores recebidos são associados ao mês em que ocorreu o checkout.
        $inicioMesAtual = $hoje->copy()->startOfMonth();
        $fimMesAtual = $hoje->copy()->endOfMonth();

        $recebidoMes = (float) \App\Hospede::where('checkin', 2)
            ->whereNotNull('checkout_at')
            ->whereBetween('checkout_at', [$inicioMesAtual, $fimMesAtual])
            ->sum(DB::raw('COALESCE(valor_pago, 0)'));

        $valorPendente = (float) \App\Hospede::whereIn('checkin', [0, 1])
            ->sum(DB::raw('COALESCE(valor_restante, 0)'));

        $hospedagensPagasMes = \App\Hospede::where('checkin', 2)
            ->whereNotNull('checkout_at')
            ->whereBetween('checkout_at', [$inicioMesAtual, $fimMesAtual])
            ->where('valor_pago', '>', 0)
            ->count();

        $ticketMedioMes = $hospedagensPagasMes > 0
            ? round($recebidoMes / $hospedagensPagasMes, 2)
            : 0;

        $extrasMes = (float) DB::table('checkout')
            ->whereBetween('created_at', [$inicioMesAtual, $fimMesAtual])
            ->sum(DB::raw('COALESCE(valor, 0)'));

        // Movimentação de hoje: entradas, saídas e hóspedes em permanência.
        $movimentacaoHoje = \App\Hospede::with(['usuario', 'undHB', 'status_hospedagem'])
            ->where(function ($query) use ($hoje) {
                $query->whereDate('data_inicio', $hoje)
                    ->orWhereDate('data_termino', $hoje)
                    ->orWhere(function ($periodo) use ($hoje) {
                        $periodo->whereDate('data_inicio', '<=', $hoje)
                            ->whereDate('data_termino', '>=', $hoje)
                            ->where('checkin', 1);
                    });
            })
            ->orderBy('data_inicio')
            ->limit(12)
            ->get();

        // Série mensal dos últimos 12 meses.
        $meses = [];
        $ocupacaoMensal = [];
        $checkinsMensais = [];
        $checkoutsMensais = [];
        $novosUsuarios = [];
        $recebimentosMensais = [];
        $pendenciasMensais = [];

        for ($i = 11; $i >= 0; $i--) {
            $inicioMes = Carbon::now()->subMonths($i)->startOfMonth();
            $fimMes = Carbon::now()->subMonths($i)->endOfMonth();
            $diasMes = $inicioMes->daysInMonth;
            $diariasDisponiveis = $totalUnidades * $diasMes;

             $diariasOcupadas = 0;

$diaAtual = $inicioMes->copy()->startOfDay();
$ultimoDia = $fimMes->copy()->startOfDay();

while ($diaAtual->lte($ultimoDia)) {
    $unidadesOcupadasNoDia = \App\Hospede::whereNotNull('und_habitacionais_id')
        ->whereDate('data_inicio', '<=', $diaAtual)
        ->whereDate('data_termino', '>', $diaAtual)
        ->whereIn('checkin', [1, 2])
        ->distinct()
        ->count('und_habitacionais_id');

    $diariasOcupadas += min($unidadesOcupadasNoDia, $totalUnidades);

    $diaAtual->addDay();
}

            $meses[] = ucfirst($inicioMes->locale('pt_BR')->translatedFormat('M/y'));
            $ocupacaoMensal[] = $diariasDisponiveis > 0
                ? round(($diariasOcupadas / $diariasDisponiveis) * 100, 1)
                : 0;
            $checkinsMensais[] = \App\Hospede::whereNotNull('checkin_at')
                ->whereBetween('checkin_at', [$inicioMes, $fimMes])
                ->count();
            $checkoutsMensais[] = \App\Hospede::where('checkin', 2)
                ->whereNotNull('checkout_at')
                ->whereBetween('checkout_at', [$inicioMes, $fimMes])
                ->count();
            $novosUsuarios[] = \App\User::whereBetween('created_at', [$inicioMes, $fimMes])->count();
            $recebimentosMensais[] = (float) \App\Hospede::where('checkin', 2)
                ->whereNotNull('checkout_at')
                ->whereBetween('checkout_at', [$inicioMes, $fimMes])
                ->sum(DB::raw('COALESCE(valor_pago, 0)'));

           $pendenciasMensais[] = (float) \App\Hospede::whereBetween(
        'data_inicio',
        [$inicioMes->toDateString(), $fimMes->toDateString()]
    )
    ->whereIn('checkin', [0, 1])
    ->sum(DB::raw('COALESCE(valor_restante, 0)'));
        }

        $hospedagensPorStatus = DB::table('hospedagem')
            ->leftJoin('status_hospedagem', 'hospedagem.status', '=', 'status_hospedagem.id')
            ->selectRaw("COALESCE(status_hospedagem.status, 'Sem situação') AS situacao, COUNT(*) AS total")
            ->groupBy('hospedagem.status', 'status_hospedagem.status')
            ->orderByDesc('total')
            ->get();

        // Indicadores administrativos de usuários.
        $totalUsuarios = \App\User::count();
        $usuariosAtivos = \App\User::where('status', 1)->count();
        $usuariosPreCadastro = \App\User::where('status', 5)->count();
        $usuariosInativos = \App\User::whereIn('status', [2, 6])->count();

        $usuariosPorStatus = DB::table('user')
            ->leftJoin('status', 'user.status', '=', 'status.id')
            ->selectRaw("COALESCE(status.status, 'Sem situação') AS situacao, COUNT(*) AS total")
            ->groupBy('user.status', 'status.status')
            ->orderByDesc('total')
            ->get();

        return view('home', compact(
            'userCPF', 'message', 'count', 'totalUnidades', 'quartosOcupados',
            'hospedesAtuais', 'taxaOcupacao', 'checkinsHoje', 'checkoutsHoje',
            'reservasPendentes', 'movimentacaoHoje', 'meses', 'ocupacaoMensal',
            'checkinsMensais', 'checkoutsMensais', 'hospedagensPorStatus',
            'totalUsuarios', 'usuariosAtivos', 'usuariosPreCadastro',
            'usuariosInativos', 'novosUsuarios', 'usuariosPorStatus',
            'recebidoMes', 'valorPendente', 'ticketMedioMes', 'extrasMes',
            'recebimentosMensais', 'pendenciasMensais'
        ));
    }

    public function logout(Request $request){

        Auth::logout();
        return redirect('/login');
        
    }

    public function administrador(){

        $message = \App\Message::newMessage(Auth::user()->id);
        $count = \App\Message::countMsg(Auth::user()->id);
        //dd($count);


        $userCPF = Auth::user()->cpf;
       
        return view('home', compact('userCPF', 'message', 'count'));
    }
    public function homehome()
    {
        
        
        if(Laratrust::hasRole('administrador_especial')) {
            //dd('ADMIN');
            return redirect('/admin');
        }
        if(Laratrust::hasRole('administrador_geral')) {
            //dd('USER');
            return redirect('/admin');
        }
        if(Laratrust::hasRole('atendente')) {
            //dd('USER');
            return redirect('/atendente');
        }
        if(Laratrust::hasRole('hospede')) {
            //dd('USER');
            return redirect('/hospede');
        }
        if(Laratrust::hasRole('precadastro')) {
        
        return redirect()->route('precadastro');
          //dd('pre');
            //return view('usuario.consulta', compact('menuAtivo', 'consulta', 'search'));
        }
    //dd('home');
    //return view('home');
    }

    /*public function precadastro()
    {
        
        $user = Auth::user();
        $id = $user->id;
        $count = User::where('id', $id)->count();



        if($count == 1){
        $user = User::findOrFail($id);
        $perfis = \App\Models\Role::all();
        $oms = \App\GerenciarOm::all();
        $postos = \App\PostoGraduacao::all();
        $forcas = \App\Forca::all();
        $ufs = \App\Uf::all();
        $cidades = \App\Cidade::all();
        $situacoes = Situacao::all();
        $nivels = Nivel::all();
        $hoje = date('Y-m-d');
        }else{
            abort('404');
        }

        if($user->indeterminado == 1){
            $user->validade = null;
        }
        $bin = base64_decode($user->documento, true);
        
        $min = date("Y-m-d");

        return view('pedido.precadastro', compact('perfis', 'oms', 'postos', 'forcas', 'ufs', 'cidades', 'situacoes', 'user', 'nivels', 'hoje', 'min'));
    
    }*/

        public function precadastro(Request $request) {
    $search = $request->get('search');
    $dataAtual = \Carbon\Carbon::now()->locale('pt_BR');

    // Filtramos pela lista de usuários com status de pré-cadastro (status 5)
    $consulta = \App\User::where('status', '5');

    if ($search) {
        $cleanSearch = preg_replace('/[^0-9]/', '', $search);

        if (is_numeric($cleanSearch) && strlen($cleanSearch) == 11) {
            $consulta->where('cpf', $cleanSearch);
        } else {
            $consulta->whereRaw("name COLLATE utf8_general_ci LIKE ?", ["%{$search}%"]);
        }
    }

    $consulta = $consulta->get();

    // Enviamos para a view de listagem que você confirmou que funciona
    return view('usuario.listaPreCadastro', compact('consulta', 'dataAtual'));
}

    public function solicitaacesso()
    {
        
        $menuAtivo = "administracao";
        return view('solicitaacesso.index');
    }

    public function pedido(Request $request)
    {  

        date_default_timezone_set('America/Sao_Paulo');

        if (isset($request['cpf'])) 
            $request['cpf'] = str_replace([".","-"], "", $request['cpf']);

        if ($request['celular'] != NULL) 
            $request['celular'] = str_replace(["(",")"," ","-"], "", $request['celular']);

        $customMessages = [
            'nome.min' => 'Nome Guerra deve ter no min 2 caracteres',
            'nome.max' => 'Nome Guerra deve ter no max 100 caracteres',            
            'nome.required' => 'Campo obrigatório',

            'email.min' => 'E-mail deve ter no min 5 caracteres',
            'email.max' => 'E-mail deve ter no max 100 caracteres',            
            'email.required' => 'Campo obrigatório',

            'cpf.min' => 'CPF deve ter no min 10 caracteres',
            'cpf.max' => 'CPF deve ter no max 11 caracteres',            
            'cpf.required' => 'Campo obrigatório',

           
            'celular.max' => 'Celular deve ter no max 11 caracteres',            
            'celular.required' => 'Campo obrigatório',

        ];

        $validatedData = [
            'nome' => 'required|max:100|min:2',
            'email' => 'required|email|max:100|min:5',
            'cpf' => 'required|max:11|min:10',
            'celular' => 'required|max:11',

        ];
        $validatedData = $request->validate($validatedData, $customMessages); 

       
        
        if(!$this->validarCPF($validatedData['cpf'])){
            return back()->withInput()->withErrors(['CPF inválido.']);
        }
        
        if(!$this->verificarCPFCadastradoPedido($validatedData['cpf'])){
            return back()->withInput()->withErrors(['Este CPF já está cadastrado no sistema.']);
        }
        

        // // Validação se e-mail já está cadastrado
        if(!$this->verificarEmailCadastrado($validatedData['email'])){
            return back()->withInput()->withErrors(['Este E-mail já está cadastrado no sistema.']);
        }

        //dd($validatedData['cpf']);
        $usuario = new User();
        $usuario->name = strtoupper($validatedData['nome']);
        $usuario->email = $validatedData['email'];
        $usuario->cpf = $validatedData['cpf'];
        $usuario->telefone = $validatedData['celular'];
        $usuario->status = 5;
        $usuario->perfil_id = 5;

        $usuario->password = Hash::make($usuario->cpf);
        //$usuario->status = 1;
        //$usuario->save();
        //dd($usuario->id);
        if($usuario->save()){
        $usuario->syncRoles(['5']);
        $id = Crypt::encrypt($usuario->id);
        \Illuminate\Support\Facades\Mail::queue(new \App\Mail\newLaravelTips($usuario));
        \Session::flash('message', ['msg'=>'Enviamos um e-mail com dados de acesso ao sistema!', 'class'=>'success']);
        
        return redirect('/solicitaacesso')->with('Enviamos um e-mail com dados de acesso ao sistema!');

        //return redirect()->route('envia.login.senha', compact('id'));

        //$mail = new MailController();
        //return $mail->envialoginSenha($id);
        }











      \Session::flash('message', ['msg'=>"Pedido Realizado com sucesso. E-mail de confirmação enviado para $usuario->email!", 'class'=>'success']);
      //return redirect()->route('solicitaacesso');






    }


    /*public function listaPedidos()
    {
        $dataAtual = Carbon::now()->locale('pt_BR');
          
        $consulta = \App\User::where('status', '3')->get();
        //dd($consulta);
        return view('pedido.index', compact('consulta'));
    }*/

        public function listaPedidos(Request $request) {
    $search = $request->get('search');
    $dataAtual = Carbon::now()->locale('pt_BR');

    // Iniciamos a query filtrando pelo status '3' (Aguardando)
    $consulta = \App\User::where('status', '3');

    if ($search) {
        // Limpa caracteres especiais para testar se é busca por CPF
        $cleanSearch = preg_replace('/[^0-9]/', '', $search);

        if (is_numeric($cleanSearch) && strlen($cleanSearch) == 11) {
            $consulta->where('cpf', $cleanSearch);
        } else {
            // Busca por Nome ignorando acentos
            $consulta->whereRaw("name COLLATE utf8_general_ci LIKE ?", ["%{$search}%"]);
        }
    }

    $consulta = $consulta->get();

    // Conforme sua imagem, a view correta é 'pedido.index'
    return view('pedido.index', compact('consulta', 'dataAtual'));
}


    public function listaCidadePorUF($id){
        //dd($id);
        $cidade = Cidade::where('uf_id', $id);
        return $cidade;
        dd($cidade);
        return Cidade::where('uf_id', $id)->first;

    }

    public function finalizarcadastro($id){
        /*
         if(!\Gate::allows('isadministrador')){
            abort(403, "Desculpa, você não tem autorização!");
        }
        */
        //dd('OK');
        date_default_timezone_set('America/Sao_Paulo');

        $id = Crypt::decrypt($id);
        $count = User::where('id', $id)->count();
        //dd($count);
        if($count == 1){
        $user = User::find($id);
        //dd($user);
        $perfis = \App\Models\Role::all();
        $oms = \App\GerenciarOm::all();
        $postos = \App\PostoGraduacao::all();
        $forcas = \App\Forca::all();
        $ufs = \App\Uf::all();
        $cidades = \App\Cidade::all();
        $situacoes = Situacao::all();

        return view('pedido.novo', compact('perfis', 'oms', 'postos', 'forcas', 'ufs', 'cidades', 'situacoes', 'user'));
        }else{
            return redirect('/');
        }
        

        //$perfis = Perfil::getByPerfilId(Auth::user()->perfil_id);
        
       
        
        //$processos = Processo::getByComissaoId($comissao_id);
       

        
    }


    public function UserCreateNew(Request $request){

        
   
        if($request->indeterminado == 1){
            $request->validade = null;
        }
   
        date_default_timezone_set('America/Sao_Paulo');

        if($request['password'] != $request['resenha']){
             return back()->withInput()->withErrors(['Senhas não são iguais!']);
        }
        
        if($request->hasFile('documento') and !$request->hasFile('documento_verso')){
              return back()->withInput()->withErrors(['Falta um Arquivo!!']);
        }
        
        if(!$request->hasFile('documento') and $request->hasFile('documento_verso')){
              return back()->withInput()->withErrors(['Falta um Arquivo!!']);
        }

        if($request->hasFile('documento') and $request->hasFile('documento_verso')){
           
            if(!$request->file('documento')->isValid() || !$request->file('documento_verso')->isValid()){
               return back()->withInput()->withErrors(['Arquivo Inválido!']);
            }

                $type = $request->documento->extension();
                $type2 = $request->documento_verso->extension();

        if($type != 'jpg' and $type != 'jpeg' and $type != 'png' and $type != 'pdf' or $type2 != 'jpg' and $type2 != 'jpeg' and $type2 != 'png' and $type2 != 'pdf'){
            return back()->withInput()->withErrors(['Formato de Arquivo Inválido!']);
        }

        }
        
        $dataForm = $request->all();
        $dataForm['pttc'] = (!isset($dataForm['pttc']))? 0 : 1;

   
        if (isset($request['cpf'])) 
            $request['cpf'] = str_replace([".","-"], "", $request['cpf']);

        if ($request['telefone'] != NULL) 
            $request['telefone'] = str_replace(["(",")"," ","-"], "", $request['telefone']);

        if ($request['idtMil'] != NULL) 
            $request['idtMil'] = str_replace("-", "", $request['idtMil']);

        $customMessages = [
            'nome.max' => 'Nome Guerra deve ter no max 100 caracteres',            
            'nome.required' => 'Campo obrigatório',

            
            'email.max' => 'E-mail deve ter no max 100 caracteres',            
            'email.required' => 'Campo obrigatório',

            'cpf.min' => 'CPF deve ter no min 10 caracteres',
            'cpf.max' => 'CPF deve ter no max 11 caracteres',            
            'cpf.required' => 'Campo obrigatório',

            'password.min' => 'Senha deve ter no min 6 caracteres',
            'password.max' => 'CPF deve ter no max 15 caracteres',            
            'password.required' => 'Campo obrigatório',
            'resenha.min' => 'Senha deve ter no min 6 caracteres',
            'resenha.max' => 'CPF deve ter no max 15 caracteres',            
            'resenha.required' => 'Campo obrigatório',

            'uf.required' => 'Campo UF obrigatório',

            'cidade.required' => 'Campo Cidade obrigatório',

            'situacao.required' => 'Campo Situação Militar obrigatório',

            'idtMil.required' => 'Campo Idt Militar obrigatório',

            'telefone.required' => 'Campo Telefone obrigatório',
            'documento_verso.max' => 'O Documento Verso precisa ter máximo MB.',
            'documento.max' => 'O Documento Frente precisa ter máximo 4MB.',
          

        ];

        $validatedData = $request->validate([
            'nome' => 'required|max:100',
            'email' => 'required|max:100',
            'cpf' => 'required|max:11',
            'idtMil' => 'required|max:15',
            'telefone' => 'required|max:11',
            'uf' => 'required',
            'cidade' => 'required',
            'situacao' => 'required',
            'pttc'  =>  'nullable',
            'siape'  =>  'nullable',
            'nivel'  =>  'nullable',
            'password' => 'required|max:15|min:6',
            'posto'  =>  'nullable',
            'mecenas' => 'nullable|boolean',
            'resenha' => 'required|max:15|min:6',
            'documento' => 'nullable|mimes:jpeg,png,pdf|max:4000',
            'documento_verso' => 'nullable|mimes:jpeg,png,pdf|max:4000',

        ]);

        if(!$this->validarCPF($validatedData['cpf'])){
            return back()->withInput()->withErrors(['CPF inválido.']);
        }
        
        if(!$this->verificarCPFCadastrado($validatedData['cpf'], null, true)){
            return back()->withInput()->withErrors(['Este CPF já está cadastrado no sistema.']);
        }
        
        
        if(!$this->verificarEmailCadastrado($validatedData['email'], $validatedData['email'])){
            return back()->withInput()->withErrors(['Este E-mail já está cadastrado no sistema.']);
        }
        if($request['password'] != $request['resenha']){
            return back()->withInput()->withErrors(['A Senha deve ser igual a Re-Senha']);
        }     
         
        $usuario = Auth::user();
        $usuario->name = strtoupper($validatedData['nome']);
        $usuario->email = $validatedData['email'];
        $usuario->cpf = $validatedData['cpf'];
        $usuario->idtMil = $validatedData['idtMil'];
        $usuario->telefone = $validatedData['telefone'];
        $usuario->uf_id = $request['uf'];
        $usuario->cidade_id = $validatedData['cidade'];
        $usuario->situacao_id = $validatedData['situacao'];
        $usuario->pttc = (!isset($validatedData['pttc']))? 0 : 1;
        $usuario->dtUltPromo = $request['dtUltPromo'];
        $usuario->forca_id = $request['forca'];
        $usuario->om_id = $request['om'];
        $usuario->perfil_id = 5;
        $usuario->postograd_id = $validatedData['posto'];
        $usuario->siape = $validatedData['siape'];
        $usuario->status = 3;
        $usuario->mecenas = $request->mecenas ? 1 : 0;
        $usuario->validade = $request->validade;
        $usuario->indeterminado = (!isset($request->indeterminado))? 0 : 1;
        


       
        


        if($request['password'] === $request['resenha']){
            $usuario->password = Hash::make($request['password']);
        }
        
        if($request->hasFile('documento') || $request->hasFile('documento_verso')){

        /*
             $usuario->tipo_doc = $type;
                $usuario->tipo_doc_verso = $type2;
            $file_documento = $request->file('documento');
            $file_documento_verso = $request->file('documento_verso');

            $contents = $file_documento->openFile()->fread($file_documento->getSize());
            $contents = base64_encode($contents);  
            
            $contents_documento_verso = $file_documento_verso->openFile()->fread($file_documento_verso->getSize());
            $contents_documento_verso = base64_encode($contents_documento_verso);  
           
            $usuario->documento = $contents;
            $usuario->documento_verso = $contents_documento_verso; 
            */
        }
        if($usuario->update()){

        if($request->hasFile('documento')) {
        $documentoService = new DocumentoService();
        $documentoService->salvarFrente($usuario, $request->file('documento'));
    }

    if($request->hasFile('documento_verso')) {
        $documentoService->salvarVerso($usuario, $request->file('documento_verso'));
    }
             
                \Session::flash('message', ['msg'=>'Aguarde o Recebimento do E-mail de Confirmação para acessar o sistema Completo!', 'class'=>'success']);
                $usuario->syncRoles(['5']);
                return redirect()->route('usuario.home');     
    
        }else{

                \Session::flash('message', ['msg'=>'Ocorreu um erro ao salvar os dados.', 'class'=>'danger']);
                return redirect()->back();
        
        }
       
        
        

        

        /*
        if($usuario->save()){
        //$user = Pedido::where('cpf', $validatedData['cpf']);
        //$user->delete();
        }
        */


        //dd($usuario);
        
        //dd($request['cpf']);
        //dd('ok');

        
    }


    public function atualizaDadosUsuario(Request $request){

        //dd($request->all());
        if($request->indeterminado == 1){
            $request->validade = null;
        }
        date_default_timezone_set('America/Sao_Paulo');
        if($request->hasFile('documento') and !$request->hasFile('documento_verso')){
              return back()->withInput()->withErrors(['Falta um Arquivo!!']);
        }
        if(!$request->hasFile('documento') and $request->hasFile('documento_verso')){
              return back()->withInput()->withErrors(['Falta um Arquivo!!']);
        }
        if($request->hasFile('documento') and $request->hasFile('documento_verso')){
           
        if(!$request->file('documento')->isValid() || !$request->file('documento_verso')->isValid()){
               return back()->withInput()->withErrors(['Arquivo Inválido!']);
        }
        $type = $request->documento->extension();
        $type2 = $request->documento_verso->extension();
        if($type != 'jpg' and $type != 'jpeg' and $type != 'png' and $type != 'pdf' or $type2 != 'jpg' and $type2 != 'jpeg' and $type2 != 'png' and $type2 != 'pdf'){
            return back()->withInput()->withErrors(['Formato de Arquivo Inválido!']);
        }
        }
        
        $dataForm = $request->all();
        $dataForm['pttc'] = (!isset($dataForm['pttc']))? 0 : 1;

        if (isset($request['cpf'])) 
            $request['cpf'] = str_replace([".","-"], "", $request['cpf']);

        if ($request['telefone'] != NULL) 
            $request['telefone'] = str_replace(["(",")"," ","-"], "", $request['telefone']);

        if ($request['idtMil'] != NULL) 
            $request['idtMil'] = str_replace([".","-"], "", $request['idtMil']);

        $customMessages = [
            'nome.max' => 'Nome deve ter no max 100 caracteres',            
            'nome.required' => 'Campo obrigatório',
            'email.max' => 'E-mail deve ter no max 100 caracteres',            
            'email.required' => 'Campo obrigatório',
            'cpf.min' => 'CPF deve ter no min 10 caracteres',
            'cpf.max' => 'CPF deve ter no max 11 caracteres',            
            'cpf.required' => 'Campo obrigatório',
            'uf.required' => 'Campo obrigatório',
            'cidade.required' => 'Campo obrigatório',
            'situacao.required' => 'Campo obrigatório',
            'perfil_id.required' => 'Campo obrigatório',
            'dtUltPromo.required' => 'Campo obrigatório',
            'idtMil.required' => 'Campo obrigatório',
            
            'telefone.required' => 'Campo obrigatório',         
            'documento_verso.max' => 'O Documento Verso precisa ter máximo 4mb.',
            'documento.max' => 'O Documento Frente precisa ter máximo 4mb.',
        ];

        $validatedData = $request->validate([
            'nome' => 'required|max:100',
            'email' => 'required|max:100',
            'cpf' => 'required|max:11',
            'idtMil' => 'required|max:15',
            'telefone' => 'required|max:11',
            'uf' => 'required',
            'cidade' => 'required',
            'situacao' => 'required',
            'mecenas' => 'nullable|boolean',
            'pttc'  =>  'nullable',
            'siape'  =>  'nullable',
            'perfil_id'  =>  'required',
            'nivel' => 'nullable',
            'om' => 'required',
            'documento' => 'nullable|mimes:jpeg,png,pdf|max:4000',
            'documento_verso' => 'nullable|mimes:jpeg,png,pdf|max:4000',
        ]);
        
        if(!$this->validarCPF($validatedData['cpf'])){
            return back()->withInput()->withErrors(['CPF inválido.']);
        }
        
	
        if(!$this->verificarCPFCadastradoAtualizaCPF($validatedData['cpf'], $request->id)){
           return back()->withInput()->withErrors(['Este CPF já está cadastrado no sistema.']);
        }
        
        
        if(!$this->verificarEmailCadastradoUsuarios($validatedData['email'], Crypt::decrypt($request['id']) )){
            return back()->withInput()->withErrors(['Este E-mail já está cadastrado no sistema.']);
        }
        
        $usuario =  User::findOrFail(Crypt::decrypt($request['id']));
        $usuario->name = strtoupper($validatedData['nome']);
        $usuario->email = $validatedData['email'];
        $usuario->cpf = $validatedData['cpf'];
        $usuario->idtMil = $validatedData['idtMil'];
        $usuario->telefone = $validatedData['telefone'];
        $usuario->uf_id = $validatedData['uf'];
        $usuario->cidade_id = $validatedData['cidade'];
        $usuario->situacao_id = $validatedData['situacao'];
        $usuario->pttc = (!isset($validatedData['pttc']))? 0 : 1;
        $usuario->nivel = $validatedData['nivel'];
        $usuario->postograd_id = $request['posto'];
        $usuario->siape = $validatedData['siape'];
        $usuario->mecenas = $validatedData['mecenas'] ? 1 : 0;
        $usuario->om_id = $validatedData['om'];
        $usuario->validade = $request->validade;
        $usuario->indeterminado = (!isset($request->indeterminado))? 0 : 1;
        if(isset($request->motivo)){
            //dd('ok');
            $usuario->motivo_id = $request->motivo;
        }else{
            $usuario->motivo_id =  null;
        }
        if(isset($type) and isset(($type2))){
        //$usuario->tipo_doc = $type;
        //$usuario->tipo_doc_verso = $type2;
        }
        $usuario->dtUltPromo = $request['dtUltPromo'];

        if($validatedData['perfil_id'] == 5){  
            $usuario->status = 5;
        }

        $usuario->perfil_id = $validatedData['perfil_id'];
        
        if($request->hasFile('documento') || $request->hasFile('documento_verso')){
           


/*
            $file_documento = $request->file('documento');
            $file_documento_verso = $request->file('documento_verso');

            $contents = $file_documento->openFile()->fread($file_documento->getSize());
            $contents = base64_encode($contents);  
            
            $contents_documento_verso = $file_documento_verso->openFile()->fread($file_documento_verso->getSize());
            $contents_documento_verso = base64_encode($contents_documento_verso);  
           
            $usuario->documento = $contents;
            $usuario->documento_verso = $contents_documento_verso; 
        */
        }
        
        if($usuario->update()){


        if($request->hasFile('documento')) {
        $documentoService = new DocumentoService();
        $documentoService->salvarFrente($usuario, $request->file('documento'));
    }

    if($request->hasFile('documento_verso')) {
        $documentoService->salvarVerso($usuario, $request->file('documento_verso'));
    }

             \Session::flash('message', ['msg'=>'Dados pessoais alterados com sucesso.', 'class'=>'success']);
             $usuario->syncRoles([$validatedData['perfil_id']]);

             return redirect()->back();
        }else{
             \Session::flash('message', ['msg'=>'Ocorreu um erro ao salvar os dados.', 'class'=>'danger']);
             return redirect()->back();
        }
        
        
        
    }

    public function homeUsuario()
    {    
        return view('pedido.home');
    }

    public function reload(){
        return response()->json(['captcha'=> captcha_img()]);
    }


    public function updatePassword(Request $request)
{

        //dd($request->all());
        # Validation
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);

        /*
        #Match The Old Password
        if(!Hash::check($request->password_confirmation, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }
        */

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->update();
        //dd($user);
        /*
        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);
        */
        return redirect()->route('login')->with("status", "Senha alterada com sucesso!");

        //return back()->with("status", "Senha alterada com sucesso!");
}
    public function updatePasswordInativo(){
        
       
        return redirect()->route('login')->with("erro", "Favor Contactar a Administração!");

    }


}
