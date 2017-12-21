@extends('layouts.app')

@section('styles')
@endsection 

@section('page-title', 'Cadastro de Produto') 

@section('content')
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

@endsection 

@section('scripts') 
    <script>
        $(document).ready( function() {
            /* Custom filtering function which will search data in column four between two values */
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var busca    = $('#datetimepicker4').val();
                    var buscaFix = data[3];
                    var log      = $('#tipo-log').val();
                    var logFix   = data[0];

                    if ((busca == buscaFix || busca == '') && (log == logFix || log == '')){
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
                "order"       : [[ 0, "desc" ]]
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
                format: 'DD/MM/YYYY HH:mm:ss'
            });
        });
    </script>
@endsection
