@extends('layouts.admin')

@section('title', trans('admin_account.add_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_admin_list').addClass('active');
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_account.add_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_account.header_menu') }}</li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">{{ trans('admin_account.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_account.add_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="account-add-form">
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
                                        <form method="post" action="{{ route('admin.account.add.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="admin_code">{{ trans('admin_account.admin_code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('admin_code') is-invalid @enderror" id="admin_code" name="admin_code" value="{{ old('admin_code') }}" placeholder="{{ trans('admin_account.admin_code') }}">
                                                                @error('admin_code')
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
                                                                <label for="name">{{ trans('admin_account.name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="{{ trans('admin_account.name') }}">
                                                                @error('name')
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
                                                                <label for="id_code">{{ trans('admin_account.id_code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('id_code') is-invalid @enderror" id="id_code" name="id_code" value="{{ old('id_code') }}" placeholder="{{ trans('admin_account.id_code') }}">
                                                                @error('id_code')
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
                                                                <label for="email">{{ trans('admin_account.email') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('admin_account.email') }}">
                                                                @error('email')
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
                                                                <label for="phone">{{ trans('admin_account.phone') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ trans('admin_account.phone') }}">
                                                                @error('phone')
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
                                                                <label for="role">{{ trans('admin_account.role') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control" id="role" name="role">
                                                                    <option value="{{ ADMIN_NORMAL }}" @if(old('role') == ADMIN_NORMAL) selected @endif>{{ trans('common.admin_role.normal') }}</option>
                                                                    <option value="{{ ADMIN_TOP }}" @if(old('role') == ADMIN_TOP) selected @endif>{{ trans('common.admin_role.top') }}</option>
                                                                </select>
                                                                @error('role')
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
                                                                <label for="password">{{ trans('admin_account.password') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="{{ trans('admin_account.password') }}">
                                                                @error('password')
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
                                                                <label for="password_confirmation">{{ trans('admin_account.password_confirmation') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('admin_account.password_confirmation') }}">
                                                                @error('password_confirmation')
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.account') }}">{{ trans('button.back') }}</a>
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