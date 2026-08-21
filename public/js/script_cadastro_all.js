/*
|--------------------------------------------------------------------------
| FUNÇÕES DE CASCATA
|--------------------------------------------------------------------------
*/

function validarUF(ufId, cidadeFieldName) {
    var $campoCidade = $('select[name="' + cidadeFieldName + '"]');

    if (!ufId) {
        $campoCidade
            .empty()
            .append('<option value="">Selecione uma cidade</option>');

        return;
    }

    $.ajax({
        url: $host + '/cascade/carregarCidades/' + ufId,
        type: 'GET',
        dataType: 'json',

        success: function (data) {
            var cidadeSelecionada = $(
                'input:hidden[name="' + cidadeFieldName + '"]'
            ).val();

            $campoCidade
                .empty()
                .append('<option value="">Cidade</option>');

            if (!data || data.length === 0) {
                $campoCidade
                    .empty()
                    .append('<option value="">Nenhuma cidade encontrada</option>');

                return;
            }

            $.each(data, function (indice, cidade) {
                var selecionado =
                    String(cidadeSelecionada) === String(cidade.id)
                        ? ' selected'
                        : '';

                $campoCidade.append(
                    '<option value="' + cidade.id + '"' + selecionado + '>' +
                        cidade.descricao +
                    '</option>'
                );
            });
        },

        error: function (xhr, textStatus, errorThrown) {
            console.error(
                'Erro ao carregar cidades:',
                xhr.status,
                xhr.responseText
            );

            $campoCidade
                .empty()
                .append('<option value="">Erro ao carregar cidades</option>');

            alert(
                'Erro ao carregar cidades. Status: ' +
                textStatus +
                '. Erro: ' +
                errorThrown
            );
        }
    });
}


function validaOM(cidadeId, campoOm) {
    var $campo = $('select[name="' + campoOm + '"]');

    if (!cidadeId) {
        $campo
            .empty()
            .append('<option value="">Selecione OM</option>');

        return;
    }

    $.ajax({
        url: $host + '/cascade/carregarOm/' + cidadeId,
        type: 'GET',
        dataType: 'json',

        success: function (data) {
            var omSelecionada = $(
                'input:hidden[name="' + campoOm + '"]'
            ).val();

            $campo
                .empty()
                .append('<option value="">OM</option>');

            if (!data || data.length === 0) {
                $campo
                    .empty()
                    .append('<option value="">Nenhuma OM encontrada</option>');

                return;
            }

            $.each(data, function (indice, om) {
                var selecionado =
                    String(omSelecionada) === String(om.id)
                        ? ' selected'
                        : '';

                $campo.append(
                    '<option value="' + om.id + '"' + selecionado + '>' +
                        om.sigla +
                    '</option>'
                );
            });
        },

        error: function (xhr, textStatus, errorThrown) {
            console.error(
                'Erro ao carregar OM:',
                xhr.status,
                xhr.responseText
            );

            $campo
                .empty()
                .append('<option value="">Erro ao carregar OM</option>');

            alert(
                'Erro ao carregar OM. Status: ' +
                textStatus +
                '. Erro: ' +
                errorThrown
            );
        }
    });
}


function validaGrupo(grupoId, campoGrupo) {
    var $campo = $('select[name="' + campoGrupo + '"]');

    if (!grupoId) {
        return;
    }

    $.ajax({
        url: $host + '/cascade/carregarUnidades/' + grupoId,
        type: 'GET',
        dataType: 'json',

        success: function (data) {
            if (!data || data.length === 0) {
                $campo.append(
                    '<option value="">Nenhuma unidade encontrada</option>'
                );

                return;
            }

            $.each(data, function (indice, unidade) {
                $campo.append(
                    '<option value="' + unidade.id + '">' +
                        unidade.sigla +
                    '</option>'
                );
            });
        },

        error: function (xhr, textStatus, errorThrown) {
            console.error(
                'Erro ao carregar Grupo Destinação:',
                xhr.status,
                xhr.responseText
            );

            alert(
                'Erro ao carregar Grupo Destinação. Status: ' +
                textStatus +
                '. Erro: ' +
                errorThrown
            );
        }
    });
}


