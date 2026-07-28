<script>
    /*
     * URL base gerada pelo próprio Laravel.
     * Funciona tanto no Laragon quanto em produção.
     */
    var calendarioJsonBaseUrl =
        '{!! url("/admin/calendario/unidade/json") !!}';

    /*
     * Atualiza o calendário de uma unidade.
     */
    function trocaCalendario(unidadeId) {
        var $calendar = $('#calendar');

        if (!$calendar.length) {
            console.warn(
                'O elemento #calendar não existe nesta página.'
            );

            return;
        }

        if (!unidadeId) {
            console.warn(
                'Nenhuma unidade foi informada para o calendário.'
            );

            return;
        }

        /*
         * Se já existir um FullCalendar nesse elemento,
         * destrói antes de criar novamente.
         */
        if ($calendar.hasClass('fc')) {
            $calendar.fullCalendar('destroy');
        }

        $calendar.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listYear'
            },

            defaultDate: '{{ $mes ?? now()->format("Y-m-d") }}',
            editable: false,
            navLinks: true,
            eventLimit: true,
            selectable: false,
            selectHelper: false,

            events: {
                url:
                    calendarioJsonBaseUrl
                    + '/'
                    + encodeURIComponent(unidadeId),

                cache: false,

                data: function () {
                    return {
                        dynamic_value: Date.now()
                    };
                },

                error: function (
                    xhr,
                    status,
                    error
                ) {
                    console.error(
                        'Erro ao carregar eventos da unidade.',
                        {
                            unidade: unidadeId,
                            statusHttp: xhr.status,
                            resposta: xhr.responseText,
                            status: status,
                            erro: error
                        }
                    );
                }
            },

            eventRender: function (
                event,
                element
            ) {
                element.on(
                    'click',
                    function () {
                        if (!$('#ModalEdit').length) {
                            return;
                        }

                        $('#ModalEdit #id_evento')
                            .val(event.id || '');

                        $('#ModalEdit #titulo')
                            .val(event.title || '');

                        $('#ModalEdit #descricao')
                            .val(event.description || '');

                        $('#ModalEdit #cor')
                            .val(event.color || '');

                        $('#ModalEdit #convidado')
                            .val(
                                event.fk_id_destinatario || ''
                            );

                        $('#ModalEdit #remetente')
                            .val(
                                event.fk_id_remetente || ''
                            );

                        $('#ModalEdit #status')
                            .val(event.status || '');

                        $('#ModalEdit #inicio')
                            .val(
                                event.start
                                    ? event.start.format(
                                        'DD-MM-YYYY HH:mm:ss'
                                    )
                                    : ''
                            );

                        $('#ModalEdit #termino')
                            .val(
                                event.end
                                    ? event.end.format(
                                        'DD-MM-YYYY HH:mm:ss'
                                    )
                                    : ''
                            );

                        $('#ModalEdit').modal('show');
                    }
                );
            }
        });
    }

    /*
     * Mantém compatibilidade caso algum código antigo
     * ainda chame a função no plural.
     */
    function trocaCalendarios(unidadeId) {
        trocaCalendario(unidadeId);
    }

    function modalShow() {
        if ($('#modalShow').length) {
            $('#modalShow').modal('show');
        }
    }

    $(document).ready(function () {
        /*
         * Só adiciona o evento se o select existir
         * nessa página.
         */
        if ($('#unidadeshabitacionais').length) {
            $('#unidadeshabitacionais')
                .off('change.calendario')
                .on(
                    'change.calendario',
                    function () {
                        var unidadeId =
                            $(this).val();

                        trocaCalendario(
                            unidadeId
                        );
                    }
                );
        }

        /*
         * Calendário geral com eventos enviados pela view.
         */
        var $calendars =
            $('#calendars');

        if ($calendars.length) {
            if ($calendars.hasClass('fc')) {
                $calendars.fullCalendar(
                    'destroy'
                );
            }

            $calendars.fullCalendar({
                defaultDate:
                    '{{ $mes ?? now()->format("Y-m-d") }}',

                editable: false,
                navLinks: false,
                eventLimit: false,
                selectable: false,
                selectHelper: false,

                eventRender: function (
                    event,
                    element
                ) {
                    element.on(
                        'click',
                        function () {
                            if (
                                !$('#ModalEdit').length
                            ) {
                                return;
                            }

                            $('#ModalEdit #id_evento')
                                .val(event.id || '');

                            $('#ModalEdit #titulo')
                                .val(event.title || '');

                            $('#ModalEdit #descricao')
                                .val(
                                    event.description || ''
                                );

                            $('#ModalEdit #cor')
                                .val(event.color || '');

                            $('#ModalEdit #convidado')
                                .val(
                                    event
                                        .fk_id_destinatario
                                    || ''
                                );

                            $('#ModalEdit #remetente')
                                .val(
                                    event
                                        .fk_id_remetente
                                    || ''
                                );

                            $('#ModalEdit #status')
                                .val(event.status || '');

                            $('#ModalEdit #inicio')
                                .val(
                                    event.start
                                        ? event.start.format(
                                            'DD-MM-YYYY HH:mm:ss'
                                        )
                                        : ''
                                );

                            $('#ModalEdit #termino')
                                .val(
                                    event.end
                                        ? event.end.format(
                                            'DD-MM-YYYY HH:mm:ss'
                                        )
                                        : ''
                                );

                            $('#ModalEdit')
                                .modal('show');
                        }
                    );
                },

                events: [
                    @php
                        $cores = [
                            '#000000',
                            '#0000FF',
                            '#4682B4',
                            '#008000',
                            '#FF0000',
                            '#6959CD',
                            '#363636',
                            '#A9A9A9',
                            '#836FFF',
                            '#000080',
                            '#6495ED',
                            '#00BFFF',
                            '#ADD8E6',
                            '#4682B4',
                            '#708090',
                            '#40E0D0',
                            '#008080',
                            '#98FB98',
                            '#3CB371',
                            '#006400',
                            '#228B22',
                            '#00FF00',
                            '#7FFF00',
                            '#808000',
                            '#B8860B',
                            '#A0522D',
                            '#CD853F',
                            '#F4A460',
                            '#7B68EE',
                            '#800000',
                            '#FAF0E6',
                            '#FFE4C4',
                            '#FFDAB9',
                            '#E6E6FA',
                            '#F0FFF0'
                        ];
                    @endphp

                    @foreach(($hospedagens ?? collect()) as $item)
                        {
                            id:
                                {{ $item->id }},

                            title:
                                @json(
                                    optional(
                                        $item->usuario
                                    )->name ?? 'Hospedagem'
                                ),

                            description:
                                @json(
                                    optional(
                                        $item->usuario
                                    )->name ?? ''
                                ),

                            start:
                                @json(
                                    $item->data_inicio
                                ),

                            /*
                             * O FullCalendar trata o final
                             * como limite exclusivo.
                             *
                             * Não acrescentamos mais um dia aqui.
                             */
                            end:
                                @json(
                                    $item->data_termino
                                ),

                            color:
                                @json(
                                    $cores[
                                        $loop->index
                                        % count($cores)
                                    ]
                                ),

                            fk_id_destinatario: '',
                            fk_id_remetente: '',
                            status: 'Aguardando'
                        }@if(!$loop->last),@endif
                    @endforeach
                ]
            });
        }
    });
</script>