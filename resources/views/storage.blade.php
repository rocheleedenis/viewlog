@extends('layouts.app')

@section('styles')
@endsection 

@section('content')   
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center"><h3>{{ $modo }}</h3></div>
                <div class="table-container">
                        <div class="col-sm-6">
                            Data: 
                            <input name="data-accessed" type="text" id="datetimepicker" />
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
@endsection 

@section('scripts')
    <script>
        // Inicia Datatable
        $(document).ready(function() {
            var oTable = $('#users-table').DataTable({
                "scrollX" : true,
                processing: true,
                serverSide: true,
                ajax: {
                    url : '{{ route('getdata') }}',
                    data: function (d) {
                        d.tipo     = $("select[name='tipo-log']").val();
                        d.accessed = $("input[name='data-accessed']").val();
                    }
                },
                columns: [
                    { data: 'level',      name: 'level' },
                    { data: 'accessed',   name: 'accessed' },
                    { data: 'user_id',    name: 'user_id' },
                    { data: 'user_email', name: 'user_email' },
                    { data: 'message',    name: 'message' },
                    { data: 'request',    name: 'request' }
                ]
            });

            // Atualiza tabela ao selecionar tipo de log
            $('#tipo-log').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            // Atualiza tabela ao selecionar data
            $('#datetimepicker').on('blur', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            // Inicializa DateTimePicker
            $('#datetimepicker').datetimepicker({
                locale: 'pt-br',
                format: 'DD/MM/YYYY HH:mm:ss'
            });
        });
    </script> 
@endsection