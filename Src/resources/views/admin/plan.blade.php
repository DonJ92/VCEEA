@extends('layouts.admin')

@section('title', trans('admin_plan.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_plan').addClass('active');
            getPlanList()
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getPlanList();
            }
        });

        function getPlanList() {
            var token = $("input[name=_token]").val();
            var batch_title = $('#batch_title').val();
            var batch_code = $('#batch_code').val();
            var college_name = $('#college_name').val();
            var college_code = $('#college_code').val();
            var department_name = $('#department_name').val();
            var department_code = $('#department_code').val();
            var major_name = $('#major_name').val();
            var major_code = $('#major_code').val();
            var subject_id = $('#subject').val();
            var college_type = $('#college_type').val();
            var college_property = $('#college_property').val();
            var academic_year = $('#academic_year').val();
            var cost_from = $('#cost_from').val();
            var cost_to = $('#cost_to').val();
            var recruitment_num = $('#recruitment_num').val();
            var recruitment_num_compare = $('#recruitment_num_compare').val();

            $.ajax({
                url: '{{ route('admin.plan.getlist') }}',
                type: 'POST',
                data: {_token: token, batch_title: batch_title, batch_code:batch_code, college_name:college_name, college_code:college_code, college_type:college_type, college_property: college_property,
                    department_name:department_name, department_code:department_code, major_name:major_name, major_code:major_code, subject_id:subject_id,
                    academic_year:academic_year, cost_from:cost_from, cost_to:cost_to, recruitment_num:recruitment_num, recruitment_num_compare:recruitment_num_compare,
                },
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#plan_list_tbl' ) ) {
                        var plan_list_tbl = $('#plan_list_tbl').DataTable();
                        plan_list_tbl.destroy();
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

                            var action = '<a class="text-primary" href="{{url('admin/plan/edit')}}/' + response[i].id + '">{{ trans('button.edit') }}</a>&nbsp;&nbsp;&nbsp;<a class="text-danger" href="javascript:onDelete(' + response[i].id + ')">{{ trans('button.delete') }}</a>'

                            datas.push([
                                num,
                                response[i].batch_title,
                                response[i].batch_code,
                                response[i].college_name,
                                response[i].college_code,
                                response[i].college_type,
                                response[i].college_property,
                                response[i].department_name,
                                response[i].department_code,
                                response[i].major_name,
                                response[i].major_code,
                                response[i].subject_name,
                                response[i].academic_year_code,
                                response[i].cost,
                                response[i].recruitment_num,
                                status,
                                action
                            ]);
                        }
                    }

                    $('#plan_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        "order": [[ 0, "asc" ]],
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

                    $('#plan_list_tbl').css('width', '');
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
                        url: '{{ route('admin.plan.delete') }}',
                        type: 'POST',
                        data: {_token: token, id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            getPlanList();
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
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_plan.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_plan.header_menu') }}</li>
                                    <li class="breadcrumb-item active">{{ trans('admin_plan.title') }}</li>
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
                                        <form action="#" class="wizard-horizontal">
                                        @csrf
                                            <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-lg-3">
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
                                                            <label for="college_name">{{ trans('admin_plan.college_name') }}</label>
                                                            <input type="text" class="form-control" id="college_name" placeholder="{{ trans('admin_plan.college_name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="college_code">{{ trans('admin_plan.college_code') }}</label>
                                                            <input type="text" class="form-control" id="college_code" placeholder="{{ trans('admin_plan.college_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="college_type">{{ trans('admin_plan.college_type') }}</label>
                                                            <select class="form-control" id="college_type">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $type_list as $type_info)
                                                                    <option value="{{ $type_info['id'] }}" @if ($type_info['id'] == old('type')) selected @endif>{{ $type_info['type'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="college_property">{{ trans('admin_plan.college_property') }}</label>
                                                            <select class="form-control" id="college_property">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $property_list as $property_info)
                                                                    <option value="{{ $property_info['id'] }}" @if ($property_info['id'] == old('property')) selected @endif>{{ $property_info['property'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="academic_year">{{ trans('admin_plan.academic_year') }}</label>
                                                            <input type="text" class="form-control" id="academic_year" placeholder="{{ trans('admin_plan.academic_year') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="department_name">{{ trans('admin_plan.department_name') }}</label>
                                                            <input type="text" class="form-control" id="department_name" placeholder="{{ trans('admin_plan.department_name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="department_code">{{ trans('admin_plan.department_code') }}</label>
                                                            <input type="text" class="form-control" id="department_code" placeholder="{{ trans('admin_plan.department_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="major_name">{{ trans('admin_plan.major_name') }}</label>
                                                            <input type="text" class="form-control" id="major_name" placeholder="{{ trans('admin_plan.major_name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="major_code">{{ trans('admin_plan.major_code') }}</label>
                                                            <input type="text" class="form-control" id="major_code" placeholder="{{ trans('admin_plan.major_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="subject">{{ trans('admin_plan.subject') }}</label>
                                                            <select class="form-control" id="subject">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($subject_list as $subject_info)
                                                                    <option value="{{ $subject_info['id'] }}"> {{ $subject_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="cost">{{ trans('admin_plan.cost') }}</label>
                                                            <div class="col-lg-12 row">
                                                                <input type="number" class="form-control col-sm-5" id="cost_from">
                                                                <span class="form-control-plaintext col-lg-1 text-center">~</span>
                                                                <input type="number" class="form-control col-sm-5" id="cost_to">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="recruitment_num">{{ trans('admin_plan.recruitment_num') }}</label>
                                                            <div class="col-md-12 row">
                                                                <input type="number" class="form-control col-md-6" id="recruitment_num" placeholder="{{ trans('admin_plan.recruitment_num') }}">
                                                                &nbsp;
                                                                <select class="form-control col-md-5" id="recruitment_num_compare">
                                                                    <option value="{{MORE}}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{LESS}}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="status">{{ trans('admin_plan.status') }}</label>
                                                            <select class="form-control" id="status">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                <option value="{{ ACTIVE }}">{{ trans('constant.status.active') }}</option>
                                                                <option value="{{ INACTIVE }}">{{ trans('constant.status.inactive') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 text-center">
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getPlanList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                            <a type="button" class="btn btn-success mr-1 mb-1" href="{{ route('admin.plan.add') }}">{{ trans('button.add') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                            <fieldset>
                                                <div class="table-responsive">
                                                <table class="table" id="plan_list_tbl">
                                                    <thead class="text-center">
                                                    <tr>
                                                        <th>{{ trans('admin_plan.no') }}</th>
                                                        <th>{{ trans('admin_plan.batch_title') }}</th>
                                                        <th>{{ trans('admin_plan.batch_code') }}</th>
                                                        <th>{{ trans('admin_plan.college_name') }}</th>
                                                        <th>{{ trans('admin_plan.college_code') }}</th>
                                                        <th>{{ trans('admin_plan.college_type') }}</th>
                                                        <th>{{ trans('admin_plan.college_property') }}</th>
                                                        <th>{{ trans('admin_plan.department_name') }}</th>
                                                        <th>{{ trans('admin_plan.department_code') }}</th>
                                                        <th>{{ trans('admin_plan.major_name') }}</th>
                                                        <th>{{ trans('admin_plan.major_code') }}</th>
                                                        <th>{{ trans('admin_plan.subject') }}</th>
                                                        <th>{{ trans('admin_plan.academic_year') }}</th>
                                                        <th>{{ trans('admin_plan.cost') }}</th>
                                                        <th>{{ trans('admin_plan.recruitment_num') }}</th>
                                                        <th>{{ trans('admin_plan.status') }}</th>
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