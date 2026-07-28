<?php

namespace App\Http\Controllers\Hospedagem;

use App\Hospede;
use App\Http\Controllers\Controller;
use App\Services\DisponibilidadeHospedagemService;
use App\UnidadeHabitacional;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisponibilidadeController extends Controller
{
    private $disponibilidade;

    public function __construct(
        DisponibilidadeHospedagemService $disponibilidade
    ) {
        $this->disponibilidade = $disponibilidade;
    }
    public function verificar(
    Request $request,
    DisponibilidadeHospedagemService $disponibilidade
) {
    $request->validate([
        'unidade_id' => 'required|integer',
        'data_inicio' => 'required|date_format:d-m-Y',
        'data_final' => 'required|date_format:d-m-Y',
        'hospedagem_id' => 'nullable|integer',
    ]);

    $inicio = Carbon::createFromFormat(
        'd-m-Y',
        $request->input('data_inicio')
    )->startOfDay();

    $fim = Carbon::createFromFormat(
        'd-m-Y',
        $request->input('data_final')
    )->startOfDay();

    if ($fim->lte($inicio)) {
        return response()->json([
            'disponivel' => false,
            'conflitos' => [],
            'message' => 'A saída deve ser posterior à entrada.',
        ], 422);
    }

    $hospedagemIgnorada = $request->filled('hospedagem_id')
        ? (int) $request->input('hospedagem_id')
        : null;

    $conflitos = $disponibilidade->buscarConflitos(
        (int) $request->input('unidade_id'),
        $inicio,
        $fim,
        $hospedagemIgnorada
    );

    $temConflito = $conflitos->isNotEmpty();

    return response()->json([
        'disponivel' => !$temConflito,

        'conflitos' => $conflitos
            ->map(function ($hospedagem) {
                return [
                    'id' => $hospedagem->id,

                    'inicio' => Carbon::parse(
                        $hospedagem->data_inicio
                    )->format('d/m/Y'),

                    'termino' => Carbon::parse(
                        $hospedagem->data_termino
                    )->format('d/m/Y'),
                ];
            })
            ->values(),

        'message' => $temConflito
            ? 'A unidade possui conflito no período informado.'
            : 'A unidade continua disponível.',
    ]);
}
    public function verificar_OLD(Request $request)
    {
        $dados = $request->validate([
            'unidade_id' => 'required|integer|exists:unidades_habitacionais,id',
            'data_inicio' => 'required|date_format:d-m-Y',
            'data_final' => 'required|date_format:d-m-Y|after:data_inicio',
            'ignorar_hospedagem' => 'nullable|integer',
        ]);

        $inicio = Carbon::createFromFormat(
            'd-m-Y',
            $dados['data_inicio']
        )->startOfDay();

        $fim = Carbon::createFromFormat(
            'd-m-Y',
            $dados['data_final']
        )->startOfDay();

        $ignorar = isset($dados['ignorar_hospedagem'])
            ? (int) $dados['ignorar_hospedagem']
            : null;

        $conflitos = $this->disponibilidade
            ->conflitos(
                (int) $dados['unidade_id'],
                $inicio,
                $fim,
                $ignorar
            )
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'inicio' => Carbon::parse($item->data_inicio)
                        ->format('d/m/Y'),
                    'termino' => Carbon::parse($item->data_termino)
                        ->format('d/m/Y'),
                ];
            })
            ->values();
        
        return response()->json([
            'disponivel' => $conflitos->isEmpty(),
            'conflitos' => $conflitos,
        ]);
    }

    /**
     * Retorna o mapa de ocupação de todas as UHs de um grupo.
     *
     * A data de saída é exclusiva para a ocupação:
     * uma hospedagem com saída no dia 04 ocupa 01, 02 e 03,
     * e o dia 04 é retornado como check-out.
     */
    public function mapa(Request $request)
    {
        $dados = $request->validate([
            'grupo_id' => 'required|integer|exists:grupo_destinacao,id',
            'tipo_id' => 'required|integer',
            'data_inicio' => 'required|date_format:d-m-Y',
            'data_final' => 'required|date_format:d-m-Y|after:data_inicio',
            'ignorar_hospedagem' => 'nullable|integer',
        ]);

        $inicioSolicitado = Carbon::createFromFormat(
            'd-m-Y',
            $dados['data_inicio']
        )->startOfDay();

        $fimSolicitado = Carbon::createFromFormat(
            'd-m-Y',
            $dados['data_final']
        )->startOfDay();

        /*
         * Exibe alguns dias antes e depois do período solicitado,
         * mantendo uma janela mínima de 15 dias.
         */
        $inicioMapa = $inicioSolicitado->copy()->subDays(3);
        $fimMapa = $fimSolicitado->copy()->addDays(5);

        if ($inicioMapa->diffInDays($fimMapa) < 15) {
            $fimMapa = $inicioMapa->copy()->addDays(15);
        }

        /*
         * Limite defensivo para evitar uma tabela excessivamente grande.
         */
        if ($inicioMapa->diffInDays($fimMapa) > 45) {
            $fimMapa = $inicioMapa->copy()->addDays(45);
        }

        $unidadesQuery = UnidadeHabitacional::query()
            ->where('grupo_destinacao_id', $dados['grupo_id'])
            ->where('disponivel', 1)
            ->with('tipohabitacao')
            ->orderBy('sigla');

        /*
         * Mantém a regra já usada no CascadeController.
         */
        if ((int) $dados['tipo_id'] === 11 || (int) $dados['tipo_id'] === 12) {
            $unidadesQuery->where('tipo_und_hab_id', $dados['tipo_id']);
        }

        $unidades = $unidadesQuery->get();
        $unidadeIds = $unidades->pluck('id');

        $reservasQuery = Hospede::query()
            ->with('usuario')
            ->ativas()
            ->whereIn('und_habitacionais_id', $unidadeIds)
            ->whereDate('data_inicio', '<', $fimMapa->format('Y-m-d'))
            ->whereDate('data_termino', '>=', $inicioMapa->format('Y-m-d'))
            ->orderBy('data_inicio');

        if (!empty($dados['ignorar_hospedagem'])) {
            $reservasQuery->where(
                'id',
                '<>',
                (int) $dados['ignorar_hospedagem']
            );
        }

        $reservasPorUnidade = $reservasQuery
            ->get()
            ->groupBy('und_habitacionais_id');

        $dias = [];
        $cursor = $inicioMapa->copy();

        while ($cursor->lt($fimMapa)) {
            $dias[] = [
                'data' => $cursor->format('Y-m-d'),
                'dia' => $cursor->format('d'),
                'mes' => $cursor->format('m'),
                'semana' => $cursor->format('D'),
                'fim_semana' => in_array($cursor->dayOfWeek, [0, 6], true),
                'solicitado' =>
                    $cursor->gte($inicioSolicitado) &&
                    $cursor->lt($fimSolicitado),
                'saida_solicitada' => $cursor->isSameDay($fimSolicitado),
            ];

            $cursor->addDay();
        }

        $linhas = $unidades->map(function ($unidade) use (
            $reservasPorUnidade,
            $inicioSolicitado,
            $fimSolicitado
        ) {
            $reservas = $reservasPorUnidade->get($unidade->id, collect());

            $conflitos = $reservas->filter(function ($reserva) use (
                $inicioSolicitado,
                $fimSolicitado
            ) {
                $inicio = Carbon::parse($reserva->data_inicio)->startOfDay();
                $fim = Carbon::parse($reserva->data_termino)->startOfDay();

                return $inicio->lt($fimSolicitado) &&
                    $fim->gt($inicioSolicitado);
            });

            return [
                'id' => $unidade->id,
                'sigla' => $unidade->sigla ?: $unidade->id,
                'tipo' => optional($unidade->tipohabitacao)->descricao,
                'pet' => (int) $unidade->pet,
                'disponivel_periodo' => $conflitos->isEmpty(),
                'reservas' => $reservas->map(function ($reserva) {
                    return [
                        'id' => $reserva->id,
                        'inicio' => Carbon::parse($reserva->data_inicio)
                            ->format('Y-m-d'),
                        'fim' => Carbon::parse($reserva->data_termino)
                            ->format('Y-m-d'),
                        'inicio_formatado' => Carbon::parse($reserva->data_inicio)
                            ->format('d/m/Y'),
                        'fim_formatado' => Carbon::parse($reserva->data_termino)
                            ->format('d/m/Y'),
                        'hospede' => optional($reserva->usuario)->name
                            ?: 'Hospedagem',
                        'status' => $reserva->status,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'periodo' => [
                'inicio' => $inicioSolicitado->format('d/m/Y'),
                'fim' => $fimSolicitado->format('d/m/Y'),
            ],
            'dias' => $dias,
            'unidades' => $linhas,
        ]);
    }

}
