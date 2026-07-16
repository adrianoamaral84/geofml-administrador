<?php

namespace App\Http\Controllers\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\DB;
use App\Services\CalculoHospedagemService;

class PagamentoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){


        $consulta = \App\pagtesouro::all();

        return view('pagtesouro.index', compact('consulta'));
    }


    public function create(){


        return view('pagtesouro.create');
    }


    public function store(Request $request){

        //dd($request->all());
        $customMessages = [
                      
            'url.required' => 'Campo URL obrigatório',
            'token.required' => 'Campo Token brigatório',
           
         
        ];

        $validatedData = [
            'url' => 'required',
            'token' => 'required',
           
        ];


      $validatedData = $request->validate($validatedData, $customMessages);
      $consulta = new \App\pagtesouro();
      $consulta->url = $validatedData['url'];
      $consulta->token = $validatedData['token'];
      $consulta->save();

      \Session::flash('message', ['msg'=>"Cadastrado com sucesso!", 'class'=>'success']);
      

      return redirect()->route('pagamento.index');


    }

    public function edit($id){

        $id = Crypt::decrypt($id);
        $consulta = \App\pagtesouro::find($id);
        //dd($consulta);
        return view('pagtesouro.edit', compact('consulta'));
        //dd('edit');
    }


    public function update(Request $request){


        $customMessages = [
                      
            'url.required' => 'Campo URL obrigatório',
            'token.required' => 'Campo Token brigatório',
            'codigoservico.required' => 'Campo Token brigatório',
            'codigoservico.number' => 'Campo Numerico',
            
            
        ];

        $validatedData = [
            'url' => 'required',
            'token' => 'required',
            'codservico' => 'required:number',   
        ];


        $validatedData = $request->validate($validatedData, $customMessages);


      $consulta = \App\pagtesouro::findOrFail($request->id);
      $consulta->url = $validatedData['url'];
      $consulta->codservico = $validatedData['codservico'];
      $consulta->token = $validatedData['token'];
      $consulta->update();

      \Session::flash('message', ['msg'=>"Atualizado com sucesso!", 'class'=>'success']);
      

      return redirect()->route('pagamento.index');

    }

    public function geraboleto(){


            $pagtesouro = \App\pagtesouro::where('id', 2)->first();
            $jsonEnvio = $this->prepareJson1();

            dd($jsonEnvio);

            $data_string = json_encode($jsonEnvio);

            $ch = curl_init('https://pagtesouro.tesouro.gov.br/pagamentos/meios-pagamento/geracao-boleto');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $pagtesouro->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            dd($result);
            curl_close($ch);

            

            $result = json_decode($result);
            dd($result);
    }

    public function consultarPagamento($id)
{
   
    $id = Crypt::decrypt($id);
    $pagamento = \App\Pagamento::where('hospedagem_id', $id)
        ->orderBy('id', 'desc')
        ->firstOrFail();

    $pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);
    $url = 'https://pagtesouro.tesouro.gov.br/api/gru/pagamentos/' . $pagamento->idPagamento;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $pagtesouro->token,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($result === false) {
        $erro = curl_error($ch);
        curl_close($ch);

        \Session::flash('message', ['msg' => 'Erro ao consultar PagTesouro: '.$erro, 'class' => 'danger']);
        return redirect()->back();
    }

    curl_close($ch);

    $dados = json_decode($result);


$pagamento->situacao = $dados->situacao->codigo ?? null;
$pagamento->valor = $dados->valor ?? null;
$pagamento->data_situacao = isset($dados->situacao->data)
    ? \Carbon\Carbon::parse($dados->situacao->data)->format('Y-m-d H:i:s')
    : null;
$pagamento->json_retorno = $result;
//$pagamento->save();

if (in_array($pagamento->situacao, ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'])) {

$hospedagem = \App\Hospede::find($pagamento->hospedagem_id);

    if ($hospedagem) {
        $hospedagem->valor_pago = ($hospedagem->valor_pago ?? 0) + ($pagamento->valor ?? 0);
        $hospedagem->valor_restante = max(0, ($hospedagem->valor ?? 0) - $hospedagem->valor_pago);
        //$hospedagem->situacao_pgto_id = 1;
        // $hospedagem->save();
    }

    \Session::flash('message', [
        'msg' => 'Pagamento confirmado com sucesso! Situação atual: ' . ($pagamento->situacao ?? 'não informada'),
        'class' => 'success'
    ]);

    return redirect()->back();
}



\Session::flash('message', [
    'msg' => 'Situação atual do pagamento: ' . ($pagamento->situacao ?? 'não informada'),
    'class' => 'info'
]);

return redirect()->back();
    
}






