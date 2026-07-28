<?php

namespace App\Http\Controllers\Calendario;

use App\Hospede;
use App\Http\Controllers\Controller;
use App\UnidadeHabitacional;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index($id)
    {
        $mes = date('Y-m-d');
        $hospedagens = Hospede::with('usuario')->get();

        return view('calendario.cale', compact('hospedagens', 'mes'));
    }

    public function calendarioMes($mes)
    {
        $mesNumero = str_pad((string) $mes, 2, '0', STR_PAD_LEFT);

        $hospedagens = Hospede::query()
            ->whereMonth('data_inicio', $mesNumero)
            ->orderBy('data_inicio')
            ->get();

        $mes = date("Y-{$mesNumero}-d");

        $unidades_habitacionais = UnidadeHabitacional::query()
            ->where('disponivel', 1)
            ->get();

        return view('calendario.cale', compact(
            'hospedagens',
            'mes',
            'unidades_habitacionais'
        ));
    }

    public function calendarioUnidade($unidade, $data_ini, $data_final)
    {
        $mes = Carbon::createFromFormat('d-m-Y', $data_ini)->format('Y-m-d');

        $hospedagens = Hospede::query()
            ->with(['usuario', 'tipouh'])
            ->where('und_habitacionais_id', $unidade)
            ->whereIn('status', [2, 3, 4, 5])
            ->whereNull('checkout_at')
            ->orderBy('data_inicio')
            ->get();

        $unidades_habitacionais = UnidadeHabitacional::query()
            ->where('disponivel', 1)
            ->get();

        return view('calendario.cale', compact(
            'hospedagens',
            'mes',
            'unidades_habitacionais'
        ));
    }

    public function calendarioporUnidade($unidade)
    {
        $mes = date('Y-m-d');

        $hospedagens = Hospede::query()
            ->with('usuario')
            ->where('und_habitacionais_id', $unidade)
            ->where('status', 0)
            ->orderBy('data_inicio')
            ->get();

        $unidades_habitacionais = UnidadeHabitacional::query()
            ->where('disponivel', 1)
            ->get();

        return view('calendario.cale', compact(
            'hospedagens',
            'mes',
            'unidades_habitacionais'
        ));
    }

    public function calendarioUnidadeJson(Request $request, $unidade)
    {
        return $this->eventosUnidade($request, $unidade);
    }

    public function eventosUnidade(Request $request, $unidade)
    {
        $query = Hospede::query()
            ->with('usuario')
            ->where('und_habitacionais_id', $unidade)
            ->whereIn('status', [2, 3, 4, 5])
            ->whereNull('checkout_at');

        if ($request->filled('ignorar_hospedagem')) {
            $query->where(
                'id',
                '<>',
                (int) $request->input('ignorar_hospedagem')
            );
        }

        if ($request->filled('start') && $request->filled('end')) {
            $inicio = Carbon::parse($request->input('start'))->startOfDay();
            $fim = Carbon::parse($request->input('end'))->startOfDay();

            $query
                ->whereDate('data_inicio', '<', $fim->format('Y-m-d'))
                ->whereDate('data_termino', '>', $inicio->format('Y-m-d'));
        }

        $eventos = $query
            ->orderBy('data_inicio')
            ->get()
            ->map(function (Hospede $hospedagem) {
                $nome = optional($hospedagem->usuario)->name ?: 'Hospedagem';

                return [
                    'id' => $hospedagem->id,
                    'title' => 'Ocupada - ' . $nome,
                    'start' => Carbon::parse($hospedagem->data_inicio)
                        ->format('Y-m-d'),
                    'end' => Carbon::parse($hospedagem->data_termino)
                        ->format('Y-m-d'),
                    'allDay' => true,
                    'color' => '#dc3545',
                    'textColor' => '#ffffff',
                    'status' => $hospedagem->status,
                ];
            })
            ->values();

        return response()->json($eventos);
    }
}