function validaPostoSituacao(situacaoId, campoPosto) {
    var $campo = $('select[name="' + campoPosto + '"]');

    if (!situacaoId) {
        $campo
            .empty()
            .append(
                '<option value="">Selecione Posto / Graduação</option>'
            );

        return;
    }

    $.ajax({
        url: $host + '/cascade/carregarPostoSituacao/' + situacaoId,
        type: 'GET',
        dataType: 'json',

        success: function (data) {
            var postoSelecionado = $(
                'input:hidden[name="' + campoPosto + '"]'
            ).val();

            $campo
                .empty()
                .append(
                    '<option value="">Selecione Posto / Graduação</option>'
                );

            if (!data || data.length === 0) {
                return;
            }

            $.each(data, function (indice, posto) {
                var selecionado =
                    String(postoSelecionado) === String(posto.id)
                        ? ' selected'
                        : '';

                $campo.append(
                    '<option value="' + posto.id + '"' + selecionado + '>' +
                        posto.sigla +
                    '</option>'
                );
            });
        },

        error: function (xhr, textStatus, errorThrown) {
            console.error(
                'Erro ao carregar Posto/Graduação:',
                xhr.status,
                xhr.responseText
            );

            alert(
                'Erro ao carregar Posto/Graduação. Status: ' +
                textStatus +
                '. Erro: ' +
                errorThrown
            );
        }
    });
}


/*
|--------------------------------------------------------------------------
| CARREGA POSTOS DE ACORDO COM A SITUAÇÃO
|--------------------------------------------------------------------------
|
| Militar da Reserva Remunerado envia situacao_id = 2.
|
*/

function validaPostoSituacaoTodos(situacaoId, campoPosto) {
    var $selectPosto = $('select[name="' + campoPosto + '"]');

    var postoSelecionado =
        $('input:hidden[name="' + campoPosto + '"]').val() ||
        $selectPosto.val();

    if (!situacaoId) {
        $selectPosto
            .empty()
            .append(
                '<option value="">Selecione Posto / Graduação</option>'
            )
            .prop('disabled', false);

        return;
    }

    $selectPosto
        .empty()
        .append('<option value="">Carregando...</option>')
        .prop('disabled', true);

    $.ajax({
        url:
            $host +
            '/cascade/carregarPostoSituacao/all/' +
            situacaoId,

        type: 'GET',
        dataType: 'json',

        success: function (data) {
            $selectPosto
                .empty()
                .append(
                    '<option value="">Selecione Posto / Graduação</option>'
                );

            if (!data || data.length === 0) {
                console.warn(
                    'Nenhum posto encontrado para situacao_id:',
                    situacaoId
                );

                $selectPosto.prop('disabled', false);

                return;
            }

            $.each(data, function (indice, posto) {
                var selecionado =
                    String(postoSelecionado) === String(posto.id)
                        ? ' selected'
                        : '';

                $selectPosto.append(
                    '<option value="' + posto.id + '"' + selecionado + '>' +
                        posto.sigla +
                    '</option>'
                );
            });

            $selectPosto.prop('disabled', false);
        },

        error: function (xhr) {
            console.error(
                'Erro ao carregar Posto/Graduação:',
                xhr.status,
                xhr.responseText
            );

            $selectPosto
                .empty()
                .append(
                    '<option value="">Erro ao carregar postos</option>'
                )
                .prop('disabled', false);

            alert('Não foi possível carregar os postos/graduações.');
        }
    });
}


function validaPosto(forcaId, campoPosto) {
    var $campo = $('select[name="' + campoPosto + '"]');

    if (!forcaId) {
        $campo.empty();

        return;
    }

    $.ajax({
        url: $host + '/cascade/carregarPosto/' + forcaId,
        type: 'GET',
        dataType: 'json',

        success: function (data) {
            var postoSelecionado = $('input:hidden[name="postos"]').val();

            $campo.empty();

            if (!data || data.length === 0) {
                $campo.append('<option value="">Selecione</option>');

                return;
            }

            $.each(data, function (indice, posto) {
                var selecionado =
                    String(postoSelecionado) === String(posto.id)
                        ? ' selected'
                        : '';

                $campo.append(
                    '<option value="' + posto.id + '"' + selecionado + '>' +
                        posto.sigla +
                    '</option>'
                );
            });
        },

        error: function (xhr, textStatus, errorThrown) {
            console.error(
                'Erro ao carregar postos:',
                xhr.status,
                xhr.responseText
            );

            alert(
                'Erro ao carregar postos. Status: ' +
                textStatus +
                '. Erro: ' +
                errorThrown
            );
        }
    });
}


