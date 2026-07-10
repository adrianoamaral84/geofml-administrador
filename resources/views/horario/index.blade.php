@extends('layouts.app')

@section('content')
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
                                            <th width="20%" style="text-align: center;">Ação</th>

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
@push('javascript')
<script type="text/javascript">
     function deleteData(id)
     {
         var id = id;
         var url = '{{ route("temporada.delete", ":id") }}';
         url = url.replace(':id', id);
         $("#deletearea").attr('action', url);
     }

     function formSubmit()
     {
         $("#deletearea").submit();
     }

     
</script>
@endpush
@endsection