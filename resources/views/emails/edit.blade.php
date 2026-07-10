@extends('layouts.app')

@section('content')
<style type="text/css">
    






textarea#mentions {
  height: 350px;
}

div.card,
.tox div.card {
  
  background: white;
  border: 1px solid #ccc;
  border-radius: 3px;
  box-shadow: 0 4px 8px 0 rgba(34, 47, 62, .1);
  padding: 8px;
  font-size: 14px;
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
}

div.card::after,
.tox div.card::after {
  content: "";
  clear: both;
  display: table;
}

div.card h1,
.tox div.card h1 {
  font-size: 14px;
  font-weight: bold;
  margin: 0 0 8px;
  padding: 0;
  line-height: normal;
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
}

div.card p,
.tox div.card p {
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
}

div.card img.avatar,
.tox div.card img.avatar {
  width: 48px;
  height: 48px;
  margin-right: 8px;
  float: left;
}

</style>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar E-mail </h3>
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
                                <form id="password-form" action="{{ route('email.atualizar') }}" method="POST">
                                    @csrf

                                    <div class="row form-group has-error">

                                        <div class="col-12">
                                        <label class="control-label">{{ __('Assunto') }}</label>
                                        <input type="hidden" name="id" value="{{ $consulta->id }}">
                                        <input type="text" name="assunto" id="assunto" class="form-control boxed" value="{{ $consulta->assunto }}" maxlength="255" minlength="2" onpaste="return false;" autofocus>
                                        @error('assunto')
                                        <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        </div>
                                    
                                    </div>

                                    <div class="row form-group has-error">

                                        <div class="col-12">
                                        <label class="control-label">{{ __('Cabeçalho') }}</label>
                                        <input type="text" name="cabecalho" id="cabecalho" class="form-control boxed" value="{{ $consulta->cabecalho }}" maxlength="255" minlength="2" autofocus>
                                        @error('cabecalho')
                                        <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="row form-group has-error">
                                        <div class="col-12">
                                            <label class="control-label">Mensagem</label>
                                            <textarea id="corpo" name="corpo" class="form-control" rows="20" cols="30" maxlength="300">{!! html_entity_decode($consulta->corpo) !!}
                                            </textarea>
                                            @error('corpo')
                                            <span class="has-error" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror 
                                    </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary rounded-s"><i class="fas fa-check-circle fa-sm"></i>  Atualizar </button>
                                             <a href="{{ route('email.index') }}" class="btn btn-danger rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
                                        </div>
                                    </div>
                                </form>
                               
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
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script src="https://cdn.tiny.cloud/1/p87ejaduf0ywgb3wkf6hdz49f4l1heozqq28nhpibwa88bk2/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script><script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
@endpush
@endsection