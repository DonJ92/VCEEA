@extends('layouts.auth')

@section('title', trans('register.title'))

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- register section starts -->
                <section class="row flexbox-container">
                    <div class="col-xl-8 col-10">
                        <div class="card bg-authentication mb-0">
                            <div class="row m-0">
                                <!-- register section left -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="text-bold-600 text-center mb-2">{{ config('app.name', '辽宁省高考志愿辅助系统') }} - {{ trans('register.title') }}</h4>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="divider">
                                                    <div class="divider-text text-uppercase text-muted"><small>{{trans('register.desc')}}</small>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('register') }}">
                                                    @csrf

                                                    <div class="form-group mb-50">
                                                        <label for="user_code">{{trans('register.user_code')}}<span class="text-danger">&nbsp;*</span></label>
                                                        <input type="text" class="form-control @error('user_code') is-invalid @enderror" autocomplete="user_code"
                                                               id="user_code" name="user_code" value="{{ old('user_code') }}" placeholder="{{trans('register.user_code')}}">
                                                        @error('user_code')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 mb-50">
                                                            <label  for="name">{{trans('register.name')}}<span class="text-danger">&nbsp;*</span></label>
                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="{{trans('register.name')}}">
                                                            @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 mb-50">
                                                            <label  for="birthday">{{ trans('register.birthday') }}<span class="text-danger">&nbsp;*</span></label>
                                                            <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday') }}" placeholder="{{ trans('register.birthday') }}">
                                                            @error('birthday')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6 mb-50">
                                                            <label  for="gender">{{ trans('register.gender') }}<span class="text-danger">&nbsp;*</span></label>
                                                            <ul class="form-control-plaintext">
                                                                <li class="d-inline-block mr-2">
                                                                    <fieldset>
                                                                        <div class="radio">
                                                                            <input type="radio" name="gender" id="male" value="{{ MALE }}" @if(empty(old('gender'))) checked @elseif (old('gender') == MALE) checked @endif>
                                                                            <label for="male">{{ trans('constant.male') }}</label>
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                                <li class="d-inline-block mr-2">
                                                                    <fieldset>
                                                                        <div class="radio">
                                                                            <input type="radio" name="gender" id="female" value="{{ FEMALE }}" @if (old('gender') == FEMALE) checked @endif>
                                                                            <label for="female">{{ trans('constant.female') }}</label>
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-50">
                                                        <label  for="id_code">{{ trans('register.id_code') }}<span class="text-danger">&nbsp;*</span></label>
                                                        <input type="text" class="form-control @error('id_code') is-invalid @enderror" id="id_code" name="id_code" value="{{ old('id_code') }}" placeholder="{{ trans('register.id_code') }}">
                                                        @error('id_code')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 mb-50">
                                                            <label  for="email">{{ trans('register.email') }}<span class="text-danger">&nbsp;*</span></label>
                                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('register.email') }}">
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6 mb-50">
                                                            <label  for="phone">{{ trans('register.phone') }}<span class="text-danger">&nbsp;*</span></label>
                                                            <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ trans('register.phone') }}">
                                                            @error('phone')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-50">
                                                        <label for="major">{{ trans('register.major') }}<span class="text-danger">&nbsp;*</span>&nbsp;&nbsp;&nbsp;<span>{{ trans('register.desc_01') }}</span></label>
                                                        <select class="select2 form-control @error('major') is-invalid @enderror" style="height:150px;" multiple="multiple" id="major" name="major[]">
                                                            @foreach($major_list as $major_info)
                                                                <option value="{{ $major_info['id'] }}" @if(!empty(old('major')) && in_array($major_info['id'], old('major'))) selected @endif  > {{ $major_info['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('major')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mb-50">
                                                        <label  for="password">{{ trans('register.password') }}<span class="text-danger">&nbsp;*</span></label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="{{ trans('register.password') }}">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label  for="password_confirmation">{{ trans('register.password_confirmation') }}</label>
                                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('register.password_confirmation') }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary glow position-relative w-100">{{ trans('button.register') }}<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                                </form>
                                                <hr>
                                                <div class="text-center"><small class="mr-25">{{trans('register.already_account')}}</small><a href="{{ route('login') }}"><small>{{trans('button.login')}}</small> </a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- image section right -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                                    <img class="img-fluid" src="{{ asset('app-assets/images/pages/register.png') }}" alt="branding logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- register section endss -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
