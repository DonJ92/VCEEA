@extends('layouts.admin')

@section('title', trans('admin_log.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_log').addClass('active');
            getLogList();
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getLogList();
            }
        });

        function getLogList() {
            var token = $("input[name=_token]").val();
            var user_code = $('#user_code').val();
            var log = $('#log').val();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();

            $.ajax({
                url: '{{ route('admin.log.getlist') }}',
                type: 'POST',
                data: {_token: token, user_code:user_code, log: log, date_from:date_from, date_to:date_to},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#log_list_tbl' ) ) {
                        var log_list_tbl = $('#log_list_tbl').DataTable();
                        log_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;

                            if (response[i].gender == '{{ FEMALE }}')
                                var gender = '{{ trans('constant.female') }}';
                            else
                                var gender = '{{ trans('constant.male') }}';

                            datas.push([
                                num,
                                response[i].user_code,
                                response[i].log_type,
                                response[i].created_at,
                            ]);
                        }
                    }

                    $('#log_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        "order": [[ 0, "asc" ]],
                        "bLengthChange": false,
                        "bFilter": false,
                        "info": false,
                        "autoWidth": true,
                        "language": {
                            "emptyTable": "{{ trans('common.no_data') }}",
                            "paginate": {
                                "previous": "{{ trans('common.previous') }}",
                                "next": "{{ trans('common.next') }}"
                            }
                        }
                    });

                    $('#log_list_tbl').css('width', '');

                    console.log(response);
                }
            });
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_log.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item active">{{ trans('admin_log.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="log-search">
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
                                        <form action="#" class="wizard-horizontal">
                                        @csrf
                                        <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="user_code">{{ trans('admin_log.user_code') }}</label>
                                                            <input type="text" class="form-control" id="user_code" placeholder="{{ trans('admin_log.user_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="log_type">{{ trans('admin_log.log') }}</label>
                                                            <select class="form-control" id="log_type">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $type_list as $type_info)
                                                                    <option value="{{ $type_info['id'] }}" @if ($type_info['id'] == old('type')) selected @endif>{{ $type_info['type'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="form-group">
                                                            <label for="date">{{ trans('admin_log.date') }}</label>
                                                            <div class="col-lg-12 row">
                                                                <input type="datetime-local" class="form-control col-sm-5" id="date_from" value="{{ $from_date }}">
                                                                <span class="form-control-plaintext col-lg-1 text-center">~</span>
                                                                <input type="datetime-local" class="form-control col-sm-5" id="date_to" value="{{ $to_date }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getLogList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="log_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('admin_log.no') }}</th>
                                                    <th>{{ trans('admin_log.user_code') }}</th>
                                                    <th>{{ trans('admin_log.log') }}</th>
                                                    <th>{{ trans('admin_log.date') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                </tbody>
                                            </table>
                                        </div>
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