<!DOCTYPE html>
<html lang="pt-br">

	<head>

    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<meta name="description" content="">
    	<meta name="author" content="">

    	<title>Calendario - Home</title>

    	<!-- Bootstrap Core CSS -->
    	<link href="{{ asset('calendario/css/bootstrap.min.css') }}" rel="stylesheet">
	
		<!-- FullCalendar -->
		<link href="{{ asset('calendario/css/fullcalendar.css') }}" rel='stylesheet' />
		<link href="{{ asset('calendario/css/fullcalendar.print.min.css') }}" rel='stylesheet' media='print' />

    	<!-- Custom CSS Calendario -->
    	<link href="{{ asset('calendario/css/calendar.css') }}" rel='stylesheet' />

	</head>

	<body>

    
		<!-- Page Content -->
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<p class="lead"></p>
					<div id="calendar" class="col-centered">
					</div>
				</div>
			</div>
			<!-- /.row -->

			<!-- Valida data dos Modals -->
			<script type="text/javascript">
				function validaForm(erro) {
					if(erro.inicio.value>erro.termino.value){
						alert('Data de Inicio deve ser menor ou igual a de termino.');
						return false;
					}else if(erro.inicio.value==erro.termino.value){
						alert('Defina um horario de inicio e termino.(24h)');
						return false;
					}
				}
			</script>


			<!-- Modal Adicionar Evento -->
			@include('calendario.modalAdd')
			
			
			<!-- Modal Editar/Mostrar/Deletar Evento -->
			@include('calendario.modalEdit')

		</div>

		

		<!-- jQuery Version 1.11.1 -->
		<script src="{{ asset('calendario/js/jquery.js') }}"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="{{ asset('calendario/js/bootstrap.min.js') }}"></script>
		
		<!-- FullCalendar -->
		<script src="{{ asset('calendario/js/moment.min.js') }}"></script>
		<script src="{{ asset('calendario/js/fullcalendar.min.js') }}"></script>
		<script src="{{ asset('calendario/locale/pt-br.js') }}"></script>
		@include ('calendario.scriptsCalendario')
		

	</body>

</html>