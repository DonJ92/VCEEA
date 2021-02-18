@extends('layouts.admin')

@section('title', trans('admin_restriction.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_restriction').addClass('active');
            getRestrictionList();
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getRestrictionList();
            }
        });

        function getRestrictionList() {
            var token = $("input[name=_token]").val();
            var name = $('#name').val();
            var batch_title = $('#batch_title').val();
            var batch_code = $('#batch_code').val();
            var description = $('#description').val();
            var status = $('#status').val();

            $.ajax({
                url: '{{ route('admin.restriction.getlist') }}',
                type: 'POST',
                data: {_token: token, name:name, batch_title: batch_title, batch_code:batch_code, description:description, status:status},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#restriction_list_tbl' ) ) {
                        var restriction_list_tbl = $('#restriction_list_tbl').DataTable();
                        restriction_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;
                            if (response[i].status == '{{ ACTIVE }}')
                                var status = '<span class="badge badge-light-success">' + '{{ trans('constant.status.active') }}' + '</span>';
                            else
                                var status = '<span class="badge badge-light-danger">' + '{{ trans('constant.status.inactive') }}' + '</span>';

                            var action = '<a class="text-primary" href="{{url('admin/restriction/edit')}}/' + response[i].id + '">{{ trans('button.edit') }}</a>&nbsp;&nbsp;&nbsp;<a class="text-danger" href="javascript:onDelete(' + response[i].id + ')">{{ trans('button.delete') }}</a>'

                            datas.push([
                                num,
                                response[i].name,
                                response[i].batch_title,
                                response[i].batch_code,
                                response[i].description,
                                status,
                                action
                            ]);
                        }
                    }

                    $('#restriction_list_tbl').dataTable({
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

                    $('#restriction_list_tbl').css('width', '');

                    console.log(response);
                }
            });
        }

        function onDelete(id) {
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

                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.restriction.delete') }}',
                        type: 'POST',
                        data: {_token: token, id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            getRestrictionList();
                        }
                    });
                }
            })
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_restriction.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_restriction.header_menu') }}</li>
                                    <li class="breadcrumb-item active">{{ trans('admin_restriction.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="restriction-search">
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
                                                            <label for="name">{{ trans('admin_restriction.name') }}</label>
                                                            <input type="text" class="form-control" id="name" placeholder="{{ trans('admin_restriction.name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="batch_title">{{ trans('admin_plan.batch_title') }}</label>
                                                            <fieldset class="form-group">
                                                                <select class="form-control" id="batch_title">
                                                                    <option value="">{{ trans('common.all') }}</option>
                                                                    @foreach($batch_list as $batch_info)
                                                                        <option value="{{ $batch_info['id'] }}"> {{ $batch_info['title'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="batch_code">{{ trans('admin_plan.batch_code') }}</label>
                                                            <fieldset class="form-group">
                                                                <select class="form-control" id="batch_code">
                                                                    <option value="">{{ trans('common.all') }}</option>
                                                                    @foreach($batch_list as $batch_info)
                                                                        <option value="{{ $batch_info['id'] }}"> {{ $batch_info['code'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="description">{{ trans('admin_restriction.description') }}</label>
                                                            <input type="text" class="form-control" id="description" placeholder="{{ trans('admin_restriction.description') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="status">{{ trans('admin_restriction.status') }}</label>
                                                            <select class="form-control" id="status">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                <option value="{{ ACTIVE }}">{{ trans('constant.status.active') }}</option>
                                                                <option value="{{ INACTIVE }}">{{ trans('constant.status.inactive') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getRestrictionList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                            <a class="btn btn-success mr-1 mb-1" href="{{ route('admin.restriction.add') }}">{{ trans('button.add') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="restriction_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('admin_restriction.no') }}</th>
                                                    <th>{{ trans('admin_restriction.name') }}</th>
                                                    <th>{{ trans('admin_restriction.batch_title') }}</th>
                                                    <th>{{ trans('admin_restriction.batch_code') }}</th>
                                                    <th>{{ trans('admin_restriction.description') }}</th>
                                                    <th>{{ trans('admin_restriction.status') }}</th>
                                                    <th>{{ trans('common.action') }}</th>
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