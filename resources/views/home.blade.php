@extends('layouts.app')

@section('content')
<style>
    .dashboard-card { border: 0; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.07); height: 100%; }
    .dashboard-card .card-block { padding: 20px; }
    .metric { display:flex; align-items:center; justify-content:space-between; min-height:105px; }
    .metric-icon { width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:21px; background:#eef3f8; color:#315d83; }
    .metric-value { font-size:28px; font-weight:700; line-height:1; color:#2d3748; }
    .metric-label { margin-top:8px; color:#6c757d; font-size:13px; }
    .section-title { margin: 12px 0 18px; font-size:18px; font-weight:600; color:#34495e; }
    .chart-container { position:relative; height:310px; }
    .badge-dashboard { padding:6px 10px; border-radius:12px; font-size:11px; }
    .table-dashboard td, .table-dashboard th { vertical-align:middle; }
    .progress { height:8px; margin-top:12px; }
    .chart-container {
    position: relative;
    width: 100%;
    height: 320px;
}
</style>

<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="title">Dashboard operacional</h3>
                    <p class="title-description">Visão geral das hospedagens e da administração do GeoFML.</p>
                </div>
                <div class="col-md-4 text-right">
                    <small class="text-muted">Atualizado em {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    @if($count > 0)
        <div class="alert alert-primary" role="alert">
            <div class="text-center">
                <i class="fa fa-envelope"></i>
                Você tem {{ $count }} {{ $count == 1 ? 'nova mensagem' : 'novas mensagens' }}.
            </div>
        </div>
    @endif

    <h4 class="section-title"><i class="fa fa-bed"></i> Operação de hospedagem</h4>
    <div class="row sameheight-container">
        @foreach([
            ['Hóspedes atuais', $hospedesAtuais, 'fa-users'],
            ['Quartos ocupados', $quartosOcupados . ' / ' . $totalUnidades, 'fa-bed'],
            ['Taxa de ocupação', number_format($taxaOcupacao, 1, ',', '.') . '%', 'fa-percent'],
            ['Check-ins hoje', $checkinsHoje, 'fas fa-sign-in-alt'],
            ['Check-outs hoje', $checkoutsHoje, 'fas fa-sign-out-alt'],
            ['Reservas pendentes', $reservasPendentes, 'fa-clock-o']
        ] as $item)
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-3">
            <div class="card dashboard-card">
                <div class="card-block metric">
                    <div>
                        <div class="metric-value">{{ $item[1] }}</div>
                        <div class="metric-label">{{ $item[0] }}</div>
                        @if($item[0] == 'Taxa de ocupação')
                            <div class="progress"><div class="progress-bar" style="width: {{ min($taxaOcupacao, 100) }}%"></div></div>
                        @endif
                    </div>
                    <div class="metric-icon"><i class="fa {{ $item[2] }}"></i></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-3">
        <div class="col-lg-8 mb-3">
            <div class="card dashboard-card">
                <div class="card-block">
                    <h4 class="section-title">Taxa de ocupação — últimos 12 meses</h4>
                    <div class="chart-container"><canvas id="graficoOcupacao"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card dashboard-card">
                <div class="card-block">
                    <h4 class="section-title">Hospedagens por situação</h4>
                    <div class="chart-container"><canvas id="graficoHospedagensStatus"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="card dashboard-card">
                <div class="card-block">
                    <h4 class="section-title">Entradas e saídas por mês</h4>
                    <div class="chart-container"><canvas id="graficoMovimentacao"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="card dashboard-card">
                <div class="card-block">
                    <h4 class="section-title">Movimentação de hoje</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-dashboard">
                            <thead><tr><th>Hóspede</th><th>UH</th><th>Período</th><th>Movimento</th></tr></thead>
                            <tbody>
                            @forelse($movimentacaoHoje as $hospedagem)
                                @php
                                    $entradaHoje = \Carbon\Carbon::parse($hospedagem->data_inicio)->isToday();
                                    $saidaHoje = \Carbon\Carbon::parse($hospedagem->data_termino)->isToday();
                                @endphp
                                <tr>
                                    <td>{{ optional($hospedagem->usuario)->name ?: $hospedagem->user_cpf }}</td>
                                    <td>{{ optional($hospedagem->undHB)->sigla ?? optional($hospedagem->undHB)->numero ?? '-' }}</td>
                                    <td><small>{{ \Carbon\Carbon::parse($hospedagem->data_inicio)->format('d/m') }} a {{ \Carbon\Carbon::parse($hospedagem->data_termino)->format('d/m') }}</small></td>
                                    <td>
                                        @if($entradaHoje)<span class="badge badge-success badge-dashboard">Entrada</span>
                                        @elseif($saidaHoje)<span class="badge badge-warning badge-dashboard">Saída</span>
                                        @else<span class="badge badge-primary badge-dashboard">Hospedado</span>@endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Nenhuma movimentação para hoje.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="section-title mt-4"><i class="fa fa-money"></i> Visão financeira</h4>
    <div class="row sameheight-container">
        @foreach([
            ['Recebido no mês', 'R$ ' . number_format($recebidoMes, 2, ',', '.'), 'fa-money'],
            ['Valores pendentes', 'R$ ' . number_format($valorPendente, 2, ',', '.'), 'fa-hourglass-half'],
            ['Ticket médio do mês', 'R$ ' . number_format($ticketMedioMes, 2, ',', '.'), 'fa-calculator'],
            ['Extras no mês', 'R$ ' . number_format($extrasMes, 2, ',', '.'), 'fa-plus-circle']
        ] as $item)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card dashboard-card"><div class="card-block metric">
                <div><div class="metric-value" style="font-size:23px">{{ $item[1] }}</div><div class="metric-label">{{ $item[0] }}</div></div>
                <div class="metric-icon"><i class="fa {{ $item[2] }}"></i></div>
            </div></div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card dashboard-card"><div class="card-block">
                <h4 class="section-title">Recebimentos e valores pendentes — últimos 12 meses</h4>
                <div class="chart-container"><canvas id="graficoFinanceiro"></canvas></div>
                <small class="text-muted">Recebimentos são vinculados ao mês do checkout. Pendências são agrupadas pelo mês de início da hospedagem.</small>
            </div></div>
        </div>
    </div>

    <h4 class="section-title mt-4"><i class="fa fa-user-circle"></i> Gestão de usuários</h4>
    <div class="row sameheight-container">
        @foreach([
            ['Total de usuários', $totalUsuarios, 'fa-users'],
            ['Usuários ativos', $usuariosAtivos, 'fa-user-check'],
            ['Aguardando cadastro', $usuariosPreCadastro, 'fa-user-plus'],
            ['Inativos/bloqueados', $usuariosInativos, 'fa-user-times']
        ] as $item)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card dashboard-card"><div class="card-block metric">
                <div><div class="metric-value">{{ $item[1] }}</div><div class="metric-label">{{ $item[0] }}</div></div>
                <div class="metric-icon"><i class="fa {{ $item[2] }}"></i></div>
            </div></div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card dashboard-card"><div class="card-block">
                <h4 class="section-title">Novos usuários — últimos 12 meses</h4>
                <div class="chart-container"><canvas id="graficoNovosUsuarios"></canvas></div>
            </div></div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card dashboard-card"><div class="card-block">
                <h4 class="section-title">Usuários por situação</h4>
                <div class="chart-container"><canvas id="graficoUsuariosStatus"></canvas></div>
            </div></div>
        </div>
    </div>
</article>
@endsection

@push('javascript')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficoUsuariosStatus');

    if (!canvas) {
        console.error('Canvas graficoUsuariosStatus não encontrado.');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado.');
        return;
    }

    const labelsUsuarios = @json($usuariosPorStatus->pluck('situacao')->values());
    const totaisUsuarios = @json($usuariosPorStatus->pluck('total')->values());

    const palette = [
        '#315d83',
        '#28a745',
        '#ffc107',
        '#dc3545',
        '#6c757d',
        '#17a2b8'
    ];

    console.log('Situações:', labelsUsuarios);
    console.log('Totais:', totaisUsuarios);

    new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labelsUsuarios,
            datasets: [{
                label: 'Usuários',
                data: totaisUsuarios.map(function (valor) {
                    return Number(valor) || 0;
                }),
                backgroundColor: palette,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const valor = context.parsed || 0;
                            return context.label + ': ' + valor;
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficoNovosUsuarios');

    if (!canvas) {
        console.error('Canvas graficoNovosUsuarios não encontrado.');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado.');
        return;
    }

    const mesesUsuarios = @json($meses);
    const novosUsuarios = @json($novosUsuarios);

    console.log('Meses:', mesesUsuarios);
    console.log('Novos usuários:', novosUsuarios);

    new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: mesesUsuarios,
            datasets: [{
                label: 'Novos usuários',
                data: novosUsuarios.map(function (valor) {
                    return Number(valor) || 0;
                }),
                backgroundColor: '#315d83',
                borderColor: '#315d83',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Novos usuários: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    try {
        const canvas = document.getElementById('graficoMovimentacao');

        if (!canvas) {
            console.error('Canvas graficoMovimentacao não encontrado.');
            return;
        }

        if (typeof Chart === 'undefined') {
            console.error('Chart.js não foi carregado.');
            return;
        }

        const mesesMovimentacao = @json($meses);
        const checkinsMensais = @json($checkinsMensais);
        const checkoutsMensais = @json($checkoutsMensais);

        console.log('Meses:', mesesMovimentacao);
        console.log('Check-ins:', checkinsMensais);
        console.log('Check-outs:', checkoutsMensais);

        new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: mesesMovimentacao,
                datasets: [
                    {
                        label: 'Entradas',
                        data: checkinsMensais.map(function (valor) {
                            return Number(valor) || 0;
                        }),
                        backgroundColor: '#47a447'
                    },
                    {
                        label: 'Saídas',
                        data: checkoutsMensais.map(function (valor) {
                            return Number(valor) || 0;
                        }),
                        backgroundColor: '#f0ad4e'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } catch (erro) {
        console.error('Erro ao criar gráfico de movimentação:', erro);
    }
});
</script>
<script>
(function () {
    var meses = @json($meses);
    var gridColor = 'rgba(0,0,0,.06)';
    var palette = ['#315d83','#47a447','#f0ad4e','#d9534f','#5bc0de','#8e6bbd','#6c757d','#20a8d8'];

    function baseOptions(percentual) {
        return {
            responsive: true, maintainAspectRatio: false,
            legend: { display: true, position: 'bottom' },
            scales: { yAxes: [{ ticks: { beginAtZero: true, callback: percentual ? function(v){ return v + '%'; } : undefined }, gridLines: { color: gridColor } }], xAxes: [{ gridLines: { display:false } }] }
        };
    }

    new Chart(document.getElementById('graficoOcupacao'), {
        type: 'line', data: { labels: meses, datasets: [{ label: 'Ocupação', data: @json($ocupacaoMensal), borderColor:'#315d83', backgroundColor:'rgba(49,93,131,.12)', pointBackgroundColor:'#315d83', fill:true }] },
        options: baseOptions(true)
    });

    new Chart(document.getElementById('graficoHospedagensStatus'), {
        type: 'doughnut', data: { labels: @json($hospedagensPorStatus->pluck('situacao')), datasets: [{ data: @json($hospedagensPorStatus->pluck('total')), backgroundColor: palette }] },
        options: { responsive:true, maintainAspectRatio:false, legend:{ position:'bottom' } }
    });

    
    new Chart(document.getElementById('graficoFinanceiro'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                { label:'Recebido', data:@json($recebimentosMensais), backgroundColor:'#47a447' },
                { label:'Pendente', data:@json($pendenciasMensais), backgroundColor:'#f0ad4e' }
            ]
        },
        options: {
            responsive:true,
            maintainAspectRatio:false,
            legend:{ display:true, position:'bottom' },
            tooltips:{ callbacks:{ label:function(item, data){
                var valor = data.datasets[item.datasetIndex].data[item.index] || 0;
                return data.datasets[item.datasetIndex].label + ': R$ ' + Number(valor).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2});
            }}},
            scales:{
                yAxes:[{ticks:{beginAtZero:true, callback:function(v){ return 'R$ ' + Number(v).toLocaleString('pt-BR'); }}, gridLines:{color:gridColor}}],
                xAxes:[{gridLines:{display:false}}]
            }
        }
    });

   
})();
</script>
@endpush
