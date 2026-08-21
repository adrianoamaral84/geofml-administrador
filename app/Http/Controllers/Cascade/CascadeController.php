<?php

namespace App\Http\Controllers\Cascade;

use App\Cidade;
use App\GerenciarOm;
use App\Hospede;
use App\Http\Controllers\Controller;
use App\PostoGraduacao;
use App\UnidadeHabitacional;
use App\Services\DisponibilidadeHospedagemService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CascadeController extends Controller
{
    private $disponibilidade;

    public function __construct(
        DisponibilidadeHospedagemService $disponibilidade
    ) {
        $this->disponibilidade = $disponibilidade;
    }
    public function carregarCidades($id)
    {
        return response()->json(Cidade::where('uf_id', $id)->get());
    }

    public function carregarOm($id)
    {
        return response()->json(GerenciarOm::where('cidade_id', $id)->get());
    }

    public function carregarPosto($id)
    {
        return response()->json(
            PostoGraduacao::where('forca_id', $id)->get()
        );
    }

    public function carregarPostoSituacao($id)
    {
        return response()->json(
            PostoGraduacao::where('situacao_id', $id)
                ->whereNotIn('id', [59, 60])
                ->get()
        );
    }

public function carregarPostoSituacaoTodos($id)
{
    $listaPosto = PostoGraduacao::where(
        'situacao_id',
        (int) $id
    )
    ->orderBy('id')
    ->get();

    return response()->json($listaPosto);
}

    public function carregarUnidadesHabtacionais(
        Request $request,
        $id,
        $tipo
    ) {
        $query = UnidadeHabitacional::query()
            ->where('grupo_destinacao_id', $id)
            ->where('disponivel', 1)
            ->with('tipohabitacao')
            ->orderBy('sigla');

        if ((int) $tipo === 11 || (int) $tipo === 12) {
            $query->where('tipo_und_hab_id', $tipo);
        }

        $unidades = $query->get();

        if (
            !$request->filled('data_inicio') ||
            !$request->filled('data_final')
        ) {
            return response()->json($unidades);
        }

        try {
            $inicio = Carbon::createFromFormat(
                'd-m-Y',
                $request->input('data_inicio')
            )->startOfDay();

            $fim = Carbon::createFromFormat(
                'd-m-Y',
                $request->input('data_final')
            )->startOfDay();
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Período inválido. Use DD-MM-YYYY.',
            ], 422);
        }

        if ($fim->lte($inicio)) {
            return response()->json([
                'message' => 'A saída deve ser posterior à entrada.',
            ], 422);
        }

        $ignorar = $request->filled('ignorar_hospedagem')
            ? (int) $request->input('ignorar_hospedagem')
            : null;

        $resultado = $unidades->map(function ($unidade) use (
            $inicio,
            $fim,
            $ignorar
        ) {
            $conflitos = $this->disponibilidade
                ->conflitos(
                    (int) $unidade->id,
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

            return [
                'id' => $unidade->id,
                'sigla' => $unidade->sigla,
                'pet' => (int) $unidade->pet,
                'tipohabitacao' => $unidade->tipohabitacao,
                'disponivel_periodo' => $conflitos->isEmpty(),
                'conflitos' => $conflitos,
            ];
        })->values();

        return response()->json($resultado);
    }
}
