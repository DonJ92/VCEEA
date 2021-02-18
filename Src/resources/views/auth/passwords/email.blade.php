@extends('layouts.auth')

@section('title', trans('passwords.forgot_title'))

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- forgot password start -->
                <section class="row flexbox-container">
                    <div class="col-xl-7 col-md-9 col-10  px-0">
                        <div class="card bg-authentication mb-0">
                            <div class="row m-0">
                                <!-- left section-forgot password -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="text-center mb-2">{{ trans('passwords.forgot_title') }}</h4>
                                            </div>
                                        </div>
                                        @if (session('status'))
                                            <div class="alert bg-success bg-light" id="success_div">
                                                <strong>{{ session('status') }}</strong>
                                            </div>
                                        @endif
                                        <div class="form-group d-flex justify-content-between align-items-center mb-2">
                                            <div class="text-left">
                                                <div class="ml-3 ml-md-2 mr-1"><a href="{{ route('login') }}" class="card-link btn btn-outline-primary text-nowrap">{{ trans('button.login') }}</a></div>
                                            </div>
                                            <div class="mr-3"><a href="{{ route('register') }}" class="card-link btn btn-outline-primary text-nowrap">{{ trans('button.register') }}</a></div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="text-muted text-center mb-2"><small>{{ trans('passwords.forgot_desc') }}</small></div>
                                                <form class="mb-2" method="POST" action="{{ route('password.email') }}">
                                                    @csrf

                                                    <div class="form-group mb-2">
                                                        <label class="text-bold-600" for="email">{{ trans('passwords.email') }}</label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('passwords.email') }}" autofocus>
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow position-relative w-100">{{ trans('button.send') }}<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- right section image -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center">
                                    <img class="img-fluid" src="{{ asset('app-assets/images/pages/forgot-password.png') }}" alt="branding logo" width="300">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- forgot password ends -->
            </div>
        </div>
    </div>
@endsection
