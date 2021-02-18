@extends('layouts.app')

@section('title', trans('follow.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_follow').addClass('active');
            getFollowList()
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getFollowList();
            }
        });

        function getFollowList() {
            var token = $("input[name=_token]").val();
            var batch_title = $('#batch_title').val();
            var batch_code = $('#batch_code').val();
            var college_type = $('#college_type').val();
            var college_property = $('#college_property').val();
            var major_name = $('#major_name').val();
            var subject_id = $('#subject').val();
            var re_subject_id = $('#re_subject').val();

            $.ajax({
                url: '{{ route('follow.getlist') }}',
                type: 'POST',
                data: {_token: token, batch_title: batch_title, batch_code: batch_code,
                    college_type:college_type, college_property: college_property,
                    major_name:major_name, subject_id:subject_id, re_subject_id:re_subject_id,
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

                            var checkbox = '<input type="checkbox" id="id_' + response[i].batch_id + '_' + response[i].id + '" name="id[]" value = "' + response[i].id + '" onclick="onCheck(' + response[i].batch_id  + ', ' + response[i].id + ')"></input>'

                            if (response[i].college_url == null || response[i].college_url == "")
                                var college_name = response[i].college_name;
                            else
                                var college_name = '<a target="_blank" href="'+response[i].college_url+'">'+response[i].college_name+'</a>';

                            var action = '<a class="text-danger cursor-pointer" id="follow_' + response[i].id + '" onclick="onUnFollow(' + response[i].id + ')">{{ trans('button.unfollow') }}</a>';

                            datas.push([
                                checkbox,
                                num,
                                response[i].batch_title,
                                response[i].batch_code,
                                college_name,
                                response[i].college_code,
                                response[i].addr_province,
                                response[i].college_type,
                                response[i].college_property,
                                response[i].department_name,
                                response[i].major_name,
                                response[i].major_code,
                                response[i].subject_name,
                                response[i].re_subject_name,
                                response[i].academic_year_code,
                                response[i].cost,
                                response[i].recruitment_num,
                                action
                            ]);
                        }
                    }

                    $('#plan_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        columnDefs: [
                            { orderable: false, targets: 0 }
                        ],
                        "order": [[ 1, "asc" ]],
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

        function onCheck(batch_id, plan_id)
        {
            var token = $("input[name=_token]").val();

            $.ajax({
                url: '{{ route('follow.getrestriction') }}',
                type: 'POST',
                data: {_token: token, batch_id: batch_id},
                dataType: 'JSON',
                success: function (response) {
                    if (response == undefined || response.length == 0   || response == false) {
                    } else {
                        if ($('#id_'+batch_id+'_'+plan_id).is(':checked') == true) {
                            for (var i = 0; i < response.length; i++) {
                                $('input[id*="id_' + response[i] + '_"]').prop('disabled', true);
                                $('input[id*="id_' + response[i] + '_"]').prop('checked', false);
                            }
                        } else {
                            for (var i = 0; i < response.length; i++) {
                                $('input[id*="id_' + response[i] + '_"]').prop('disabled', false);
                                $('input[id*="id_' + response[i] + '_"]').prop('checked', false);
                            }
                        }
                    }
                }
            });
        }

        function onUnFollow(id)
        {
            var token = $("input[name=_token]").val();

            $.ajax({
                url: '{{ route('plan.unfollow') }}',
                type: 'POST',
                data: {_token: token, plan_id: id},
                dataType: 'JSON',
                success: function (response) {
                    if (response == undefined || response.length == 0 || response == false) {
                    } else {
                        getFollowList();
                    }
                }
            });
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('follow.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item active">{{ trans('follow.title') }}</li>
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
                                        <form method="post" action="{{ route('follow.simulate') }}" class="wizard-horizontal">
                                        @csrf
                                        <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="batch_title">{{ trans('follow.batch_title') }}</label>
                                                            <select class="form-control" id="batch_title">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($batch_list as $batch_info)
                                                                    <option value="{{ $batch_info['id'] }}"> {{ $batch_info['title'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="batch_code">{{ trans('follow.batch_code') }}</label>
                                                            <select class="form-control" id="batch_code">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($batch_list as $batch_info)
                                                                    <option value="{{ $batch_info['id'] }}"> {{ $batch_info['code'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="college_type">{{ trans('follow.college_type') }}</label>
                                                            <select class="form-control" id="college_type">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $type_list as $type_info)
                                                                    <option value="{{ $type_info['id'] }}">{{ $type_info['type'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="college_property">{{ trans('follow.college_property') }}</label>
                                                            <select class="form-control" id="college_property">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $property_list as $property_info)
                                                                    <option value="{{ $property_info['id'] }}">{{ $property_info['property'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="major_name">{{ trans('follow.major_name') }}</label>
                                                            <select class="form-control" id="major_name">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $major_list as $major_info)
                                                                    <option value="{{ $major_info['id'] }}">{{ $major_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="subject">{{ trans('follow.subject') }}</label>
                                                            <select class="form-control" id="subject">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($subject_list as $subject_info)
                                                                    <option value="{{ $subject_info['id'] }}"> {{ $subject_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="re_subject">{{ trans('follow.re_subject') }}</label>
                                                            <select class="form-control" id="re_subject">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($subject_list as $subject_info)
                                                                    <option value="{{ $subject_info['id'] }}"> {{ $subject_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 text-center">
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getFollowList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                            <button type="submit" class="btn btn-success mr-1 mb-1">{{ trans('button.simulate') }}</button>
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
                                                            <th></th>
                                                            <th>{{ trans('follow.no') }}</th>
                                                            <th>{{ trans('follow.batch_title') }}</th>
                                                            <th>{{ trans('follow.batch_code') }}</th>
                                                            <th>{{ trans('follow.college_name') }}</th>
                                                            <th>{{ trans('follow.college_code') }}</th>
                                                            <th>{{ trans('follow.addr_province') }}</th>
                                                            <th>{{ trans('follow.college_type') }}</th>
                                                            <th>{{ trans('follow.college_property') }}</th>
                                                            <th>{{ trans('follow.department_name') }}</th>
                                                            <th>{{ trans('follow.major_name') }}</th>
                                                            <th>{{ trans('follow.major_code') }}</th>
                                                            <th>{{ trans('follow.subject') }}</th>
                                                            <th>{{ trans('follow.re_subject') }}</th>
                                                            <th>{{ trans('follow.academic_year') }}</th>
                                                            <th>{{ trans('follow.cost') }}</th>
                                                            <th>{{ trans('follow.recruitment_num') }}</th>
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