public function processaPagamentoRestante($id, $restante = null)
{
    $hospedagemId = Crypt::decrypt($id);

    $hospedagem = \App\Hospede::with('user')->findOrFail($hospedagemId);

    if ((int) Auth::id() !== (int) $hospedagem->user_id && !$this->usuarioPodeGerenciarHospedagem()) {
        Log::warning('Tentativa de gerar pagamento para hospedagem de outro usuário.', [
            'usuario_id' => Auth::id(),
            'hospedagem_id' => $hospedagem->id,
        ]);

        abort(403);
    }

    /*
     * Não reutiliza a proximaUrl de uma sessão anterior.
     * Ao fechar a janela do PagTesouro sem concluir, essa URL pode ficar inválida.
     * Nesse caso, a cobrança anterior é marcada como substituída e uma nova sessão
     * é criada no PagTesouro.
     */
    $pagamentoAtual = \App\Pagamento::where('hospedagem_id', $hospedagem->id)
        ->where('tipo', 'pagamento_restante')
        ->orderByDesc('id')
        ->first();

    if ($pagamentoAtual && in_array($pagamentoAtual->situacao, ['CRIADO', 'INICIADO'], true)) {
        $pagamentoAtual->situacao = 'SUBSTITUIDO';
        $pagamentoAtual->save();
    }

    if ($pagamentoAtual && in_array(
        $pagamentoAtual->situacao,
        ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'],
        true
    )) {
        return response('O pagamento restante já foi confirmado. Esta janela pode ser fechada.', 200);
    }

    /* O valor recebido pela URL é ignorado; o servidor sempre recalcula. */
    $calculo = app(CalculoHospedagemService::class)->calcular($hospedagem);
    $valorRestante = round((float) $calculo['valor_restante'], 2);


    if ($valorRestante <= 0) {
        return response('Não há valor restante para pagamento. Esta janela pode ser fechada.', 200);
    }

    

    $hospedagem->qntdiarias = $calculo['dias'];
$hospedagem->valor = $calculo['valor_total'];
$hospedagem->valor_restante = $valorRestante;
$hospedagem->save();

/*
 * Simulador local do pagamento restante.
 */
if (config('services.pagtesouro.modo_teste')) {
    $pagamento = new \App\Pagamento();

    $pagamento->idPagamento =
        'TESTE-RESTANTE-' . strtoupper(uniqid());

    $pagamento->proximaUrl = '#';
    $pagamento->hospedagem_id = $hospedagem->id;
    $pagamento->tipo = 'pagamento_restante';
    $pagamento->situacao = 'CRIADO';
    $pagamento->valor = $valorRestante;
    $pagamento->save();

    $pagamento->proximaUrl = route(
        'pagamento.simulador',
        [
            'id' => Crypt::encrypt($pagamento->id),
        ]
    );

    $pagamento->save();

    return redirect()->to($pagamento->proximaUrl);
}

/*
 * Somente produção/homologação.
 */
$pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);




