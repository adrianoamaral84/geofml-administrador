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

    $pagtesouro = \App\PagTesouro::findOrFail(2);
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
        $hospedagem->situacao_pgto_id = 1;
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
    date_default_timezone_set('America/Sao_Paulo');

    $id = Crypt::decrypt($id);

    $hospedagem = \App\Hospede::with('user')->findOrFail($id);

    /*
     * Verifica o último pagamento restante.
     */
    $pagamentoAtual = \App\Pagamento::where(
        'hospedagem_id',
        $hospedagem->id
    )
        ->where('tipo', 'pagamento_restante')
        ->orderBy('id', 'desc')
        ->first();

    if ($pagamentoAtual) {
        if (in_array(
            $pagamentoAtual->situacao,
            ['CRIADO', 'INICIADO']
        )) {
            \Session::flash('message', [
                'msg' => 'Já existe um pagamento restante em aberto. Consulte a situação antes de gerar outro.',
                'class' => 'warning'
            ]);

           return redirect()->back();
        }

        if (in_array(
            $pagamentoAtual->situacao,
            ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO']
        )) {
            \Session::flash('message', [
                'msg' => 'O pagamento restante já foi confirmado.',
                'class' => 'success'
            ]);

            return redirect()->back();
        }
    }

    /*
     * Recalcula a hospedagem no servidor.
     * Não utiliza o valor restante recebido pela URL.
     */
    $calculoService = new \App\Services\CalculoHospedagemService();

    $calculo = $calculoService->calcular($hospedagem);

    $valorRestante = round(
        (float) $calculo['valor_restante'],
        2
    );

    if ($valorRestante <= 0) {
        \Session::flash('message', [
            'msg' => 'Não há valor restante para pagamento.',
            'class' => 'info'
        ]);

        return redirect()->back();
    }
   
    /*
     * Atualiza os valores da hospedagem antes de gerar a cobrança.
     */
    $hospedagem->qntdiarias = $calculo['dias'];
    $hospedagem->valor = $calculo['valor_total'];
    $hospedagem->valor_restante = $valorRestante;
    $hospedagem->save();

    /*
     * Prepara a requisição para o PagTesouro.
     */
    $pagtesouro = \App\PagTesouro::findOrFail(2);

    $jsonEnvio = $this->prepareJsonPagamentoRestante(
        $hospedagem,
        $valorRestante
    );

    $dataString = json_encode($jsonEnvio);

    if ($dataString === false) {
        \Session::flash('message', [
            'msg' => 'Não foi possível preparar os dados do pagamento.',
            'class' => 'danger'
        ]);

        return redirect()->back();
    }

    $ch = curl_init($pagtesouro->url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    /*
     * Mantenha o proxy apenas na rede interna.
     * Comente esta linha na versão internet, se necessário.
     */
    //curl_setopt($ch, CURLOPT_PROXY, 'http://10.42.130.22:3128');

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $pagtesouro->token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $resultadoApi = curl_exec($ch);

    if ($resultadoApi === false) {
        $erroCurl = curl_error($ch);

        curl_close($ch);

        \Session::flash('message', [
            'msg' => 'Erro ao comunicar com o PagTesouro: ' . $erroCurl,
            'class' => 'danger'
        ]);

        return redirect()->back();
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $resultado = json_decode($resultadoApi);

    if (
        json_last_error() !== JSON_ERROR_NONE ||
        !is_object($resultado)
    ) {
        \Session::flash('message', [
            'msg' => 'O PagTesouro retornou uma resposta inválida.',
            'class' => 'danger'
        ]);

        return redirect()->back();
    }

    if (
        $httpCode < 200 ||
        $httpCode >= 300 ||
        isset($resultado->codigo)
    ) {
        \Session::flash('message', [
            'msg' => $resultado->descricao
                ?? 'Não foi possível gerar o pagamento no PagTesouro.',
            'class' => 'danger'
        ]);

        return redirect()->back();
    }

    if (
        empty($resultado->idPagamento) ||
        empty($resultado->proximaUrl)
    ) {
        \Session::flash('message', [
            'msg' => 'O PagTesouro não retornou os dados necessários do pagamento.',
            'class' => 'danger'
        ]);

        return redirect()->back();
    }

    /*
     * Registra o pagamento restante.
     */
    if($pagamentoAtual){
         if (in_array(
            $pagamentoAtual->situacao,
            ['CRIADO', 'INICIADO']
        )) {
        
        $pagamentoAtual->situacao = $resultado->situacao->codigo ?? 'CRIADO';
        $pagamentoAtual->proximaUrl = $resultado->proximaUrl;
        $pagamentoAtual->valor = $valorRestante;
        $pagamentoAtual->valor = $valorRestante;
        $pagamentoAtual->update();
       
        }
    }else{
       $pagamento = new \App\Pagamento();
    $pagamento->idPagamento = $resultado->idPagamento;
    $pagamento->proximaUrl = $resultado->proximaUrl;
    $pagamento->hospedagem_id = $hospedagem->id;
    $pagamento->tipo = 'pagamento_restante';
    $pagamento->situacao = $resultado->situacao->codigo ?? 'CRIADO';
    $pagamento->valor = $valorRestante;
    $pagamento->save();
    }
    

    return redirect()->to($resultado->proximaUrl);
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
    ->orderBy('id', 'desc')
    ->first();

if ($pagamentoAtual) {

    if (in_array($pagamentoAtual->situacao, ['CRIADO', 'INICIADO'])) {
        \Session::flash('message', [
            'msg' => 'Já existe um pagamento em aberto. Consulte a situação antes de gerar outro.',
            'class' => 'warning'
        ]);

        return redirect()->back();
    }

    if (in_array($pagamentoAtual->situacao, ['CONCLUIDO', 'PAGO', 'PAGAMENTO_CONCLUIDO'])) {
        \Session::flash('message', [
            'msg' => 'Pagamento da diária inicial já foi confirmado.',
            'class' => 'success'
        ]);

        return redirect()->back();
    }
}
            $valorDiaria = $hospedagem->valorTarifaComDesconto();

            if(Auth::id() <> $hospedagem->user_id){
                
                Log::warning('Usuario '. Auth::id(). 'Tentou acessar hospedagem que não é sua!');
                abort('401');

            }
            

           
            $pagtesouro = \App\pagtesouro::where('id', 2)->first();
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

                    
            
            
                $valor_restante = $hospedagem->valor - $valorDiaria;

                // Atualiza o status da hospedagem para indicar que o pagamento da diária inicial foi iniciado
                $hospedagem->status = 5;
                $hospedagem->update();

                // Cria um registro de pagamento para a diária inicial
                $pagamento = new \App\Pagamento();
                $pagamento->idPagamento = $result->idPagamento;
                $pagamento->proximaUrl = $result->proximaUrl;
                $pagamento->hospedagem_id = $hospedagem->id;
                $pagamento->tipo = 'diaria_inicial';
                $pagamento->situacao = $result->situacao->codigo ?? null;
                $pagamento->save();
                                        

                $sessao = str_replace('https://pagtesouro.tesouro.gov.br/#/pagamento?idSessao=', '', $result->proximaUrl);
            
                return redirect()->to($result->proximaUrl);        
    }



    public function pagamentoCarrinho($id, $total)
    {
        
        
           

            $id = Crypt::decrypt($id);
            $total = Crypt::decrypt($total);
            

            $hospedagem = \App\Hospede::findOrFail($id);
            //dd($hospedagem->user->name);
            
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
        
        
        $codigo = \App\PagTesouro::all()->first();
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
        
        $codigo = \App\PagTesouro::all()->first(); 
        $today = Carbon::now()->addDays(0);
        $vencimento = $today->format('dmY');
       
        return array(
            "codigoServico" => $codigo->codservico,
            "referencia" => "",
            "competencia" => "",
            "vencimento" => $vencimento,
            "cnpjCpf" => "73524998062", // colocar o cpf do militar cadastrado
            //"cnpjCpf" => $hospedagem->user_cpf, // colocar o cpf do militar cadastrado
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
        
        $codigo = \App\PagTesouro::all()->first();
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

        $hospedagem = \App\Hospede::where('id', $hospedagemId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'sucesso' => true,
            'status' => $hospedagem->status,
            'valor_pago' => $hospedagem->valor_pago,
            'valor_restante' => $hospedagem->valor_restante,

            // Ajuste conforme o status usado pelo sistema
            'pagamento_confirmado' => in_array($hospedagem->status, [3, 7])
        ]);
    } catch (\Exception $exception) {
        Log::error('Erro ao consultar status do pagamento', [
            'erro' => $exception->getMessage()
        ]);

        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Não foi possível consultar o pagamento.'
        ], 500);
    }
}
    
}
