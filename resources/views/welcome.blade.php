@extends('layouts.app')

@section('styles')
    <link href="/vendor/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection 

@section('page-title', 'Cadastro de Produto') 

@section('content')
    <div class="title m-b-md">
        ViewLog
    </div>

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <h3 class="links">
                <a href="{{ asset('storage')}}">Ver arquivos da pasta</a>
            </h3>
            <hr>
            <h3 class="text-content">Ler apenas um arquivo</h3>
            <form action="/single" enctype="multipart/form-data" method="post">
                {{csrf_field()}}
                <div class="file-loading">
                    <input class='file' name="file" type="file" multiple value="">
                </div>
                <br>
                <input class="btn btn-primary" type="submit" value="Enviar">
            </form>
        </div>
    </div>
@endsection 

@section('scripts') 
    <script src="/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/vendor/bootstrap-fileinput/js/locales/pt-BR.js"></script>
    
    <script>
        $(function () {
            $('.file').fileinput({
                showUpload           : false,
                language             : 'pt-BR',
                allowedFileExtensions: [ 'jpg', 'png' ]
            });
        });
    </script>
@endsection