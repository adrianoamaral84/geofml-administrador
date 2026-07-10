


<script>

		function modalShow() {
		$('#modalShow').modal('show');
	}

	$(document).ready(function() {
	$protocol = window.location.protocol;
    $host = $protocol+ '//'+$(location).attr('host')+'/GEOFML2.1/public';
	// trocar host

	if($('select[name="unidadeshabitacionais"] option:selected').val() != '') {
        var unidadeshabitacionais = $('select[name="unidadeshabitacionais"] option:selected').val();
        //changeFields(unidadeshabitacionais);
        //validarUF(situacao, 'cidadeEndereco_id');
        //alert(unidadeshabitacionais);
        //validaPostoSituacao(situacao, 'posto');
    }
    

    $('#unidadeshabitacionais').on('change', ()=>{
        id_situacao = $('#unidadeshabitacionais').val();
        //changeFields(id_situacao);
        //alert(id_situacao);
       
        //trocaCalendario(id_situacao, 'unidadeshabitacionais');
        trocaCalendarios(id_situacao);

    });



</script>
<script>

  	document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendars');
    var calendar = new FullCalendar.Calendar(calendarEl, {
    	
      	headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },

      initialDate: '{{ $mes }}',
      navLinks: false, // can click day/week names to navigate views
      businessHours: false, // display business hours
      editable: false,
      selectable: false,
      events: [


      				@php 
					$cores = ['#000000', '#0000FF', '#4682B4', '#008000', '#FF0000', '#6959CD','#363636', '#A9A9A9', '#836FFF', '#000080', '#6495ED', '#00BFFF', '#ADD8E6', '#4682B4', '#708090', '#40E0D0', '#008080', '#98FB98', '#3CB371', '#006400', '#228B22', '#00FF00', '#7FFF00', '#808000', '#B8860B', '#A0522D', '#CD853F', '#F4A460', '#7B68EE', '#800000', '#FAF0E6', '#FFE4C4', '#FFDAB9', '#E6E6FA', '#E6E6FA', '#F0FFF0'];
					
					$quantidade = count($cores);
					
					@endphp
													
					@foreach($hospedagens as $hospedagem)

					{
						@php 
						$gera = rand(1,35);
						
						$Date1 = $hospedagem->data_termino;
						$date = new DateTime($Date1);
						$date->add(new DateInterval('P1D')); // P1D means a period of 1 day
						$DataFinal = $date->format('Y-m-d');							
					
						$Date2 = $hospedagem->data_inicio;
						$datainicial = new DateTime($Date2);
						$datainicial->sub(new DateInterval('P1D'));
						$datainicial1 = $datainicial->format('Y-m-d');							

						@endphp

						
						id: {{ $hospedagem->id }},
						title: '{{ $hospedagem->usuario->name }}',
						description: '{{ $hospedagem->usuario->name }}',
						start: '{{ $hospedagem->data_inicio }}',
						end: '{{$DataFinal}}',
						color: '{{ $cores[$gera] }}',						
					},
					
					@endforeach
        
      	]
    	});

    	calendar.render();

  		});

		</script>
						