//    $pagtesouro = \App\PagTesouro::findOrFail(2);




















    //
    $dataString = json_encode(
        $this->prepareJsonPagamentoRestante($hospedagem, $valorRestante),
        JSON_UNESCAPED_UNICODE
    );

    if ($dataString === false) {
        Log::error('Falha ao codificar os dados do PagTesouro.', [
            'hospedagem_id' => $hospedagem->id,
            'json_error' => json_last_error_msg(),
        ]);

        return response('Não foi possível preparar os dados do pagamento.', 500);
    }

    $ch = curl_init($pagtesouro->url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $dataString,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $pagtesouro->token,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT => 30,
        // Em produção, mantenha a validação SSL ativada.
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $resultadoApi = curl_exec($ch);
    $erroCurl = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resultadoApi === false) {
        Log::error('Erro de comunicação com o PagTesouro.', [
            'hospedagem_id' => $hospedagem->id,
            'erro' => $erroCurl,
        ]);

        return response('Erro ao comunicar com o PagTesouro.', 502);
    }

    $resultado = json_decode($resultadoApi);

    if (json_last_error() !== JSON_ERROR_NONE || !is_object($resultado)) {
        Log::error('Resposta inválida do PagTesouro.', [
            'hospedagem_id' => $hospedagem->id,
            'http_code' => $httpCode,
            'resposta' => $resultadoApi,
        ]);

        return response('O PagTesouro retornou uma resposta inválida.', 502);
    }

    if ($httpCode < 200 || $httpCode >= 300 || isset($resultado->codigo)) {
        $mensagem = $resultado->descricao ?? 'Não foi possível gerar o pagamento no PagTesouro.';

        Log::warning('PagTesouro recusou a geração do pagamento.', [
            'hospedagem_id' => $hospedagem->id,
            'http_code' => $httpCode,
            'resposta' => $resultadoApi,
        ]);

        return response($mensagem, 422);
    }

    if (empty($resultado->idPagamento) || empty($resultado->proximaUrl)) {
        return response('O PagTesouro não retornou os dados necessários do pagamento.', 502);
    }

    $pagamento = new \App\Pagamento();
    $pagamento->idPagamento = $resultado->idPagamento;
    $pagamento->proximaUrl = $resultado->proximaUrl;
    $pagamento->hospedagem_id = $hospedagem->id;
    $pagamento->tipo = 'pagamento_restante';
    $pagamento->situacao = $resultado->situacao->codigo ?? 'CRIADO';
    $pagamento->valor = $valorRestante;
    $pagamento->save();

    return redirect()->away($resultado->proximaUrl);
}














    public function processaPagamentoRestante_OLD($id, $restante)
    {
         

           
            date_default_timezone_set('America/Sao_Paulo');
            
            $id = Crypt::decrypt($id);
            $hospedagem = \App\Hospede::findOrFail($id);
            
            
            $pagamentoAtual = \App\Pagamento::where('hospedagem_id', $hospedagem->id)
    ->where('tipo', 'pagamento_restante')
    ->orderBy('id', 'desc')
    ->first();

if ($pagamentoAtual) {

    if (in_array($pagamentoAtual->situacao, ['CRIADO', 'INICIADO'])) {
        \Session::flash('message', [
            'msg' => 'Já existe um pagamento restante em aberto. Consulte a situação antes de gerar outro.',
            'class' => 'warning'
        ]);

        return redirect()->back();
    }

    if (in_array($pagamentoAtual->situacao, ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'])) {
        \Session::flash('message', [
            'msg' => 'Pagamento restante já foi confirmado.',
            'class' => 'success'
        ]);

        return redirect()->back();
    }
}

           

            $dataInicioPedido   = $hospedagem->data_inicio;
            $dataFimPedido      = $hospedagem->data_termino;

           

            $diasBanco = DB::select("SELECT DATEDIFF(NOW(), checkin_at) AS DIFERENCA_DIAS FROM hospedagem
            WHERE id = $id");
            
            
            $dataCheckIn = $hospedagem->checkin_at;
            $dataCheckIn = new DateTime($dataCheckIn);

            $dataCheckInParaCobrar = $dataCheckIn->format('Y-m-d');
            
            $dataCheckIna       =   $dataCheckIn->format('H:i:s');
            $dataCheckInToTime  =   strtotime($dataCheckIna);           
            

            $DataEntradaUH = $dataCheckIn->format('Y-m-d');
           


            $hoje = date("Y-m-d H:i:s");
            $hoje = new DateTime($hoje);
            $hojeData = $hoje->format('Y-m-d');
            $hojeHoras = date("H:i:s");
            $hojeHorasToTime = strtotime($hojeHoras);
            $data_fim = $hoje;

            
            
            $horarios = \App\Horario::first();
            $HorarioEntrada = $horarios->entrada;
            $HorarioEntrada = new DateTime($HorarioEntrada);
            $HorarioEntradaTolerancia = $HorarioEntrada->sub(new DateInterval('PT'.$horarios->tolerancia.'H'));

            $HorarioEntradaTolerancia = $HorarioEntrada->format('H:i:s');
            $HorarioEntradaToleranciaToTime = strtotime($HorarioEntradaTolerancia);

            
            $HorarioSaida = $horarios->saida;
            $HorarioSaida = new DateTime($HorarioSaida);
            $HorarioSaidaTolerancia = $HorarioSaida->add(new DateInterval('PT'.$horarios->tolerancia.'H'));
            
            $HorarioSaidaTolerancia = $HorarioSaida->format('H:i:s');
            $HorarioSaidaToleranciaToTime = strtotime($HorarioSaidaTolerancia);
            
            // Resgata diferença entre as datas
            $dateInterval = $dataCheckIn->diff($hoje);
            $dias = $dateInterval->days;
            
            

            $tarifa = $hospedagem->valorTarifaComDesconto();
            foreach ($diasBanco as $key => $value) {
                $diasBanco = $value->DIFERENCA_DIAS;
            }
            

            if($diasBanco == 1){
                $restante = 0;
            }

           

            if(strtotime($dataInicioPedido) < strtotime($hojeData) and strtotime($dataFimPedido) > strtotime($hojeData)){
                
                
                //DENTRO DO PERIODO DE RESERVA NAO COBRA TAXA
                $CobrarDiariaExtra = false;
              
            }else{

                
                //FORA DO PERIODO DE RESERVA COBRA TAXA
                $CobrarDiariaExtra = true;

            //  SE SAIR ANTES DO HORARIO ADICIONA UM DIA SAIDA  
            if ($hojeHorasToTime >= $HorarioSaidaToleranciaToTime) {
            //COBRAR MAIS UMA DIÁRIA

            $restante = $restante + $tarifa;
            $diasBanco = $diasBanco + 1;
            }
            


            }


            if(strtotime($dataInicioPedido) < strtotime($dataCheckInParaCobrar) and strtotime($dataFimPedido) > strtotime($dataCheckInParaCobrar)){
               
               
            }else{
                
                 // SE ENTRAR ANTES DO HORARIO ADICIONA UM DIA ENTRADA   
            if($dataCheckInToTime < $HorarioEntradaToleranciaToTime){
            
           $restante = $restante + $tarifa;
           
            if(!$diasBanco == 1){
                $diasBanco = $diasBanco + 1;
            
            }                  
            }
            }

            

           

           if ($restante <= 0) {
    \Session::flash('message', [
        'msg' => 'Não há valor restante para pagamento.',
        'class' => 'info'
    ]);

    return redirect()->back();
}
            
           
            $pagtesouro = \App\pagtesouro::where('id', 2)->first();
            $jsonEnvio = $this->prepareJsonPagamentoRestante($hospedagem, $restante);
            

            $data_string = json_encode($jsonEnvio);

            $timeout = 20;

            $ch = curl_init($pagtesouro->url);            

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_PROXY_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "LOGIN:SENHA");
            //curl_setopt($ch, CURLOPT_PROXY, "http://proxy.11ct.eb.mil.br:3128");
            curl_setopt($ch, CURLOPT_PROXY, "http://10.42.130.22:3128");

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $pagtesouro->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            
            
            
           $result = curl_exec($ch);

if ($result === false) {
    curl_close($ch);

    \Session::flash('message', [
        'msg' => "Houve um erro ao enviar os dados para a API do PagTesouro!",
        'class' => 'danger'
    ]);

    return redirect()->back();
}

curl_close($ch);
            
            
            $result = json_decode($result);
            foreach ($result as $key => $value) {
               $mensagem = $value;
            }
            
            if(isset($mensagem->descricao)){
                     \Session::flash('message', ['msg'=>$mensagem->descricao, 'class'=>'danger']);                
               return redirect()->back();
            }

            $pagamento = new \App\Pagamento();
            $pagamento->idPagamento = $result->idPagamento;
            $pagamento->proximaUrl = $result->proximaUrl;
            $pagamento->hospedagem_id = $hospedagem->id;
            $pagamento->tipo = 'pagamento_restante';
            $pagamento->situacao = $result->situacao->codigo ?? null;
            $pagamento->save();

          
            return redirect()->to($result->proximaUrl);        


    }


    public function processaRequisicao($id)
    {
        
            date_default_timezone_set('America/Sao_Paulo');
       
            $id = Crypt::decrypt($id);
            $hospedagem = \App\Hospede::findOrFail($id);
        $pagamentoAtual = \App\Pagamento::where('hospedagem_id', $hospedagem->id)
        ->where('tipo', 'diaria_inicial')
        ->orderByDesc('id')
        ->first();

    if ($pagamentoAtual && in_array($pagamentoAtual->situacao, ['CRIADO', 'INICIADO'], true)) {
        $pagamentoAtual->situacao = 'SUBSTITUIDO';
        $pagamentoAtual->save();
    }

    if ($pagamentoAtual && in_array(
        $pagamentoAtual->situacao,
        ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'],
        true
    )) {
        return response(
    'O pagamento da diária inicial já foi confirmado. Esta janela pode ser fechada.',
    200
);
    }

            $valorDiaria = $hospedagem->valorTarifaComDesconto();

            if(Auth::id() <> $hospedagem->user_id){
                
                Log::warning('Usuario '. Auth::id(). 'Tentou acessar hospedagem que não é sua!');
                abort('401');

            }
            

           
            $valorDiaria = round(
    (float) $hospedagem->valorTarifaComDesconto(),
    2
);

/*
 * Simulador local da diária inicial.
 */
if (config('services.pagtesouro.modo_teste')) {
    $pagamento = new \App\Pagamento();

    $pagamento->idPagamento =
        'TESTE-INICIAL-' . strtoupper(uniqid());

    $pagamento->proximaUrl = '#';
    $pagamento->hospedagem_id = $hospedagem->id;
    $pagamento->tipo = 'diaria_inicial';
    $pagamento->situacao = 'CRIADO';
    $pagamento->valor = $valorDiaria;
    $pagamento->save();

    $pagamento->proximaUrl = route(
        'pagamento.simulador',
        [
            'id' => Crypt::encrypt($pagamento->id),
        ]
    );

    $pagamento->save();

    return redirect()->to($pagamento->proximaUrl);
}

/*
 * Produção ou homologação.
 */
$pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);

            $jsonEnvio = $this->prepareJson($hospedagem);
         
            $data_string = json_encode($jsonEnvio);
         
            $timeout = 20;

            $ch = curl_init($pagtesouro->url);            
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            //curl_setopt($ch, CURLOPT_PROXY_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "LOGIN:SENHA");
            //curl_setopt($ch, CURLOPT_PROXY, "http://proxy.11ct.eb.mil.br:3128");
            //curl_setopt($ch, CURLOPT_PROXY, "http://192.168.3.22:3128");

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $pagtesouro->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
           
            
           $result = curl_exec($ch);

if ($result === false) {
    curl_close($ch);

    \Session::flash('message', [
        'msg' => "Houve um erro ao enviar os dados para a API do PagTesouro!",
        'class' => 'danger'
    ]);

    return redirect()->back();
}

            curl_close($ch);
            
            $result = json_decode($result);
            
            if($result === false){
                 
            }

            if(isset($result->codigo)){
                     \Session::flash('message', ['msg'=>$result->descricao, 'class'=>'danger']);                
               return redirect()->back();
            }

                    
            
            
               

                // Cria um registro de pagamento para a diária inicial
               $pagamento = new \App\Pagamento();
$pagamento->idPagamento = $result->idPagamento;
$pagamento->proximaUrl = $result->proximaUrl;
$pagamento->hospedagem_id = $hospedagem->id;
$pagamento->tipo = 'diaria_inicial';
$pagamento->situacao = $result->situacao->codigo ?? 'CRIADO';
$pagamento->valor = round($valorDiaria, 2);
$pagamento->save();

return redirect()->to($result->proximaUrl);  
    }



    public function pagamentoCarrinho($id, $total)
    {
        
            $id = Crypt::decrypt($id);
            $total = Crypt::decrypt($total);
            $hospedagem = \App\Hospede::findOrFail($id);
            
            if(Auth::id() <> $hospedagem->user_id){

                //Log::warning('Usuario '. Auth::id(). ' Tentou acessar hospedagem que não é sua!');
                //abort('401');
            }
            
            $verifica = \App\Pagamento::where('hospedagem_id', $id)->count();
            if($verifica > 0){
            
                //\Session::flash('message', ['msg'=>"Existe um pagamento cadastrado no sistema para essa hospedagem!", 'class'=>'danger']);
                //return redirect()->back();
            
            }

           
            $pagtesouro = \App\pagtesouro::where('id', 2)->first();
            $jsonEnvio = $this->prepareJsonCarrinho($hospedagem, $total);
            

            $data_string = json_encode($jsonEnvio);
           

            $timeout = 20;

            $ch = curl_init($pagtesouro->url);            
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_PROXY_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_PROXY, "http://192.168.3.22:3128");

            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "04281158960:Brasil@123");
            //curl_setopt($ch, CURLOPT_PROXY, "http://proxy.11ct.eb.mil.br:3128");
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $pagtesouro->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            
             
            if(curl_exec($ch) === false)
            {
               \Session::flash('message', ['msg'=>"Houve um erro ao enviar os dados para a APi do PagTesouro!", 'class'=>'danger']);                
               return redirect()->back();
            }
                   

            


   
          
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);
            
           
            if(isset($result->codigo)){
                \Session::flash('message', ['msg'=>$result->descricao, 'class'=>'danger']);                
                return redirect()->back();
            }
            
            

            
            
           

            if($result === false){
            
            }   

           
            return redirect()->to($result->proximaUrl);        
    }

    private function resultadoTemErro($result): bool
    {
        return is_array($result);
    }

      public function prepareJson1()
    {
        return array(
            "idSessao" => "3a7161fa-2d7f-4256-b6a1-efe3b3d73315",

        );
    }


    public function prepareJson($hospedagem)
    {
        
        
        $codigo = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);
        $today = Carbon::now()->addDays(0);
        $vencimento = $today->format('dmY');
        $valorDiaria = $hospedagem->valorTarifaComDesconto();
        
        return array(
            "codigoServico" => $codigo->codservico,
            "referencia" => "",
            "competencia" => "",
            "vencimento" => $vencimento,
            "cnpjCpf" => $hospedagem->user_cpf, // colocar o cpf do militar cadastrado
            "nomeContribuinte" =>  $hospedagem->user->name, // Colocar o nome do militar 
            "valorPrincipal" =>  $hospedagem->valorTarifaComDesconto(),
            "valorDescontos" => "",
            "valorOutrasDeducoes" => "",
            "valorMulta" => "",
            "valorJuros" => "",
            "valorOutrosAcrescimos" => "",
            "modoNavegacao" => "2",
            "urlNotificacao" => "https://valpagtesouro.tesouro.gov.br/api/simulador/ug/notificacao"
        );
    }
    public function prepareJsonPagamentoRestante($hospedagem, $restante)
    {
        
        $codigo = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);
        $today = Carbon::now()->addDays(0);
        $vencimento = $today->format('dmY');
       
        return array(
            "codigoServico" => $codigo->codservico,
            "referencia" => "",
            "competencia" => "",
            "vencimento" => $vencimento,
            "cnpjCpf" => $hospedagem->user_cpf, // colocar o cpf do militar cadastrado
            "nomeContribuinte" =>  $hospedagem->user->name, // Colocar o nome do militar 
            "valorPrincipal" =>  $restante,
            "valorDescontos" => "",
            "valorOutrasDeducoes" => "",
            "valorMulta" => "",
            "valorJuros" => "",
            "valorOutrosAcrescimos" => "",
            "modoNavegacao" => "2",
            "urlNotificacao" => "https://valpagtesouro.tesouro.gov.br/api/simulador/ug/notificacao"
        );
    }

    public function prepareJsonCarrinho($hospedagem, $total)
    {
        
        $codigo = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);
        $total = number_format((float)$total, 2, '.', '');
        $today = Carbon::now()->addDays(0);
        $vencimento = $today->format('dmY');

        return array(
            "codigoServico" => $codigo->codservico,
            "referencia" => "",
            "competencia" => "",
            "vencimento" => $vencimento,
            "cnpjCpf" => $hospedagem->user_cpf, // colocar o cpf do militar cadastrado
            "nomeContribuinte" =>  $hospedagem->user->name, // Colocar o nome do militar 
            "valorPrincipal" =>  $total,
            "valorDescontos" => "",
            "valorOutrasDeducoes" => "",
            "valorMulta" => "",
            "valorJuros" => "",
            "valorOutrosAcrescimos" => "",
            "modoNavegacao" => "2",
            "urlNotificacao" => "https://valpagtesouro.tesouro.gov.br/api/simulador/ug/notificacao"
        );
    }


   public function consultarStatus($id)
{
    try {
        $hospedagemId = Crypt::decrypt($id);

        $hospedagem = \App\Hospede::findOrFail($hospedagemId);

        /*
         * Segurança:
         * permite o dono da hospedagem ou usuário administrativo.
         */
        if (
            (int) Auth::id() !== (int) $hospedagem->user_id
            && !$this->usuarioPodeGerenciarHospedagem()
        ) {
            abort(403);
        }

        /*
         * Busca o pagamento restante mais recente
         * que não tenha sido substituído.
         */
        $pagamento = \App\Pagamento::where(
            'hospedagem_id',
            $hospedagem->id
        )
            ->where('tipo', 'pagamento_restante')
            ->where('situacao', '<>', 'SUBSTITUIDO')
            ->orderByDesc('id')
            ->firstOrFail();

        /*
         * Se já foi confirmado anteriormente,
         * não precisa consultar o PagTesouro novamente.
         */
        $situacoesConfirmadas = [
            'CONCLUIDO',
            'PAGO',
            'PAGAMENTO_CONCLUIDO',
        ];




                /*
 * No simulador local, o método aprovarSimulacao()
 * já atualizou o pagamento e a hospedagem no banco.
 */
if (config('services.pagtesouro.modo_teste')) {
    $pagamento->refresh();
    $hospedagem->refresh();

    $confirmado = in_array(
        $pagamento->situacao,
        $situacoesConfirmadas,
        true
    );

    return response()->json([
        'sucesso' => true,
        'pagamento_confirmado' => $confirmado,
        'situacao' => $pagamento->situacao,
        'valor_pago' => (float) ($hospedagem->valor_pago ?? 0),
        'valor_restante' => (float) ($hospedagem->valor_restante ?? 0),
        'mensagem' => $confirmado
            ? 'Pagamento restante de teste confirmado.'
            : 'Pagamento restante aguardando aprovação.',
    ]);
}





        if (in_array(
            $pagamento->situacao,
            $situacoesConfirmadas,
            true
        )) {
            return response()->json([
                'sucesso' => true,
                'pagamento_confirmado' => true,
                'situacao' => $pagamento->situacao,
                'valor_pago' => (float) $hospedagem->valor_pago,
                'valor_restante' => (float) $hospedagem->valor_restante,
                'mensagem' => 'Pagamento já confirmado.',
            ]);
        }

        /*
         * Consulta o PagTesouro.
         */
        $pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);

        $urlConsulta =
            'https://pagtesouro.tesouro.gov.br/api/gru/pagamentos/'
            . $pagamento->idPagamento;

        $ch = curl_init($urlConsulta);

        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . $pagtesouro->token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $resultadoApi = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($resultadoApi === false) {
            $erroCurl = curl_error($ch);
            curl_close($ch);

            throw new \RuntimeException(
                'Erro ao consultar PagTesouro: ' . $erroCurl
            );
        }

        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \RuntimeException(
                'PagTesouro retornou HTTP ' . $httpCode
            );
        }

        $dados = json_decode($resultadoApi);

        if (
            json_last_error() !== JSON_ERROR_NONE
            || !is_object($dados)
        ) {
            throw new \RuntimeException(
                'Resposta inválida do PagTesouro.'
            );
        }

        $situacaoPagTesouro =
            $dados->situacao->codigo ?? null;

        $pagamentoConfirmado = in_array(
            $situacaoPagTesouro,
            $situacoesConfirmadas,
            true
        );

        /*
         * Caso ainda não esteja pago,
         * atualiza apenas a situação atual.
         */
        if (!$pagamentoConfirmado) {
            if (!empty($situacaoPagTesouro)) {
                $pagamento->situacao = $situacaoPagTesouro;
                $pagamento->save();
            }

            return response()->json([
                'sucesso' => true,
                'pagamento_confirmado' => false,
                'situacao' => $situacaoPagTesouro,
                'valor_pago' => (float) $hospedagem->valor_pago,
                'valor_restante' => (float) $hospedagem->valor_restante,
                'mensagem' => 'Pagamento ainda não confirmado.',
            ]);
        }

        /*
         * Pagamento confirmado:
         * atualiza tudo em uma transação.
         */
        DB::transaction(function () use (
            $pagamento,
            $hospedagem,
            $situacaoPagTesouro,
            $dados,
            $situacoesConfirmadas
        ) {
            /*
             * Bloqueia as linhas para evitar que duas
             * requisições atualizem ao mesmo tempo.
             */
            $pagamentoBanco = \App\Pagamento::where(
                'id',
                $pagamento->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $hospedagemBanco = \App\Hospede::where(
                'id',
                $hospedagem->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Verifica se já tinha sido contabilizado.
             */
            $jaContabilizado = in_array(
                $pagamentoBanco->situacao,
                $situacoesConfirmadas,
                true
            );

            /*
             * Atualiza a situação do pagamento.
             */
            $pagamentoBanco->situacao =
                $situacaoPagTesouro;

            /*
             * Só atualiza o valor se o PagTesouro
             * realmente tiver retornado um valor.
             */
            if (isset($dados->valor)) {
                $pagamentoBanco->valor =
                    round((float) $dados->valor, 2);
            }

            $pagamentoBanco->save();

            /*
             * Evita somar duas vezes.
             */
            if (!$jaContabilizado) {
                $valorPagamento = round(
                    (float) ($pagamentoBanco->valor ?? 0),
                    2
                );

                $valorTotal = round(
                    (float) ($hospedagemBanco->valor ?? 0),
                    2
                );

                $valorPagoAtual = round(
                    (float) ($hospedagemBanco->valor_pago ?? 0),
                    2
                );

                $novoValorPago = round(
                    $valorPagoAtual + $valorPagamento,
                    2
                );

                /*
                 * Não permite valor pago acima do total.
                 */
                $novoValorPago = min(
                    $novoValorPago,
                    $valorTotal
                );

                $novoValorRestante = max(
                    0,
                    round(
                        $valorTotal - $novoValorPago,
                        2
                    )
                );

                $hospedagemBanco->valor_pago =
                    $novoValorPago;

                $hospedagemBanco->valor_restante =
                    $novoValorRestante;

                
                $hospedagemBanco->save();
            }
        });

        $hospedagem->refresh();

        return response()->json([
            'sucesso' => true,
            'pagamento_confirmado' => true,
            'situacao' => $situacaoPagTesouro,
            'valor_pago' => (float) $hospedagem->valor_pago,
            'valor_restante' => (float) $hospedagem->valor_restante,
            'mensagem' => 'Pagamento confirmado e registrado no banco.',
        ]);
    } catch (\Throwable $exception) {
        Log::error(
            'Erro ao consultar status do pagamento restante.',
            [
                'erro' => $exception->getMessage(),
            ]
        );

        return response()->json([
            'sucesso' => false,
            'pagamento_confirmado' => false,
            'mensagem' => 'Não foi possível consultar o pagamento.',
        ], 500);
    }
}

    private function atualizarPagamentoPeloPagTesouro($pagamento, $hospedagem): void
    {
        $pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);
        $url = 'https://pagtesouro.tesouro.gov.br/api/gru/pagamentos/'
            . rawurlencode($pagamento->idPagamento);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $pagtesouro->token,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $resultadoApi = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erroCurl = curl_error($ch);
        curl_close($ch);

        if ($resultadoApi === false || $httpCode < 200 || $httpCode >= 300) {
            Log::warning('Não foi possível atualizar o pagamento no PagTesouro.', [
                'pagamento_id' => $pagamento->id,
                'http_code' => $httpCode,
                'erro' => $erroCurl,
            ]);
            return;
        }

        $dados = json_decode($resultadoApi);

        if (json_last_error() !== JSON_ERROR_NONE || !is_object($dados)) {
            return;
        }

        $situacaoAnterior = $pagamento->situacao;
        $novaSituacao = $dados->situacao->codigo ?? $situacaoAnterior;

        $pagamento->situacao = $novaSituacao;
        $pagamento->valor = isset($dados->valor)
            ? (float) $dados->valor
            : $pagamento->valor;
        // Grave apenas os campos que existem na tabela pagamentos.
        // data_situacao e json_retorno podem ser adicionados depois por migration.
        $pagamento->save();

        $situacoesConfirmadas = ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'];

        /* Credita somente na primeira transição para pago. */
        if (
            !in_array($situacaoAnterior, $situacoesConfirmadas, true)
            && in_array($novaSituacao, $situacoesConfirmadas, true)
        ) {
            DB::transaction(function () use ($pagamento, $hospedagem) {
                $hospedagem = \App\Hospede::lockForUpdate()->findOrFail($hospedagem->id);
                $valorPagamento = (float) ($pagamento->valor ?? 0);

                $hospedagem->valor_pago = round(
                    (float) ($hospedagem->valor_pago ?? 0) + $valorPagamento,
                    2
                );
                $hospedagem->valor_restante = max(
                    0,
                    round((float) ($hospedagem->valor ?? 0) - $hospedagem->valor_pago, 2)
                );
               
                $hospedagem->save();
            });
        }
    }

    /**
     * Verifica se o usuário autenticado possui um perfil administrativo
     * autorizado a operar pagamentos de hospedagens de terceiros.
     */
   private function usuarioPodeGerenciarHospedagem(): bool
{
    $usuario = Auth::user();

    if (!$usuario || !method_exists($usuario, 'hasRole')) {
        return false;
    }

    $perfisPermitidos = [
        'atendente',
        'administrador_geral',
        'administrador',
        'auxiliar_administrador_geral',
    ];

    foreach ($perfisPermitidos as $perfil) {
        if ($usuario->hasRole($perfil)) {
            return true;
        }
    }

    return false;
}

public function consultarStatusPagamentoInicial($id)
{
    try {
        $hospedagemId = Crypt::decrypt($id);

        $hospedagem = \App\Hospede::findOrFail($hospedagemId);

        if ((int) Auth::id() !== (int) $hospedagem->user_id) {
            Log::warning(
                'Usuário ' . Auth::id() .
                ' tentou consultar pagamento da hospedagem ' .
                $hospedagem->id
            );

            abort(403);
        }

        $pagamento = \App\Pagamento::where(
            'hospedagem_id',
            $hospedagem->id
        )
            ->where('tipo', 'diaria_inicial')
            ->where('situacao', '<>', 'SUBSTITUIDO')
            ->orderByDesc('id')
            ->firstOrFail();

        $situacoesConfirmadas = [
            'CONCLUIDO',
            'PAGO',
            'PAGAMENTO_CONCLUIDO',
        ];







        /*
 * No simulador local, a aprovação já atualiza o banco.
 * Portanto, não consulta a API do PagTesouro.
 */
if (config('services.pagtesouro.modo_teste')) {
    $pagamento->refresh();
    $hospedagem->refresh();

    $confirmado = in_array(
        $pagamento->situacao,
        $situacoesConfirmadas,
        true
    );

    return response()->json([
        'sucesso' => true,
        'pagamento_confirmado' => $confirmado,
        'situacao' => $pagamento->situacao,
        'valor_pago' => (float) ($hospedagem->valor_pago ?? 0),
        'valor_restante' => (float) ($hospedagem->valor_restante ?? 0),
       'mensagem' => $confirmado
    ? 'Pagamento da diária inicial de teste confirmado.'
    : 'Pagamento da diária inicial aguardando aprovação.',
    ]);
}






        /*
         * Se já foi contabilizado, retorna imediatamente.
         */
        if (in_array(
            $pagamento->situacao,
            $situacoesConfirmadas,
            true
        )) {
            return response()->json([
                'sucesso' => true,
                'pagamento_confirmado' => true,
                'situacao' => $pagamento->situacao,
                'valor_pago' => (float) $hospedagem->valor_pago,
                'valor_restante' => (float) $hospedagem->valor_restante,
                'mensagem' => 'Pagamento inicial já confirmado.',
            ]);
        }

        $pagtesouro = \App\PagTesouro::findOrFail(
    config('services.pagtesouro.config_id')
);

        $urlConsulta =
            'https://pagtesouro.tesouro.gov.br/api/gru/pagamentos/'
            . $pagamento->idPagamento;

        $ch = curl_init($urlConsulta);

        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . $pagtesouro->token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $resultadoApi = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($resultadoApi === false) {
            $erroCurl = curl_error($ch);
            curl_close($ch);

            throw new \RuntimeException(
                'Erro ao consultar PagTesouro: ' . $erroCurl
            );
        }

        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \RuntimeException(
                'PagTesouro retornou HTTP ' . $httpCode
            );
        }

        $dados = json_decode($resultadoApi);

        if (
            json_last_error() !== JSON_ERROR_NONE ||
            !is_object($dados)
        ) {
            throw new \RuntimeException(
                'Resposta inválida do PagTesouro.'
            );
        }

        $situacaoPagTesouro =
            $dados->situacao->codigo ?? null;

        $pagamentoConfirmado = in_array(
            $situacaoPagTesouro,
            $situacoesConfirmadas,
            true
        );

        /*
         * Ainda não foi pago.
         */
        if (!$pagamentoConfirmado) {
            if (!empty($situacaoPagTesouro)) {
                $pagamento->situacao = $situacaoPagTesouro;
                $pagamento->save();
            }

            return response()->json([
                'sucesso' => true,
                'pagamento_confirmado' => false,
                'situacao' => $situacaoPagTesouro,
                'mensagem' => 'Pagamento ainda não confirmado.',
            ]);
        }

        /*
         * Pagamento confirmado:
         * atualiza pagamento e hospedagem em uma transação.
         */
        DB::transaction(function () use (
            $pagamento,
            $hospedagem,
            $dados,
            $situacaoPagTesouro,
            $situacoesConfirmadas
        ) {
            $pagamentoBanco = \App\Pagamento::where(
                'id',
                $pagamento->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $hospedagemBanco = \App\Hospede::where(
                'id',
                $hospedagem->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $jaContabilizado = in_array(
                $pagamentoBanco->situacao,
                $situacoesConfirmadas,
                true
            );

            $pagamentoBanco->situacao =
                $situacaoPagTesouro;

            if (isset($dados->valor)) {
                $pagamentoBanco->valor = round(
                    (float) $dados->valor,
                    2
                );
            }

            $pagamentoBanco->save();

            /*
             * Impede que a diária seja somada mais de uma vez.
             */
            if (!$jaContabilizado) {
                $valorDiaria = round(
                    (float) (
                        $pagamentoBanco->valor
                        ?? $hospedagemBanco->valorTarifaComDesconto()
                    ),
                    2
                );

                $valorTotal = round(
                    (float) ($hospedagemBanco->valor ?? 0),
                    2
                );

                $valorPagoAtual = round(
                    (float) ($hospedagemBanco->valor_pago ?? 0),
                    2
                );

                $novoValorPago = min(
                    $valorTotal,
                    round($valorPagoAtual + $valorDiaria, 2)
                );

                $novoValorRestante = max(
                    0,
                    round($valorTotal - $novoValorPago, 2)
                );

                $hospedagemBanco->status = 5;
                $hospedagemBanco->valor_pago = $novoValorPago;
                $hospedagemBanco->valor_restante =
                    $novoValorRestante;

                $hospedagemBanco->save();
            }
        });

        $hospedagem->refresh();

        return response()->json([
            'sucesso' => true,
            'pagamento_confirmado' => true,
            'situacao' => $situacaoPagTesouro,
            'valor_pago' => (float) $hospedagem->valor_pago,
            'valor_restante' => (float) $hospedagem->valor_restante,
            'mensagem' =>
                'Pagamento da diária inicial confirmado e registrado.',
        ]);
    } catch (\Throwable $exception) {
        Log::error(
            'Erro ao consultar pagamento da diária inicial.',
            [
                'erro' => $exception->getMessage(),
            ]
        );

        return response()->json([
            'sucesso' => false,
            'pagamento_confirmado' => false,
            'mensagem' =>
                'Não foi possível consultar o pagamento inicial.',
        ], 500);
    }
}


public function simulador($id)
{
    abort_unless(
        config('services.pagtesouro.modo_teste'),
        404
    );

    $pagamentoId = Crypt::decrypt($id);

    $pagamento = \App\Pagamento::findOrFail($pagamentoId);

    $hospedagem = \App\Hospede::findOrFail(
        $pagamento->hospedagem_id
    );

    return view(
        'pagamento.simulador',
        compact('pagamento', 'hospedagem')
    );
}
public function aprovarSimulacao($id)
{
    abort_unless(
        config('services.pagtesouro.modo_teste'),
        404
    );

    $pagamentoId = Crypt::decrypt($id);

    DB::transaction(function () use ($pagamentoId) {
        $pagamento = \App\Pagamento::where(
            'id',
            $pagamentoId
        )
            ->lockForUpdate()
            ->firstOrFail();

        $hospedagem = \App\Hospede::where(
            'id',
            $pagamento->hospedagem_id
        )
            ->lockForUpdate()
            ->firstOrFail();

        if (in_array(
            $pagamento->situacao,
            ['PAGO', 'CONCLUIDO', 'PAGAMENTO_CONCLUIDO'],
            true
        )) {
            return;
        }

        $valorPagamento = round(
            (float) ($pagamento->valor ?? 0),
            2
        );

        $valorTotal = round(
            (float) ($hospedagem->valor ?? 0),
            2
        );

        $valorPagoAtual = round(
            (float) ($hospedagem->valor_pago ?? 0),
            2
        );

        $novoValorPago = min(
            $valorTotal,
            round($valorPagoAtual + $valorPagamento, 2)
        );

        $novoValorRestante = max(
            0,
            round($valorTotal - $novoValorPago, 2)
        );

        $pagamento->situacao = 'PAGO';
        $pagamento->save();

        $hospedagem->valor_pago = $novoValorPago;
        $hospedagem->valor_restante = $novoValorRestante;

        if ($pagamento->tipo === 'diaria_inicial') {
            $hospedagem->status = 5;
        }

        

        $hospedagem->save();
    });

    return view('pagamento.simulador_resultado', [
        'titulo' => 'Pagamento aprovado',
        'mensagem' =>
            'Pagamento de teste confirmado. Esta janela pode ser fechada.',
        'sucesso' => true,
    ]);
}
}