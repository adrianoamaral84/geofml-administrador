

<script>

	function trocaCalendario(Id){


		
		$('#calendar').fullCalendar({


		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listYear'
		},
		
		defaultDate: '{{ $mes }}',
		editable: false,
		navLinks: true,
		eventLimit: true,
		selectable: false,
		selectHelper: true,
		select: function(start, end) {
			$('#ModalAdd #inicio').val(moment(start).format('DD-MM-YYYY HH:mm:ss'));
			$('#ModalAdd #termino').val(moment(end).format('DD-MM-YYYY HH:mm:ss'));
			$('#ModalAdd').modal('show');
		},
		eventRender: function(event, element) {
			element.bind('click', function() {
				$('#ModalEdit #id_evento').val(event.id);
				$('#ModalEdit #titulo').val(event.title);
				$('#ModalEdit #descricao').val(event.description);
				$('#ModalEdit #cor').val(event.color);
				$('#ModalEdit #convidado').val(event.fk_id_destinatario);
				$('#ModalEdit #remetente').val(event.fk_id_remetente);
				$('#ModalEdit #status').val(event.status);
				$('#ModalEdit #inicio').val(event.start.format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalEdit #termino').val(event.end.format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalEdit').modal('show');
			});
		},
		eventDrop: function(event, delta, revertFunc) { 
			edit(event);
		},
					
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
			edit(event);
		},

		////////////////////////////////////////////////////////////////////



		events: {
   		 url: $host+'/admin/calendario/unidade/json/'+Id,
   		 data: function() { // a function that returns an object
     	return {
        dynamic_value: Math.random()
      };
    }
  }



















//////////////////////////////////////////////////
			});

	
		//alert(Id);

	}












	function modalShow() {
		$('#modalShow').modal('show');
	}

	$(document).ready(function() {
	$protocol = window.location.protocol;
    $host = $protocol+ '//'+$(location).attr('host')+'/GEOFML2.1/public';
	

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
	



	$('#calendars').fullCalendar({

		
		defaultDate: '{{ $mes }}',
		editable: false,
		navLinks: false,
		eventLimit: false,
		selectable: false,
		selectHelper: false,
		
		select: function(start, end) {
			$('#ModalAdd #inicio').val(moment(start).format('DD-MM-YYYY HH:mm:ss'));
			$('#ModalAdd #termino').val(moment(end).format('DD-MM-YYYY HH:mm:ss'));
			$('#ModalAdd').modal('show');
		},

		eventRender: function(event, element) {
			element.bind('click', function() {
				$('#ModalEdit #id_evento').val(event.id);
				$('#ModalEdit #titulo').val(event.title);
				$('#ModalEdit #descricao').val(event.description);
				$('#ModalEdit #cor').val(event.color);
				$('#ModalEdit #convidado').val(event.fk_id_destinatario);
				$('#ModalEdit #remetente').val(event.fk_id_remetente);
				$('#ModalEdit #status').val(event.status);
				$('#ModalEdit #inicio').val(event.start.format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalEdit #termino').val(event.end.format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalEdit').modal('show');
			});
		},
		eventDrop: function(event, delta, revertFunc) { 
			edit(event);
		},
					
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
			edit(event);
		},

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
						
						@endphp

						
						id: {{ $hospedagem->id }},
						title: '{{ $hospedagem->usuario->name }}',
						description: '{{ $hospedagem->usuario->name }}',
						start: '{{ $hospedagem->data_inicio }}',
						end: '{{$DataFinal}}',
						color: '{{ $cores[$gera] }}',
						fk_id_destinatario: '',
						fk_id_remetente: '',
						status:'Aguardando',
						
					},
					
					@endforeach
				]
			});
				
				function edit(event){
					start = event.start.format('DD-MM-YYYY HH:mm:ss');
					if(event.end){
						end = event.end.format('DD-MM-YYYY HH:mm:ss');
					}else{
						end = start;
					}
					
					id_evento =  event.id;
					
					Event = [];
					Event[0] = id_evento;
					Event[1] = start;
					Event[2] = end;
					
					$.ajax({
					url: 'evento/action/eventoEditData.php',
					type: "POST",
					data: {Event:Event},
					success: function(rep) {
							if(rep == 'OK'){
								//alert('Modificação Salva!');
							}else{
								alert('Falha ao salvar, tente novamente!'); 
							}
						}
				});
			}







		});




</script>
