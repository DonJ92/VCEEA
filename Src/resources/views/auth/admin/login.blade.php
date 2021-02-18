@extends('layouts.auth')

@section('title', trans('admin_login.title'))

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- login page start -->
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-8 col-11">
                        <div class="card bg-authentication mb-0">
                            <div class="row m-0">
                                <!-- left section-login -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="text-bold-600 text-center mb-2">{{ config('app.name', '辽宁省高考志愿辅助系统') }} - {{ trans('admin_login.admin') }}</h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="divider">
                                                    <div class="divider-text text-uppercase text-muted"><small>{{ trans('admin_login.title') }}</small></div>
                                                </div>
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
                                                <form method="POST" action="{{ route('admin.login.submit') }}">
                                                    @csrf

                                                    <div class="form-group mb-50">
                                                        <label for="user_code">{{ trans('admin_login.admin_code') }}</label>
                                                        <input type="text" class="form-control @error('admin_code') is-invalid @enderror" id="admin_code" name="admin_code" value="{{ old('admin_code') }}" placeholder="{{ trans('admin_login.admin_code') }}">
                                                        @error('admin_code')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password">{{ trans('admin_login.password') }}</label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ trans('admin_login.password') }}">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                        <div class="text-right"><a href="auth-forgot-password.html" class="card-link"><small>{{ trans('admin_login.forgot_password') }}</small></a></div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow w-100 position-relative">{{ trans('button.login') }}<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                                <hr>
<!--                                                <div class="text-center"><small class="mr-25">{{ trans('admin_login.no_account') }}</small><a href="{{ route('register') }}"><small>{{ trans('button.register') }}</small></a></div>    -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- right section image -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                                    <div class="card-content">
                                        <img class="img-fluid" src="{{ asset('app-assets/images/pages/login.png') }}" alt="branding logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- login page ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection