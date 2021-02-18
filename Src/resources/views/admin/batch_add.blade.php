@extends('layouts.admin')

@section('title', trans('admin_batch.add_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_batch').addClass('active');
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_batch.add_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_batch.header_menu') }}</li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.batch') }}">{{ trans('admin_batch.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_batch.add_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="batch-add-form">
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
                                        <form method="post" action="{{ route('admin.batch.add.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="title">{{ trans('admin_batch.title') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="{{ trans('admin_batch.title') }}">
                                                                @error('title')
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
                                                                <label for="code">{{ trans('admin_batch.code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"  value="{{ old('code') }}" placeholder="{{ trans('admin_batch.code') }}">
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
                                                                <label for="description">{{ trans('admin_batch.description') }}</label>
                                                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" placeholder="{{ trans('admin_batch.description') }}">
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
                                                                <label for="status">{{ trans('admin_batch.status') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                                    <option value="{{ ACTIVE }}" @if(old('status') == ACTIVE) selected @endif >{{ trans('constant.status.active') }}</option>
                                                                    <option value="{{ INACTIVE }}" @if(old('status') == INACTIVE) selected @endif>{{ trans('constant.status.inactive') }}</option>
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.batch') }}">{{ trans('button.back') }}</a>
                                                                <button type="submit" class="btn btn-primary mr-1 mb-1">{{ trans('button.add') }}</button>
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