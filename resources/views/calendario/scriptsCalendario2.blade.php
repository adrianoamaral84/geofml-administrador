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
            // A saída é exclusiva: uma nova hospedagem pode iniciar no mesmo dia.
            if (entrada < saida) {
                eventos.push({
                    id: 'ocupacao-' + id, title: nome,
                    start: entrada, end: saida, allDay: true,
                    backgroundColor: '#ba5b32', borderColor: '#ba5b32',
                    extendedProps: { tipo: 'ocupacao' }
                });
            }
            eventos.push({
                id: 'entrada-' + id, title: 'Check-in: ' + nome,
                start: entrada, allDay: true,
                backgroundColor: '#198754', borderColor: '#198754',
                extendedProps: { tipo: 'checkin' }
            });
            eventos.push({
                id: 'saida-' + id, title: 'Check-out: ' + nome,
                start: saida, allDay: true,
                backgroundColor: '#0d6efd', borderColor: '#0d6efd',
                extendedProps: { tipo: 'checkout' }
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
