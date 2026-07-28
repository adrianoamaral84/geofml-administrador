<?php

namespace App\Services;

use App\Hospede;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DisponibilidadeHospedagemService
{
    public const STATUS_ATIVOS = [2, 3, 4, 5];

    public function queryConflitos(
        int $unidadeId,
        Carbon $inicio,
        Carbon $fim,
        ?int $ignorarHospedagemId = null
    ): Builder {
        $query = Hospede::query()
            ->ativas()
            ->where('und_habitacionais_id', $unidadeId)
            ->sobrepostas($inicio, $fim)
            ->orderBy('data_inicio');

        if ($ignorarHospedagemId) {
            $query->where('id', '<>', $ignorarHospedagemId);
        }

        return $query;
    }
    public function buscarConflitos(
        $unidadeId,
        Carbon $inicio,
        Carbon $fim,
        $ignorarHospedagemId = null
    ) {
        $query = Hospede::query()
            ->where(
                'und_habitacionais_id',
                $unidadeId
            )
            ->whereIn('status', [2, 3, 4, 5])
            ->whereNull('checkout_at')
            ->whereDate(
                'data_inicio',
                '<',
                $fim->format('Y-m-d')
            )
            ->whereDate(
                'data_termino',
                '>',
                $inicio->format('Y-m-d')
            )
            ->orderBy('data_inicio');

        if ($ignorarHospedagemId) {
            $query->where(
                'id',
                '<>',
                $ignorarHospedagemId
            );
        }

        return $query->get([
            'id',
            'data_inicio',
            'data_termino',
            'und_habitacionais_id',
            'status',
        ]);
    }
    public function temConflito(
        int $unidadeId,
        Carbon $inicio,
        Carbon $fim,
        ?int $ignorarHospedagemId = null
    ): bool {
        return $this->queryConflitos(
            $unidadeId,
            $inicio,
            $fim,
            $ignorarHospedagemId
        )->exists();
    }

    public function primeiroConflito(
        int $unidadeId,
        Carbon $inicio,
        Carbon $fim,
        ?int $ignorarHospedagemId = null
    ): ?Hospede {
        return $this->queryConflitos(
            $unidadeId,
            $inicio,
            $fim,
            $ignorarHospedagemId
        )->first();
    }

    public function conflitos(
        int $unidadeId,
        Carbon $inicio,
        Carbon $fim,
        ?int $ignorarHospedagemId = null
    ) {
        return $this->queryConflitos(
            $unidadeId,
            $inicio,
            $fim,
            $ignorarHospedagemId
        )->get();
    }
}
