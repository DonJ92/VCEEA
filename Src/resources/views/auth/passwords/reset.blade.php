@extends('layouts.auth')

@section('title', trans('passwords.reset_title'))

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- reset password start -->
            <section class="row flexbox-container">
                <div class="col-xl-7 col-10">
                    <div class="card bg-authentication mb-0">
                        <div class="row m-0">
                            <!-- left section-login -->
                            <div class="col-md-6 col-12 px-0">
                                <div class="card disable-rounded-right d-flex justify-content-center mb-0 p-2 h-100">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="text-center mb-2">{{ trans('passwords.reset_title') }}</h4>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form class="mb-2" method="POST" action="{{ route('password.update') }}">
                                                @csrf

                                                <input type="hidden" name="token" value="{{ $token }}">
                                                <div class="form-group">
                                                    <label class="text-bold-600" for="email">{{ trans('passwords.email') }}</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ trans('passwords.email') }}" value="{{ $email ?? old('email') }}">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-bold-600" for="password">{{ trans('passwords.password') }}</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ trans('passwords.password') }}">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="text-bold-600" for="password_confirmation">{{ trans('passwords.password_confirm') }}</label>
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('passwords.password_confirm') }}">
                                                </div>
                                                <button type="submit" class="btn btn-primary glow position-relative w-100">{{ trans('button.reset_pwd') }}<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right section image -->
                            <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                                <img class="img-fluid" src="{{ asset('app-assets/images/pages/reset-password.png') }}" alt="branding logo">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- reset password ends -->

        </div>
    </div>
</div>
@endsection
