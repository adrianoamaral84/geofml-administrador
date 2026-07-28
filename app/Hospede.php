<?php

namespace App;

use App\Services\DisponibilidadeHospedagemService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Hospede extends Model
{
    protected $table = 'hospedagem';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tipouh()
    {
        return $this->belongsTo(TipoUndHab::class, 'tipo_und_id');
    }

    public function undHB()
    {
        return $this->belongsTo(
            UnidadeHabitacional::class,
            'und_habitacionais_id'
        );
    }

    public function comprovante()
    {
        return $this->hasOne(Comprovante::class, 'hospedagem_id');
    }

    public function status_hospedagem()
    {
        return $this->belongsTo(Status_hospedagem::class, 'status');
    }

    public function scopeAtivas(Builder $query): Builder
    {
        return $query
            ->whereIn('status', DisponibilidadeHospedagemService::STATUS_ATIVOS)
            ->whereNull('checkout_at');
    }

    public function scopeSobrepostas(
        Builder $query,
        Carbon $inicio,
        Carbon $fim
    ): Builder {
        return $query
            ->whereDate('data_inicio', '<', $fim->format('Y-m-d'))
            ->whereDate('data_termino', '>', $inicio->format('Y-m-d'));
    }

    public function valorTarifaComDesconto()
    {
        if (!$this->user) {
            return $this->valortarifa;
        }

        return $this->user->aplicarDesconto($this->valortarifa);
    }
}
