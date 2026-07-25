<?php

namespace App\Http\Controllers\PedidosDuplicados;

use App\Http\Controllers\Controller;
use App\Hospede;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosDuplicadosController extends Controller
{

    /**
     * Exibe os pedidos duplicados dentro do mês selecionado.
     */
    public function index(Request $request)
    {
        /*
         * Recebe o mês no formato YYYY-MM.
         * Exemplo: 2026-07.
         *
         * Quando nenhum mês for informado, utiliza o mês atual.
         */
        $competencia = $request->get(
            'competencia',
            Carbon::now()->format('Y-m')
        );

        /*
         * Valida o mês informado.
         * Caso o valor seja inválido, volta para o mês atual.
         */
        try {
            $data = Carbon::createFromFormat('Y-m', $competencia)
                ->startOfMonth();
        } catch (\Exception $e) {
            $data = Carbon::now()->startOfMonth();
            $competencia = $data->format('Y-m');
        }

        /*
         * Define o primeiro e o último dia do mês selecionado.
         */
        $inicio = $data->copy()
            ->startOfMonth()
            ->format('Y-m-d');

        $fim = $data->copy()
            ->endOfMonth()
            ->format('Y-m-d');

        /*
         * Identifica os usuários que possuem mais de um pedido
         * dentro do mês selecionado.
         */
        $usuariosDuplicados = Hospede::query()
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('data_inicio', [$inicio, $fim])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1');

        /*
         * Busca todos os pedidos pertencentes aos usuários
         * identificados anteriormente.
         *
         * Os pedidos são agrupados pelo campo user_id.
         */
        $pedidos = Hospede::query()
            ->with([
                'user',
                'tipouh',
                'status_hospedagem',
            ])
            ->whereBetween('data_inicio', [$inicio, $fim])
            ->whereIn(
                'user_id',
                $usuariosDuplicados->pluck('user_id')
            )
            ->orderBy('user_id')
            ->orderBy('data_inicio')
            ->orderBy('id')
            ->get()
            ->groupBy('user_id');

        /*
         * Envia os pedidos e o mês selecionado para a página.
         */
        return view(
            'pedido.duplicados',
            compact('pedidos', 'competencia')
        );
    }

    /**
     * Exclui somente um pedido.
     */
    public function destroy($id)
    {
        /*
         * Busca o pedido pelo ID.
         * Caso não exista, o Laravel retorna erro 404.
         */
        $pedido = Hospede::findOrFail($id);

        /*
         * Exclui o pedido.
         */
        $pedido->delete();

        /*
         * Mensagem apresentada depois da exclusão.
         */
        \Session::flash('message', [
            'msg' => 'Pedido duplicado excluído com sucesso.',
            'class' => 'success',
        ]);

        /*
         * Retorna para a mesma tela.
         */
        return redirect()->back();
    }

    /**
     * Exclui todos os pedidos marcados na tela.
     */
    public function destroySelecionados(Request $request)
    {
        /*
         * Recupera o array pedidos[] enviado pelo formulário.
         *
         * O array_filter com is_numeric evita que sejam processados
         * valores não numéricos.
         */
        $ids = array_filter(
            (array) $request->input('pedidos', []),
            'is_numeric'
        );

        /*
         * Impede o envio do formulário sem selecionar pedidos.
         */
        if (empty($ids)) {
            return redirect()
                ->back()
                ->withErrors([
                    'Selecione pelo menos um pedido para excluir.',
                ]);
        }

        /*
         * Exclui todos os pedidos cujos IDs foram selecionados.
         */
        $excluidos = Hospede::whereIn('id', $ids)->delete();

        /*
         * Informa quantos pedidos foram excluídos.
         */
        \Session::flash('message', [
            'msg' => $excluidos
                . ' pedido(s) excluído(s) com sucesso.',
            'class' => 'success',
        ]);

        return redirect()->back();
    }
}