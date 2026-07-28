<script>
(function ($) {
    'use strict';

    $(function () {
        var $calendar = $('#calendars');

        if (
            !$calendar.length ||
            typeof $.fn.fullCalendar !== 'function'
        ) {
            return;
        }

        if ($calendar.hasClass('fc')) {
            $calendar.fullCalendar('destroy');
        }

        $calendar.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,listYear'
            },
            defaultDate: '{{ $mes ?? date("Y-m-d") }}',
            editable: false,
            navLinks: true,
            eventLimit: true,
            selectable: false,
            locale: 'pt-br',
            events: [
                @foreach(($hospedagens ?? collect()) as $item)
                    {
                        id: {{ (int) $item->id }},
                        title: @json(optional($item->usuario)->name ?: 'Hospedagem'),
                        start: @json(\Carbon\Carbon::parse($item->data_inicio)->format('Y-m-d')),
                        end: @json(\Carbon\Carbon::parse($item->data_termino)->format('Y-m-d')),
                        allDay: true,
                        color: '#dc3545',
                        textColor: '#ffffff'
                    }@if(!$loop->last),@endif
                @endforeach
            ]
        });
    });
})(jQuery);
</script>
