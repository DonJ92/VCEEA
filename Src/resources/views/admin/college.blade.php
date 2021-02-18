@extends('layouts.admin')

@section('title', trans('admin_college.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_college').addClass('active');
            getCollegeList();
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getCollegeList();
            }
        });

        function onChangeProvince() {
            var token = $("input[name=_token]").val();
            var province_id = $('#addr_province').val();

            $.ajax({
                url: '{{ route('admin.getcitylist') }}',
                type: 'POST',
                data: {_token: token, province_id: province_id},
                dataType: 'JSON',
                success: function (response) {
                    $('#addr_city')
                        .empty()
                        .append('<option selected="selected" value="">{{ trans('common.all') }}</option>');

                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            $("#addr_city").append(new Option(response[i].name, response[i].id));
                        }
                    }
                }
            });
        }

        function getCollegeList() {
            var token = $("input[name=_token]").val();
            var name = $('#name').val();
            var code = $('#code').val();
            var national_code = $('#national_code').val();
            var addr_province = $('#addr_province').val();
            var addr_city = $('#addr_city').val();
            var type = $('#type').val();
            var property = $('#property').val();
            var description = $('#description').val();
            var status = $('#status').val();

            $.ajax({
                url: '{{ route('admin.college.getlist') }}',
                type: 'POST',
                data: {_token: token, name: name, code:code, national_code:national_code,
                    addr_province:addr_province, addr_city:addr_city, type:type, property:property,
                    description:description, status:status},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#college_list_tbl' ) ) {
                        var college_list_tbl = $('#college_list_tbl').DataTable();
                        college_list_tbl.destroy();
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

                            if (!response[i].site_url)
                                var site_url = '';
                            else
                                var site_url = '<a href="'+response[i].site_url+'" target="_blank">'+response[i].site_url+'</a>';

                            var action = '<a class="text-primary" href="{{url('admin/college/edit')}}/' + response[i].id + '">{{ trans('button.edit') }}</a>&nbsp;&nbsp;&nbsp;<a class="text-danger" href="javascript:onDelete(' + response[i].id + ')">{{ trans('button.delete') }}</a>'

                            datas.push([
                                num,
                                response[i].name,
                                response[i].code,
                                response[i].national_code,
                                response[i].province_name,
                                response[i].city_name,
                                response[i].type,
                                response[i].property,
                                site_url,
                                response[i].description,
                                status,
                                action
                            ]);
                        }
                    }

                    $('#college_list_tbl').dataTable({
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

                    $('#college_list_tbl').css('width', '');
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
                        url: '{{ route('admin.college.delete') }}',
                        type: 'POST',
                        data: {_token: token, id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            getCollegeList();
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
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_college.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_college.header_menu') }}</li>
                                    <li class="breadcrumb-item active">{{ trans('admin_college.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="college-search">
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
                                                            <label for="name">{{ trans('admin_college.name') }}</label>
                                                            <input type="text" class="form-control" id="name" placeholder="{{ trans('admin_college.name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="code">{{ trans('admin_college.code') }}</label>
                                                            <input type="text" class="form-control" id="code" placeholder="{{ trans('admin_college.code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="national_code">{{ trans('admin_college.national_code') }}</label>
                                                            <input type="text" class="form-control" id="national_code" placeholder="{{ trans('admin_college.national_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="addr_province">{{ trans('admin_college.addr_province') }}</label>
                                                            <select class="form-control" id="addr_province" onchange="onChangeProvince();">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($province_list as $province_info)
                                                                    <option value="{{ $province_info['id'] }}"> {{ $province_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="addr_city">{{ trans('admin_college.addr_city') }}</label>
                                                            <select class="form-control" id="addr_city">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($city_list as $city_info)
                                                                    <option value="{{ $city_info['id'] }}"> {{ $city_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="type">{{ trans('admin_college.type') }}</label>
                                                            <select class="form-control" id="type">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $type_list as $type_info)
                                                                    <option value="{{ $type_info['id'] }}" @if ($type_info['id'] == old('type')) selected @endif>{{ $type_info['type'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="property">{{ trans('admin_college.property') }}</label>
                                                            <select class="form-control" id="property">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach( $property_list as $property_info)
                                                                    <option value="{{ $property_info['id'] }}" @if ($property_info['id'] == old('property')) selected @endif>{{ $property_info['property'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="status">{{ trans('admin_college.status') }}</label>
                                                            <select class="form-control" id="status">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                <option value="{{ ACTIVE }}">{{ trans('constant.status.active') }}</option>
                                                                <option value="{{ INACTIVE }}">{{ trans('constant.status.inactive') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="description">{{ trans('admin_college.description') }}</label>
                                                            <input type="text" class="form-control" id="description" placeholder="{{ trans('admin_college.description') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getCollegeList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                            <a class="btn btn-success mr-1 mb-1" href="{{ route('admin.college.add') }}">{{ trans('button.add') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="college_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('admin_college.no') }}</th>
                                                    <th>{{ trans('admin_college.name') }}</th>
                                                    <th>{{ trans('admin_college.code') }}</th>
                                                    <th>{{ trans('admin_college.national_code') }}</th>
                                                    <th>{{ trans('admin_college.addr_province') }}</th>
                                                    <th>{{ trans('admin_college.addr_city') }}</th>
                                                    <th>{{ trans('admin_college.type') }}</th>
                                                    <th>{{ trans('admin_college.property') }}</th>
                                                    <th>{{ trans('admin_college.site_url') }}</th>
                                                    <th>{{ trans('admin_college.description') }}</th>
                                                    <th>{{ trans('admin_college.status') }}</th>
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