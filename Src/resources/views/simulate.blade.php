@extends('layouts.app')

@section('title', trans('simulate.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_simulate').addClass('active');
            getSimulateList()
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getSimulateList();
            }
        });

        function getSimulateList() {
            var token = $("input[name=_token]").val();
            var simulate_id = $('#simulate_id').val();

            $.ajax({
                url: '{{ route('simulate.getlist') }}',
                type: 'POST',
                data: {_token: token, simulate_id: simulate_id},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#simulate_list_tbl' ) ) {
                        var simulate_list_tbl = $('#simulate_list_tbl').DataTable();
                        simulate_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;

                            if (response[i].college_url == null || response[i].college_url == "")
                                var college_name = response[i].college_name;
                            else
                                var college_name = '<a target="_blank" href="'+response[i].college_url+'">'+response[i].college_name+'</a>';

                            if (i == 0)
                                var moveup = '<a>{{ trans('button.moveup') }}</a>';
                            else
                                var moveup = '<a class="text-primary cursor-pointer" onclick="onMoveUp(' + response[i].simulate_detail_id + ')">{{ trans('button.moveup') }}</a>';

                            if (i == (response.length - 1))
                                var movedown = '<a>{{ trans('button.movedown') }}</a>';
                            else
                                var movedown = '<a class="text-primary cursor-pointer" onclick="onMoveDown(' + response[i].simulate_detail_id + ')">{{ trans('button.movedown') }}</a>';

                            var del = '<a class="text-danger cursor-pointer" onclick="onDelete(' + response[i].simulate_detail_id + ')">{{ trans('button.delete') }}</a>';

                            var action = moveup + '&nbsp;&nbsp;&nbsp;' + movedown + '&nbsp;&nbsp;&nbsp;' + del;

                            datas.push([
                                num,
                                response[i].batch_title,
                                response[i].batch_code,
                                college_name,
                                response[i].college_code,
                                response[i].department_name,
                                response[i].major_name,
                                response[i].major_code,
                                action
                            ]);
                        }
                    }

                    $('#simulate_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        "ordering": false,
                        "bLengthChange": false,
                        "bFilter": false,
                        "info": false,
                        "language": {
                            "emptyTable": "{{ trans('common.no_data') }}",
                            "paginate": {
                                "previous": "{{ trans('common.previous') }}",
                                "next": "{{ trans('common.next') }}"
                            }
                        }
                    });

                    $('#simulate_list_tbl').css('width', '');
                }
            });
        }

        function onMoveUp(simulate_detail_id) {
            var token = $("input[name=_token]").val();

            $.ajax({
                url: '{{ route('simulate.moveup') }}',
                type: 'POST',
                data: {_token: token, simulate_detail_id: simulate_detail_id},
                dataType: 'JSON',
                success: function (response) {
                    if (response == undefined || response.length == 0 || response == false) {
                    } else {
                        getSimulateList();
                    }
                }
            });
        }

        function onMoveDown(simulate_detail_id) {
            var token = $("input[name=_token]").val();

            $.ajax({
                url: '{{ route('simulate.movedown') }}',
                type: 'POST',
                data: {_token: token, simulate_detail_id: simulate_detail_id},
                dataType: 'JSON',
                success: function (response) {
                    if (response == undefined || response.length == 0 || response == false) {
                    } else {
                        getSimulateList();
                    }
                }
            });
        }

        function onDelete(simulate_detail_id) {
            Swal.fire({
                title: '{{ trans('common.delete_confirm') }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans('button.yes') }}',
                cancelButtonText: '{{ trans('button.no') }}',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                var token = $("input[name=_token]").val();

                $.ajax({
                    url: '{{ route('simulate.delete') }}',
                    type: 'POST',
                    data: {_token: token, simulate_detail_id: simulate_detail_id},
                    dataType: 'JSON',
                    success: function (response) {
                        if (response == undefined || response.length == 0 || response == false) {
                        } else {
                            getSimulateList();
                        }
                    }
                });
            })
        }

        function onSimulateDelete() {
            Swal.fire({
                title: '{{ trans('common.delete_confirm') }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans('button.yes') }}',
                cancelButtonText: '{{ trans('button.no') }}',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                var token = $("input[name=_token]").val();
                var simulate_id = $('#simulate_id').val();

                $('#simulate_form').attr('action', '{{ route('simulate.deletesimulate') }}');
                $('#simulate_form').submit();
            })
        }

        function onExport() {
            $('#simulate_form').attr('action', '{{ route('simulate.export') }}');
            $('#simulate_form').submit();
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('simulate.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item active">{{ trans('simulate.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="plan-tabs">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content mt-2">
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert bg-success bg-light" id="success_div">
                                                <strong>{{ session('success') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('failed'))
                                            <div class="alert bg-danger bg-light" id="failed_div">
                                                <strong>{{ $errors->first('failed') }}</strong>
                                            </div>
                                        @endif
                                        <form method="post" action="" class="wizard-horizontal" id="simulate_form">
                                        @csrf
                                        <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="simulate_id">{{ trans('simulate.name') }}</label>
                                                            <select class="form-control" id="simulate_id" name="id" onchange="getSimulateList()">
                                                                @foreach($simulate_list as $simulate_info)
                                                                    <option value="{{ $simulate_info['id'] }}"> {{ $simulate_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 text-center">
                                                        <label></label>
                                                        <div>
                                                            <button type="button" class="btn btn-success mr-1 mb-1" onclick="onExport()">{{ trans('button.export') }}</button>
                                                            <button type="button" class="btn btn-danger mr-1 mb-1" onclick="onSimulateDelete()">{{ trans('button.delete_simulate') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                            <fieldset>
                                                <div class="table-responsive">
                                                    <table class="table" id="simulate_list_tbl">
                                                        <thead class="text-center">
                                                        <tr>
                                                            <th>{{ trans('simulate.no') }}</th>
                                                            <th>{{ trans('simulate.batch_title') }}</th>
                                                            <th>{{ trans('simulate.batch_code') }}</th>
                                                            <th>{{ trans('simulate.college_name') }}</th>
                                                            <th>{{ trans('simulate.college_code') }}</th>
                                                            <th>{{ trans('simulate.department_name') }}</th>
                                                            <th>{{ trans('simulate.major_name') }}</th>
                                                            <th>{{ trans('simulate.major_code') }}</th>
                                                            <th>{{ trans('common.action') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-center">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection