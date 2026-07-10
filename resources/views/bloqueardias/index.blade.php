@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title">Configurar Hospedagem</h3> 
                    <small>Página onde tem as configurações da área de hospedagem! </small>
                </div>
            </div>
        </div>
        <br>
    </div>
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Bloquear Dias <a href="{{ route('configurarhospedagem.create')}}" class="btn btn-primary btn-sm rounded-s"> Add </a></h3> 
                    <small>Cadastra os períodos a serem bloqueados pelo sistema, para que o usuário não consiga realizar a inscrição! </small>
                </div>
            </div>
        </div>
        <div class="items-search">
           
        </div>
    </div>
    <section class="section">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">

                            <div class="table-flip-scroll">

                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                          
                                            <th width="45%">Motivo</th>
                                            <th width="20%">Data Início</th>
                                            <th width="20%">Data Final</th>       
                                            <th width="15%" style="text-align: center;">Ação</th>

                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @if ($consulta->count() == 0)
                                            <tr>
                                                <td colspan="4" style="text-align: center;"><small>Nenhum registro encontrado</small></td>
                                            </tr>
                                            @else
                                             @foreach ($consulta as $item) 
                                            <tr>
                                                <td>
                                                    

                                                    {{ $item->motivo }}


                                                    
                                                    </td>
                                                <td>
                                                    {{\Carbon\Carbon::parse($item->data_inicio)->format('Y-m-d')}}
                                                </td>
                                        <td>
                                           

                                                @if($item->data_fim)

                                                    {{\Carbon\Carbon::parse($item->data_fim)->format('Y-m-d')}}
                                            
                                                @endif

                                            

                                            
                                        </td>
                                                <td style="text-align: center;">
                                                    <h6>
                                                    


                <a href="{{ route('configurarhospedagem.edit', ['id' => Crypt::encrypt($item->id)]) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-edit" style="color: #ffffff"></i></a>

                    
                                                 
    <a href="javascript:;" data-toggle="modal" onclick="deleteData('{{ Crypt::encrypt($item->id) }}')" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Deletar">
                    <i class="fas fa-trash fa-sm" ></i></a>

            

                                                       
                                                    </h6>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                    </tbody>
                                </table>
                                <div class="modal fade" id="DeleteModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="deletearea" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p>Tem certeza que deseja deletar ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmit()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                               {{$consulta->links()}}
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                   
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr>

     <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">

                        <section class="example">

                            <div class="table-flip-scroll">
                                 <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Dia limite para inscrição no mês </h3> 
                    <small>Cadastra o dia em que fecha as inscrições no mês, para que o usuário não consiga realizar a inscrição! </small>
                </div>
            </div>
            <hr>
                                <table class="table table-striped table-bordered table-hover flip-content">
                                    <thead class="flip-header">
                                        <tr>
                                          
                                            <th width="85%">Dia</th>
                                                
                                            <th width="15%" style="text-align: center;">Ação</th>

                                        </tr>
                                    </thead>
                                    <tbody>                                                                                                                       
                                            <tr>
                                                <td>                                                    
                                                    {{ $diaBloqueado->dia }}                                                
                                                    </td>
                                                
                                        
                                                <td style="text-align: center;">
                                                    <h6>
                                                    
    <a href="{{ route('configurarhospedagem.create.dia', ['id' => Crypt::encrypt($diaBloqueado->id)]) }}" title="Editar Dia Bloqueado" class="btn btn-info btn-sm">
                    <i class="fas fa-edit" style="color: #ffffff"></i></a>
                    
     <!--                                            
    <a href="javascript:;" data-toggle="modal" onclick="deleteData('{{ Crypt::encrypt($diaBloqueado->id) }}')" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Deletar">
    <i class="fas fa-trash fa-sm" ></i></a>
    -->                                   
                                                    </h6>
                                                </td>
                                            </tr>
                                           
                                    </tbody>
                                </table>
                                <div class="modal fade" id="DeleteModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="deletearea" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p>Tem certeza que deseja deletar ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmit()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                               {{$consulta->links()}}
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                   
                                </div>
                            </div>
                        </section>
                    </div>
                </div>





















                
            </div>
        </div>
    </section>