/*
|--------------------------------------------------------------------------
| EXIBIÇÃO DOS CAMPOS POR SITUAÇÃO
|--------------------------------------------------------------------------
*/

function changeFields(idSituacao) {
    idSituacao = String(idSituacao || '');

    // Nenhuma situação selecionada
    if (idSituacao === '' || idSituacao === '0') {
        hiddenFields();

        return;
    }

    // Militar da Ativa
    if (idSituacao === '1') {
        $('.milReserva').hide();
        $('.siape').hide();

        $('.militarAtiva').show();
        $('.ForcaOmPosto').show();
        $('.identidade').show();

        $('#nivel').text(' Posto / Graduação');
        $('#texto').text(' Identidade Militar');
        $('#idtMil').mask('000.000.000-0');

        $('#pttc').prop('checked', false);
        $('#mesAnoFinal')
            .prop('required', false)
            .val('');

        atualizarObrigatoriedadeMesAno();
        militarAtiva();

        return;
    }

    // Militar da Reserva Remunerado — situacao_id = 2
    if (idSituacao === '2') {
        $('.milReserva').show();
        $('.militarAtiva').show();
        $('.ForcaOmPosto').show();
        $('.identidade').show();
        $('.siape').hide();
        $('#nivel').text(' Posto / Graduação');
        $('#texto').text(' Identidade Militar');
        $('#idtMil').mask('000.000.000-0');

        atualizarObrigatoriedadeMesAno();
        militarReserva();

        return;
    }

    // Servidor Civil
    if (idSituacao === '3') {
        $('.milReserva').hide();
        $('.siape').show();
        $('.identidade').show();
        $('.militarAtiva').hide();
        $('.ForcaOmPosto').show();

        $('#texto').text(' Identidade Civil');
        $('#nivel').text(' Nível');
        $('#idtMil').mask('00.000.000-0');

        $('#pttc').prop('checked', false);
        $('#mesAnoFinal')
            .prop('required', false)
            .val('');

        atualizarObrigatoriedadeMesAno();
        servidorCivil();

        return;
    }

    // Pensionista ou demais situações
    $('.milReserva').hide();
    $('.siape').hide();
    $('.militarAtiva').hide();
    $('.ForcaOmPosto').show();
    $('.identidade').show();

    $('#nivel').text(' Posto / Graduação');
    $('#texto').text(' Identidade Militar');
    $('#idtMil').mask('000.000.000-0');

    $('#pttc').prop('checked', false);
    $('#mesAnoFinal')
        .prop('required', false)
        .val('');

    atualizarObrigatoriedadeMesAno();
    pensionista();
}


function hiddenFields() {
    $('.milReserva').hide();
    $('.militarAtiva').hide();
    $('.siape').hide();
    $('.ForcaOmPosto').hide();
    $('.identidade').hide();
    $('.nivelescola').hide();

    $('#pttc').prop('required', false);
    $('#mesAnoFinal').prop('required', false);
    $('#dtUltPromo').prop('required', false);
    $('#forca').prop('required', false);
    $('#posto').prop('required', false);
    $('#siape').prop('required', false);
    $('#idtMil').prop('required', false);
}


function militarAtiva() {
    $('#dtUltPromo').prop('required', true);
    $('#forca').prop('required', true);
    $('#posto').prop('required', true);
    $('#idtMil').prop('required', true);

    $('#pttc').prop('required', false);
    $('#siape').prop('required', false);
}


function militarReserva() {
    $('#dtUltPromo').prop('required', true);
    $('#forca').prop('required', true);
    $('#posto').prop('required', true);
    $('#idtMil').prop('required', true);

    $('#siape').prop('required', false);

    atualizarObrigatoriedadeMesAno();
}


function pensionista() {
    $('#pttc').prop('required', false);
    $('#dtUltPromo').prop('required', false);
    $('#forca').prop('required', true);
    $('#posto').prop('required', true);
    $('#siape').prop('required', false);
    $('#idtMil').prop('required', true);
}


function servidorCivil() {
    $('#siape').prop('required', true);
    $('#idtMil').prop('required', true);

    $('#pttc').prop('required', false);
    $('#dtUltPromo').prop('required', false);
    $('#forca').prop('required', false);
    $('#posto').prop('required', false);
}


