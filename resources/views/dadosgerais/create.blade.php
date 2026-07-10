@extends('layouts.app')

@section('content')
<style>
 .ck-editor__editable_inline{
    min-height: 200px;
 }   
</style>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Cadastra Dados Gerais </h3>
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
                                <form id="password-form" action="{{ route('dadosgerais.store') }}" method="POST">
                                    @csrf

                                          <div class="row form-group has-error">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <label class="control-label">Cabeçalho</label>
                                            <textarea class="ckeditor form-control" name="cabecalho" id="cabecalho" rows="10"></textarea>
                                           


                                            @error('cabecalho')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>


                                <div class="row form-group has-error">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                        <label class="control-label">Nome Seção</label>
                                            <input type="text" name="nome_secao" id="nome_secao" required="" class="form-control boxed" value="{{ old('nome_secao') }}" maxlength="100" minlength="2" onpaste="return false;" autofocus>


                                            @error('nome_secao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>


                                    <div class="row form-group has-error">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <label class="control-label">Assinatura</label>
                                            <input type="text" name="assinatura" id="assinatura" required="" class="form-control boxed" value="{{ old('assinatura') }}" maxlength="100" minlength="2" onpaste="return false;" autofocus>


                                            @error('assinatura')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>

                         


                                    <div class="row form-group has-error">

                                        


                                    </div>
                                 
                                   
                                   
                                    <hr>


                                    <div class="form-group row">
                                        <div class="col-sm-8 col-md-8 col-lg-8">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle btn-sm fa-sm"></i>  Cadastrar </button>
                                        </div>
                                    </div>
                                </form>
                               
                            </div>
                           
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

@push('javascript')
<script src="{{ asset('ckeditor5/build/ckeditor.js') }}" ></script>
<script src="{{ asset('ckfinder/ckfinder.js') }}"></script>
 <script>ClassicEditor
            .create( document.querySelector( '.ckeditor' ), {
        
                toolbar: {
                    items: [
                        'bold',
                        'italic',
                        '|',
                        'link',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'indent',
                        'outdent',
                        '|',
                        
                        'blockQuote',
                        '|',
                        'undo',
                        'redo',
                        '|',
                        'removeFormat',
                        'fontFamily',
                        'alignment',
                        '|',
                        'fontSize',
                        'CKFinder',
                        'exportPdf'
                    ]
                },
                language: 'pt-br',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',
                
            } )
            .then( editor => {
                window.editor = editor;
        
                
      
                
            } )
            .catch( error => {
                console.error( 'Oops, something went wrong!' );
                console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                console.warn( 'Build id: rjw3ovjid1ph-4tfjamdaf132' );
                console.error( error );
            } );

    </script>


@endpush
@endsection