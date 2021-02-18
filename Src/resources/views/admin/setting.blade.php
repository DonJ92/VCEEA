@extends('layouts.admin')

@section('title', trans('admin_setting.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_setting').addClass('active');

            @if ($errors->has('password') || $errors->has('password_confirmation') || $errors->has('old_password') || session('pwd_success') || $errors->has('pwd_failed'))
                $('#account-vertical-general').removeClass('active');
                $('#account-vertical-password').addClass('active show');
                $('#account-pill-general').removeClass('active');
                $('#account-pill-password').addClass('active');
            @endif
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_setting.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_setting.header_menu') }}</li>
                                    <li class="breadcrumb-item active">{{ trans('admin_setting.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="page-account-settings">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <!-- left menu section -->
                                <div class="col-md-2 mb-2 mb-md-0 pills-stacked">
                                    <ul class="nav nav-pills flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="false">
                                                <i class="bx bx-cog"></i>
                                                <span>{{ trans('admin_setting.update_profile') }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="true">
                                                <i class="bx bx-lock"></i>
                                                <span>{{ trans('admin_setting.update_password') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- right content section -->
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
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
                                                        <form method="POST" action="{{ route('admin.setting.profile') }}">
                                                            @csrf

                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.admin_code') }}</label>
                                                                            <input type="text" class="form-control @error('admin_code') is-invalid @enderror" id="admin_code" name="admin_code" value="{{ old('admin_code') ?  old('admin_code') : Auth::user()->admin_code }}" placeholder="{{ trans('admin_setting.admin_code') }}">
                                                                            @error('admin_code')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.name') }}</label>
                                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?  old('name') : Auth::user()->name }}" placeholder="{{ trans('admin_setting.name') }}">
                                                                            @error('name')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.id_code') }}</label>
                                                                            <input type="text" class="form-control @error('id_code') is-invalid @enderror" id="id_code" name="id_code" value="{{ old('id_code') ?  old('id_code') : Auth::user()->id_code }}" placeholder="{{ trans('admin_setting.id_code') }}">
                                                                            @error('id_code')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.email') }}</label>
                                                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?  old('email') : Auth::user()->email }}" placeholder="{{ trans('admin_setting.email') }}">
                                                                            @error('email')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.phone') }}</label>
                                                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') ?  old('phone') : Auth::user()->phone }}" placeholder="{{ trans('admin_setting.phone') }}">
                                                                            @error('phone')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                    <button type="submit" class="btn btn-primary glow mr-sm-1 mb-1">{{ trans('button.update') }}</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane fade " id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                                        @if (session('pwd_success'))
                                                            <div class="alert bg-success bg-light" id="success_div">
                                                                <strong>{{ session('pwd_success') }}</strong>
                                                            </div>
                                                        @endif
                                                        @if ($errors->has('pwd_failed'))
                                                            <div class="alert bg-danger bg-light" id="failed_div">
                                                                <strong>{{ $errors->first('pwd_failed') }}</strong>
                                                            </div>
                                                        @endif
                                                        <form method="POST" action="{{ route('admin.setting.password') }}">
                                                            @csrf

                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.old_password') }}</label>
                                                                            <input type="password" name="old_password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ trans('admin_setting.old_password') }}">
                                                                            @error('old_password')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.new_password') }}</label>
                                                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ trans('admin_setting.new_password') }}">
                                                                            @error('password')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <label>{{ trans('admin_setting.new_password_confirm') }}</label>
                                                                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ trans('admin_setting.new_password_confirm') }}">
                                                                            @error('password_confirmation')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                    <button type="submit" class="btn btn-primary glow mr-sm-1 mb-1">{{ trans('button.update') }}</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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