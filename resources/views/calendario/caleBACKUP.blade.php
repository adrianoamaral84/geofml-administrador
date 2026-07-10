@extends('layouts.calendario_sem_menu')

@section('content')
			<div class="row">
				<div class="col-lg-12 text-center">
					<p class="lead"></p>
					<div id="calendars" class="col-centered">
					</div>
				</div>
			</div>

			 <div class="row has-error">

                    <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                
                        <label class="control-label">{{ __('Unidades Habitacionais') }}</label>
                        
                            <select name="unidadeshabitacionais" id="unidadeshabitacionais" required class="custom-select mr-sm-2 @error('unidadeshabitacionais') is-invalid @enderror" autocomplete="off">
                            
                            <option value="">Selecione Unidade Habitacional</option>
                            @foreach($unidades_habitacionais as $unidades_habitacional)

                        		<option value="{{$unidades_habitacional->id}}">{{$unidades_habitacional->sigla}} - {{ $unidades_habitacional->classe->descricao}} - {{ $unidades_habitacional->tipohabitacao->descricao}} @if($unidades_habitacional->pet == 1) - Pet SIM @endif
                        		</option>
                        	
                        	@endforeach
                                       
                            </select>
                                                        
                                    @error('unidadeshabitacionais')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

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

		@endsection 