</article>
<br><br>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Horário de Entrada e Saída  </h3> 
                    <small>Cadastra o horário de entrada e saída do hospede no seu quarto!</small>
                </div>
            </div>
        </div>
        <div class="items-search">
           
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <table class="table table-striped table-bordered table-hover flip-content">
                                    <thead class="flip-header">
                                        <tr>
                                          
                                            <th width="40%">Horário Entrada ( check-in )</th>
                                            <th width="40%">Horário Saída ( check-out )</th>                                                
                                            <th width="15%" style="text-align: center;">Ação</th>

                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @if ($consulta1->count() == 0)
                                            <tr>
                                                <td colspan="4" style="text-align: center;"><small>Nenhum registro encontrado</small></td>
                                            </tr>
                                            @else
                                             @foreach ($consulta1 as $item) 
                                            <tr>
                                                <td>                                  
                                                    {{\Carbon\Carbon::parse($item->entrada)->format('H:i')}}                                             
                                                </td>
                                                <td>{{\Carbon\Carbon::parse($item->saida)->format('H:i')}}</td>
                                                <td style="text-align: center;">                              

                <a href="{{ route('horario.edit', ['id' => Crypt::encrypt($item->id)]) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-edit" style="color: #ffffff"></i></a>           
                                                    
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                    </tbody>
                                </table>
                                <div class="modal fade" id="DeleteModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="deletearea" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p>Tem certeza que deseja deletar ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmit()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                   
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
<br><br>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Temporadas </h3> 
                    <small>Edita os períodos da alta e baixa temporada</small>
                </div>
            </div>
        </div>
        <div class="items-search">
           
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <table class="table table-striped table-bordered table-hover flip-content">
                                    <thead class="flip-header">
                                        <tr>
                                          
                                            <th width="30%">Temporada</th>
                                            <th width="">Data Início</th>
                                            <th width="">Data Final</th>       
                                            <th width="15%" style="text-align: center;">Ação</th>

                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @if ($temporadas->count() == 0)
                                            <tr>
                                                <td colspan="4" style="text-align: center;"><small>Nenhum registro encontrado</small></td>
                                            </tr>
                                            @else
                                             @foreach ($temporadas as $item) 
                                            <tr>
                                                <td>
                                                    

                                                    {{ $item->tipotemporadas->tipo_temporada }}


                                                    
                                                    </td>
                                                <td>{{\Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y')}}</td>
                                        <td>{{\Carbon\Carbon::parse($item->data_termino)->format('d/m/Y')}}</td>
                                                <td style="text-align: center;">
                                                    <h6>
                                                    

                <a href="{{ route('temporada.edit', ['id' => Crypt::encrypt($item->id)]) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-edit" style="color: #ffffff"></i></a>

                    
        <!--                                           
    <a href="javascript:;" data-toggle="modal" onclick="deleteData('{{ Crypt::encrypt($item->id) }}')" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Deletar">
                    <i class="fas fa-trash fa-sm" ></i></a>

                -->

                                                       
                                                    </h6>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                    </tbody>
                                </table>
                                <div class="modal fade" id="DeleteModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="deletearea" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p>Tem certeza que deseja deletar ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmit()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                               {{$consulta->links()}}
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                   
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>





@push('javascript')
<script type="text/javascript">
     function deleteData(id)
     {
         var id = id;
         var url = '{{ route("configurarhospedagem.destroy", ":id") }}';
         url = url.replace(':id', id);
         $("#deletearea").attr('action', url);
     }

     function formSubmit()
     {
         $("#deletearea").submit();
     }

     $(document).ready(function() {
    var table = $('#tabela').DataTable({
    
    lengthChange: false,
    lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 registros', '25 registros', '50 registros', 'Mostrar todos' ]
        ],
   buttons: [

            {
                extend: 'excelHtml5',              
                title: 'GEOFML - Relatório Arrecadacao Mensal',
                footer: true,
            },
            {
                extend: 'pdfHtml5',
                title: 'GEOFML - Relatório Arrecadacao Mensal',
                footer: true,
            },
            
            'print','pageLength',

    ],
    select: true,
    "processing": true,
    "order": [[ 0, "asc" ]],
    stateSave: true,
    language: {          
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
    },
    "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    },
    "select": {
        "rows": {
            "_": " Selecionado %d linhas",
            "0": " Nenhuma linha selecionada",
            "1": " Selecionado 1 linha"
        }
    },
    "buttons": {
        "copy": "Copiar para a área de transferência",
        "copyTitle": "Cópia bem sucedida",
        "copySuccess": {
            "1": "Uma linha copiada com sucesso",
            "_": "%d linhas copiadas com sucesso"
        }
    }


    }
    });
    table.buttons().container().appendTo( '#tabela_wrapper .col-md-6:eq(0)' );
    } );
</script>
    <script src="{{ asset('js/DataTables/datatables.min.js') }}" ></script>  
    <script src="{{ asset('js/DataTables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js') }}" ></script>  
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.select.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.bootstrap4.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.buttons.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.colVis.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.html5.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Select-1.3.1/js/select.bootstrap4.min.js') }}" ></script>  
@endpush
@endsection