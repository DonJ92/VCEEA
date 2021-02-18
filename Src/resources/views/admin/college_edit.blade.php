@extends('layouts.admin')

@section('title', trans('admin_college.edit_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_college').addClass('active');
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
                        .append('<option selected="selected"></option>');

                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            $("#addr_city").append(new Option(response[i].name, response[i].id));
                        }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_college.edit_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_college.header_menu') }}</li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.college') }}">{{ trans('admin_college.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_college.edit_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="college-add-form">
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
                                        <form method="post" action="{{ route('admin.college.edit.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <input type="hidden" name="id" value="{{ $college_info['id'] }}">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="name">{{ trans('admin_college.name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?  old('name') : $college_info['name'] }}" placeholder="{{ trans('admin_college.name') }}">
                                                                @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="code">{{ trans('admin_college.code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"  value="{{ old('code') ? old('code') : $college_info['code'] }}" placeholder="{{ trans('admin_college.code') }}">
                                                                @error('code')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="national_code">{{ trans('admin_college.national_code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('national_code') is-invalid @enderror" name="national_code" value="{{ old('national_code') ? old('national_code') : $college_info['national_code'] }}" id="national_code" placeholder="{{ trans('admin_college.national_code') }}">
                                                                @error('national_code')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="addr_province">{{ trans('admin_college.addr_province') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('addr_province') is-invalid @enderror" id="addr_province" name="addr_province" onchange="onChangeProvince();">
                                                                    <option></option>
                                                                    @foreach($province_list as $province_info)
                                                                        <option value="{{ $province_info['id'] }}" @if($province_info['id'] == (old('addr_province') ? old('addr_province') : $college_info['addr_province']) ) selected @endif> {{ $province_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('addr_province')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="addr_city">{{ trans('admin_college.addr_city') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('addr_city') is-invalid @enderror" id="addr_city" name="addr_city">
                                                                    <option></option>
                                                                    @foreach($city_list as $city_info)
                                                                        <option value="{{ $city_info['id'] }}" @if($city_info['id'] == (old('addr_city') ? old('addr_city') : $college_info['addr_city']) ) selected @endif> {{ $city_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('addr_city')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="addr_detail">{{ trans('admin_college.addr_detail') }}</label>
                                                                <input type="text" class="form-control @error('addr_detail') is-invalid @enderror" id="addr_detail" name="addr_detail" value="{{ old('addr_detail') ? old('addr_detail') : $college_info['addr_detail'] }}" placeholder="{{ trans('admin_college.addr_detail') }}">
                                                                @error('addr_detail')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="type">{{ trans('admin_college.type') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                                                    <option></option>
                                                                    @foreach( $type_list as $type_info)
                                                                        <option value="{{ $type_info['id'] }}" @if ($type_info['id'] == (old('type') ? old('type') : $college_info['type'] ) ) selected @endif>{{ $type_info['type'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('type')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="property">{{ trans('admin_college.property') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('property') is-invalid @enderror" id="property" name="property">
                                                                    <option></option>
                                                                    @foreach( $property_list as $property_info)
                                                                        <option value="{{ $property_info['id'] }}" @if ($property_info['id'] == (old('property') ? old('property') : $college_info['property']) ) selected @endif>{{ $property_info['property'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('property')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="site_url">{{ trans('admin_college.site_url') }}</label>
                                                                <input type="text" class="form-control @error('site_url') is-invalid @enderror" id="site_url" name="site_url" value="{{ old('site_url') ? old('site_url') : $college_info['site_url'] }}" placeholder="{{ trans('admin_college.site_url') }}">
                                                                @error('site_url')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="description">{{ trans('admin_college.description') }}</label>
                                                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') ? old('description') : $college_info['description'] }}" placeholder="{{ trans('admin_college.description') }}">
                                                                @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="status">{{ trans('admin_college.status') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                                    <option value="{{ ACTIVE }}" @if((old('status') ? old('status') : $college_info['status']) == ACTIVE) selected @endif >{{ trans('constant.status.active') }}</option>
                                                                    <option value="{{ INACTIVE }}" @if((old('status') ? old('status') : $college_info['status']) == INACTIVE) selected @endif>{{ trans('constant.status.inactive') }}</option>
                                                                </select>
                                                                @error('status')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6"><hr></div>
                                                    <div class="row">
                                                        <div class="col-md-6 text-center">
                                                            <div>
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.college') }}">{{ trans('button.back') }}</a>
                                                                <button type="submit" class="btn btn-primary mr-1 mb-1">{{ trans('button.update') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
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