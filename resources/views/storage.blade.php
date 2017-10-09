<!DOCTYPE html>
<html lang="en">
<head>
    <html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ViewLog</title>

    <!-- Bootstrap -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        ViewLog
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">&nbsp;</ul>
                </div>
            </div>
        </nav>
	        
	    <div class="row">
            <section class="content">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center"><h3>{{ $modo }}</h3></div>
                            <div class="table-container">
                                
                                    <div class='col-sm-6'>
                                        Data: 
                                        <input name="data-accessed" type='text' id='datetimepicker4' />
                                    </div>
                                    <div class="col-md-6">
                                        <p class="pull-right">
                                            Tipo de log:
                                            <select name="tipo-log" id="tipo-log">
                                                <option value="" selected="selected">--</option>
                                                <option value="DEBUG">DEBUG</option>
                                                <option value="INFO">INFO</option>
                                                <option value="NOTICE">NOTICE</option>
                                                <option value="WARNING">WARNING</option>
                                                <option value="ALERT">ALERT</option>
                                                <option value="ERROR">ERROR</option>
                                                <option value="CRITICAL">CRITICAL</option>
                                                <option value="EMERGENCY">EMERGENCY</option>
                                            </select>
                                        </p>
                                    </div>
                                    
                                
						        <table class="table table-bordered" id="users-table">
						            <thead>
						                <tr>
											<th>level</th>
											<th>accessed</th>
											<th>user_id</th>
											<th>user_email</th>
											<th>message</th>
											<th>request</th>
						                </tr>
						            </thead>
						        </table>
    						</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- jQuery 2 -->
	<script src="/assets/jquery/jquery-2.2.4.min.js"></script>
	<!-- Moment -->
	<script type="text/javascript" src="/assets/moment/moment-with-locales.min.js"></script>
	<!-- Bootstrap -->
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script type="text/javascript" src="/assets/datetimepicker/bootstrap-datetimepicker.js"></script>

    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        // Inicia Datatable
        $(document).ready(function() {
            var oTable = $('#users-table').DataTable({
                "scrollX"     : true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('getdata') }}',
                    data: function (d) {
                        d.tipo = $("select[name='tipo-log']").val();
                        d.accessed = $("input[name='data-accessed']").val();
                    }
                },
                columns: [
                    { data: 'level', name: 'level' },
                    { data: 'accessed', name: 'accessed' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'user_email', name: 'user_email' },
                    { data: 'message', name: 'message' },
                    { data: 'request', name: 'request' }
                ]
            });

            // Atualiza tabela ao selecionar tipo de log
            $('#tipo-log').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            // Atualiza tabela ao selecionar data
            $('#datetimepicker4').on('blur', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            // Inicializa DateTimePicker
            $('#datetimepicker4').datetimepicker({
                locale: 'pt-br',
                format:'DD/MM/YYYY HH:mm:ss',
            });
        });
    </script>
    @stack('scripts')
</body>
</html>