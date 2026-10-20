<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendars');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    var eventos = [];
    @foreach(($hospedagens ?? collect()) as $hospedagem)
        @php
            $usuario = $hospedagem->usuario;
            $posto = optional(optional($usuario)->posto)->sigla;
            $nome = trim(($posto ? $posto . ' - ' : '') . (optional($usuario)->name ?: 'Hospedagem'));
            $entrada = \Carbon\Carbon::parse($hospedagem->data_inicio)->format('Y-m-d');
            $saida = \Carbon\Carbon::parse($hospedagem->data_termino)->format('Y-m-d');
        @endphp
        (function () {
            var id = @json($hospedagem->id);
            var nome = @json($nome);
            var entrada = @json($entrada);
            var saida = @json($saida);
            // A data final dos eventos no FullCalendar é exclusiva.
            // O dia de saída deve aparecer na barra de hospedagem.
            var fimVisual = new Date(saida + 'T12:00:00');
            fimVisual.setDate(fimVisual.getDate() + 1);
            var fimFormatado = fimVisual.getFullYear() + '-' +
                String(fimVisual.getMonth() + 1).padStart(2, '0') + '-' +
                String(fimVisual.getDate()).padStart(2, '0');

            eventos.push({
                id: 'ocupacao-' + id,
                title: nome,
                start: entrada,
                end: fimFormatado,
                allDay: true,
                backgroundColor: '#ba5b32',
                borderColor: '#ba5b32',
                extendedProps: { tipo: 'ocupacao' }
            });
        })();
    @endforeach

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        initialDate: @json($mes ?? date('Y-m-d')),
        navLinks: false,
        editable: false,
        selectable: false,
        dayMaxEvents: false,
        events: eventos
    });
    calendar.render();
});
</script>