function requiredField(field, state) {
    var input = $(field);

    if (String(state).toUpperCase() === 'TRUE') {
        input.prop('required', true);
    } else {
        input.prop('required', false);
    }
}


/*
|--------------------------------------------------------------------------
| PTTC E MÊS/ANO FINAL
|--------------------------------------------------------------------------
*/

function atualizarObrigatoriedadeMesAno() {
    var $pttc = $('#pttc');
    var $mesAnoFinal = $('#mesAnoFinal');
    var $labelMesAnoFinal = $('label[for="mesAnoFinal"]');
    var situacaoId = String($('#situacao').val() || '');

    /*
     * Mês/Ano Final é obrigatório somente quando:
     * - situação = 2;
     * - PTTC estiver marcado.
     */
    if (situacaoId === '2' && $pttc.is(':checked')) {
        $mesAnoFinal.prop('required', true);

        $labelMesAnoFinal.html(
            'Mês/Ano Final <span class="text-danger">*</span>'
        );
    } else {
        $mesAnoFinal.prop('required', false);
        $labelMesAnoFinal.text('Mês/Ano Final');
    }
}


/*
|--------------------------------------------------------------------------
| INICIALIZAÇÃO
|--------------------------------------------------------------------------
*/

$(document).ready(function () {
    window.$protocol = window.location.protocol;
    window.$host =
        window.$protocol + '//' + $(location).attr('host');

    hiddenFields();

    /*
     * Nome do arquivo selecionado.
     */
    $('.custom-file-input').on('change', function () {
        var fileName = $(this).val().split('\\').pop();

        $(this)
            .siblings('.custom-file-label')
            .addClass('selected')
            .html(fileName);
    });


    /*
     * Máscara do campo Mês/Ano Final.
     */
    $('#mesAnoFinal').mask('00/0000');


    /*
     * Ao marcar/desmarcar PTTC, atualiza a obrigatoriedade.
     * O valor não é apagado automaticamente ao desmarcar,
     * permitindo marcar novamente sem perder o que foi digitado.
     */
    $('#pttc').on('change', function () {
        atualizarObrigatoriedadeMesAno();
    });


    /*
     * Carregamento inicial de UF e Cidade.
     */
    var ufInicial = $('select[name="uf"]').val();

    if (ufInicial) {
        validarUF(ufInicial, 'cidade');
    }

    $('select[name="uf"]').on('change', function () {
        validarUF($(this).val(), 'cidade');
    });


    /*
     * Carregamento de OM pela cidade.
     */
    var cidadeOculta = $('input[name="cidadee"]').val();

    if (cidadeOculta) {
        validaOM(cidadeOculta, 'om');
    }

    var cidadeInicial = $('select[name="cidade"]').val();

    if (cidadeInicial) {
        validaOM(cidadeInicial, 'om');
    }

    $('select[name="cidade"]').on('change', function () {
        validaOM($(this).val(), 'om');
    });


    /*
     * Grupo Destinação.
     */
    $('select[name="grupodestinacao"]').on('change', function () {
        validaGrupo($(this).val(), 'grupodestinacao');
    });


    /*
     * Situação e Posto/Graduação.
     *
     * Este é o único evento de alteração da situação.
     * Quando a situação for 2, a requisição enviará exatamente 2.
     */
    var situacaoInicial = $('#situacao').val();

    if (situacaoInicial) {
        changeFields(situacaoInicial);
        validaPostoSituacaoTodos(situacaoInicial, 'posto');
    }

    $('#situacao').on('change', function () {
        var situacaoId = $(this).val();

        changeFields(situacaoId);
        validaPostoSituacaoTodos(situacaoId, 'posto');
    });


    /*
     * Quando o Posto/Graduação for alterado,
     * solicita novamente os documentos.
     */
    $('#posto').on('change', function () {
        if ($('#dtUltPromo').length) {
            $('#dtUltPromo').val('');
        }

        $('#documento').prop('required', true);
        $('#documento_verso').prop('required', true);

        alert('Precisa anexar novo documento');
    });


    /*
     * Atualiza a obrigatoriedade ao abrir a página.
     */
    atualizarObrigatoriedadeMesAno();
});