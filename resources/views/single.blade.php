<!DOCTYPE html>
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
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="/assets/DataTables/datatables.min.css">
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
                                            <input type='text' id='datetimepicker4' />
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
                                <!-- TESTE -->
                                <table id="table1" class="table table-bordered table-hover table-striped">

                                    <thead>
                                        <th class="col-md-1">level</th>
                                        <th class="col-md-1">user_id</th>
                                        <th class="col-md-1">user_email</th>
                                        <th class="col-md-1">accessed</th>
                                        <th class="col-md-1">message</th>
                                        <th class="col-md-1">request</th>
                                    </thead>
                                    <tbody>
                                        @if(count($contents))  
                                        @foreach($contents as $content)  
                                        <tr>
                                            <td class="col-md-1">{{$content['level']}}</td>
                                            <td class="col-md-1">{{$content['user_id']}}</td>
                                            <td class="col-md-2">{{$content['user_email']}}</td>
                                            <td class="col-md-2">{{$content['accessed']}}</td>
                                            <td class="col-md-3">{{$content['message']}}</td>
                                            <td class="col-md-3">{{$content['request']}}</td>
                                        </tr>
                                        @endforeach 
                                        @else
                                        <tr>
                                            <td colspan="8">No Records found !!</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <th class="col-md-1">level</th>
                                        <th class="col-md-1">user_id</th>
                                        <th class="col-md-1">user_email</th>
                                        <th class="col-md-1">accessed</th>
                                        <th class="col-md-1">message</th>
                                        <th class="col-md-1">request</th>
                                    </tfoot>
                                </table>
                            </div>
                            <pre>
                        </pre>
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
    <!-- Bootstrap Datetimepicker -->
    <script type="text/javascript" src="/assets/datetimepicker/bootstrap-datetimepicker.js"></script>
    <!-- DataTable -->
    <script src="/assets/DataTables/datatables.min.js"></script>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            /* Custom filtering function which will search data in column four between two values */
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var busca = $('#datetimepicker4').val();
                    var buscaFix = data[3];
                
                    var log = $('#tipo-log').val();
                    var logFix = data[0];
                    if ((busca == buscaFix || busca == '') && 
                        (log == logFix || log == '')){
                        return true;
                    }
                    return false;
                }
            );

            // DataTable
            var table = $('#table1').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                "scrollX"     : true,
                "order": [[ 0, "desc" ]]
            });

            // Event listener to the two range filtering inputs to redraw on input
            $('#datetimepicker4').blur( function() {
                table.draw();
            });

            $('#tipo-log').on('change', function() {
                table.draw();
            });

            $('#datetimepicker4').datetimepicker({
                locale: 'pt-br',
                format:'DD/MM/YYYY HH:mm:ss'
            });
        })
    </script>
</body>
</html>
