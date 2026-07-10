@extends('layouts.app')
@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Lista de Documentos Vencidos </h3> 
                </div>
            </div>
        </div>
        <div class="items-search">
           
        <form method="GET" action="{{ route('documentos.vencidos') }}" class="form-inline mb-3">
    <div class="input-group input-group-sm">
        <input type="text" name="search" class="form-control" placeholder="Nome ou CPF..." value="{{ request('search') }}">
        <div class="input-group-append">
            <button type="submit" class="btn btn-danger btn-sm">Buscar Vencidos</button>
        </div>
        @if(request('search'))
            <div class="input-group-append ml-2">
                <a href="{{ route('documentos.vencidos') }}" class="btn btn-secondary btn-sm">Limpar</a>
            </div>
        @endif
    </div>
</form>
           
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
                                           
                                            <th width="60%">Nome</th>
                                            
                                            <th width="15%" style="text-align: center;">CPF</th>
                                            
                                            <th width="15%" style="text-align: center;">Data</th>
                                            <th width="10%" style="text-align: center;">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @if (count($consulta) == 0)
                                            <tr>
                                                <td colspan="3" style="text-align: center;"><small>Nenhum registro encontrado</small></td>
                                            </tr>
                                            @else
                                            @foreach ($consulta as $item) 
                                            <tr>
                                                
                                                <td> 

                                               @role('administrador_geral|auxiliar_administrador_geral')
                                                <a href="{{route('usuario.verdados', ['id' => \Crypt::encrypt($item->id) ])}}"> {{ $item->name }} </a> 
                                                @else
                                                    {{ $item->name }}
                                                @endrole
                                                                                                     
                                                </td>
                                                <td style="text-align: center;"> {{substr($item->cpf, 0, 3) . '.' . substr($item->cpf, 3, 3) . '.' . substr($item->cpf, 6, 3) . '-' . substr($item->cpf, 9)}}</td>
                                                <td style="text-align: center;">
                                                   
                                                    
                                                        {{ $item->validade }} 
               

                                                                                                   
                                                </td>
                                                <td style="text-align: center;">
                                                     <a href="{{route('envia.enviaMailDataDocumentoVencido', ['id' => Crypt::encrypt($item->id)])}}" data-toggle="tooltip" data-placement="top" title="Enviar {{ $item->name }}"> <i class="far fa-envelope" style="color: blue;"> </i> </a>
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
                                                    <p>Tem certeza que deseja deletar esta UF ?</p>
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
@push('javascript')
<script type="text/javascript">
     function deleteData(id)
     {
         var id = id;
         var url = '{{ route("deleteuf", ":id") }}';
         url = url.replace(':id', id);
         $("#deletearea").attr('action', url);
     }

     function formSubmit()
     {
         $("#deletearea").submit();
     }

     $(document).ready(function() {
    var table = $('#tabela').DataTable({
    
    searching:false,
    lengthChange: false,
    lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 registros', '25 registros', '50 registros', 'Mostrar todos' ]
        ],
   buttons: [

            {
                extend: 'excelHtml5',              
                title: 'GEOFML - Documentos Vencidos'
            },
            {
                extend: 'pdfHtml5',
                title: 'GEOFML - Documentos Vencidos'
            },
            'print','pageLength',
    ],
    select: true,
    "processing": true,
    "order": [[ 1, "asc" ]],
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