<?php

namespace Tests\Unit;

use App\Hospede;
use App\Services\CalculoHospedagemService;
use App\User;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class CalculoHospedagemMecenasTest extends TestCase
{
    public function test_regras_de_tolerancia_extras_e_desconto_sem_duplicacao(): void
    {
        Mockery::mock('alias:App\\Horario')->shouldReceive('first')->andReturn((object) [
            'entrada' => '15:00:00', 'saida' => '12:00:00', 'tolerancia' => 2,
        ]);

        try {
            // Entrada, saída, diárias esperadas, extras de entrada e saída.
            $casos = [
                ['10 13:00:00', '15 14:00:00', 5, 0, 0],
                ['10 12:59:59', '15 14:00:00', 6, 1, 0],
                ['09 15:00:00', '15 14:00:00', 6, 1, 0],
                ['08 15:00:00', '15 14:00:00', 7, 2, 0],
                ['11 14:17:04', '12 10:00:00', 5, 0, 0],
                ['10 15:00:00', '14 23:00:00', 5, 0, 0],
                ['10 15:00:00', '15 14:00:01', 6, 0, 1],
                ['10 15:00:00', '16 13:00:00', 6, 0, 1],
                ['10 15:00:00', '16 14:00:01', 7, 0, 2],
                ['10 12:00:00', '15 15:00:00', 7, 1, 1],
            ];

            foreach ([false, true] as $mecenas) {
                foreach ($mecenas ? [66, 46.20] : [66] as $salva) {
                    foreach ($casos as [$entrada, $saida, $dias, $extraEntrada, $extraSaida]) {
                        $hospedagem = new HospedeComTarifaTeste;
                        $user = new User;
                        $user->mecenas = $mecenas;
                        $hospedagem->setRelation('user', $user);
                        $hospedagem->valortarifa = $salva;
                        $hospedagem->valor_pago = 46.20;
                        $hospedagem->data_inicio = '2026-11-10';
                        $hospedagem->data_termino = '2026-11-15';
                        $hospedagem->checkin_at = '2026-11-'.$entrada;
                        $hospedagem->checkout_at = '2026-11-'.$saida;
                        $resultado = (new CalculoHospedagemService)->calcular($hospedagem);
                        $tarifa = $mecenas ? 46.20 : 66;
                        $this->assertEquals($dias, $resultado['dias']);
                        $this->assertEquals($extraEntrada, $resultado['diarias_extra_entrada']);
                        $this->assertEquals($extraSaida, $resultado['diarias_extra_saida']);
                        $this->assertEquals($tarifa, $resultado['valor_tarifa']);
                        $this->assertEquals(round($dias * $tarifa, 2), $resultado['valor_total']);
                        $this->assertEquals(round($dias * $tarifa - 46.20, 2), $resultado['valor_restante']);
                        $this->assertEquals($mecenas ? round($dias * 19.80, 2) : 0, $resultado['desconto_total']);

                        Carbon::setTestNow(Carbon::parse($hospedagem->checkout_at, 'America/Sao_Paulo'));
                        $hospedagem->checkout_at = null;
                        $this->assertEquals($resultado, (new CalculoHospedagemService)->calcular($hospedagem));
                        // Reabrir a tela após salvar o recálculo não deve duplicar desconto.
                        $hospedagem->valor = $resultado['valor_total'];
                        $hospedagem->qntdiarias = $resultado['dias'];
                        $this->assertEquals($resultado, (new CalculoHospedagemService)->calcular($hospedagem));
                        $hospedagem->valor_pago = 10000;
                        $this->assertEquals(0, (new CalculoHospedagemService)->calcular($hospedagem)['valor_restante']);
                    }
                }
            }
        } finally {
            Carbon::setTestNow();
            Mockery::close();
        }
    }
}

class HospedeComTarifaTeste extends Hospede
{
    protected function tarifaPrimeiraDiariaSemDesconto()
    {
        return 66.0;
    }
}
