@extends('layouts.admin')

@section('title', trans('admin_department.edit_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_department').addClass('active');
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_department.edit_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_department.header_menu') }}</li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.department') }}">{{ trans('admin_department.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_department.edit_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="department-add-form">
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
                                        <form method="post" action="{{ route('admin.department.edit.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <input type="hidden" name="id" value="{{ $department_info['id'] }}">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="name">{{ trans('admin_department.name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?  old('name') : $department_info['name'] }}" placeholder="{{ trans('admin_department.name') }}">
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
                                                                <label for="code">{{ trans('admin_department.code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"  value="{{ old('code') ? old('code') : $department_info['code'] }}" placeholder="{{ trans('admin_department.code') }}">
                                                                @error('code')
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
                                                                <label for="description">{{ trans('admin_department.description') }}</label>
                                                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') ? old('description') : $department_info['description'] }}" placeholder="{{ trans('admin_department.description') }}">
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
                                                                <label for="status">{{ trans('admin_department.status') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                                    <option value="{{ ACTIVE }}" @if((old('status') ? old('status') : $department_info['status']) == ACTIVE) selected @endif >{{ trans('constant.status.active') }}</option>
                                                                    <option value="{{ INACTIVE }}" @if((old('status') ? old('status') : $department_info['status']) == INACTIVE) selected @endif>{{ trans('constant.status.inactive') }}</option>
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.department') }}">{{ trans('button.back') }}</a>
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