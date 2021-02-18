@extends('layouts.admin')

@section('title', trans('admin_score.edit_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_score_history').addClass('active');

            var currentYear = (new Date).getFullYear();

            for (var i = 0; i < 20; i++)
                $("#year").append(new Option(currentYear - i, currentYear - i));

            $('#year').val({{ old('year') ? old('year') : $score_info['year'] }});
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_score.edit_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.score') }}">{{ trans('admin_score.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_score.edit_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="score-add-form">
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
                                        <form method="post" action="{{ route('admin.score.edit.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <input type="hidden" name="id" value="{{ $score_info['id'] }}">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="batch_title">{{ trans('admin_score.batch_title') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('batch_title') is-invalid @enderror" id="batch_title" name="batch_title">
                                                                    <option></option>
                                                                    @foreach($batch_list as $batch_info)
                                                                        <option value="{{ $batch_info['id'] }}" @if((old('batch_title') ? old('batch_title') : $score_info['batch_id']) == $batch_info['id']) selected @endif> {{ $batch_info['title'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('batch_title')
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
                                                                <label for="year">{{ trans('admin_score.year') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('year') is-invalid @enderror" id="year" name="year">
                                                                    <option></option>
                                                                </select>
                                                                @error('year')
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
                                                                <label for="college_name">{{ trans('admin_score.college_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('college_name') is-invalid @enderror" id="college_name" name="college_name">
                                                                    <option></option>
                                                                    @foreach($college_list as $college_info)
                                                                        <option value="{{ $college_info['id'] }}" @if((old('college_name') ? old('college_name') : $score_info['college_id']) == $college_info['id']) selected @endif> {{ $college_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('college_name')
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
                                                                <label for="department_name">{{ trans('admin_score.department_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('department_name') is-invalid @enderror" id="department_name" name="department_name">
                                                                    <option></option>
                                                                    @foreach($department_list as $department_info)
                                                                        <option value="{{ $department_info['id'] }}" @if((old('department_name') ? old('department_name') : $score_info['department_id'] ) == $department_info['id']) selected @endif> {{ $department_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('department_name')
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
                                                                <label for="major_name">{{ trans('admin_score.major_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('major_name') is-invalid @enderror" id="major_name" name="major_name">
                                                                    <option></option>
                                                                    @foreach($major_list as $major_info)
                                                                        <option value="{{ $major_info['id'] }}" @if((old('major_name') ? old('major_name') : $score_info['major_id']) == $major_info['id']) selected @endif> {{ $major_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('major_name')
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
                                                                <label for="user_code">{{ trans('admin_score.user_code') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('user_code') is-invalid @enderror" id="user_code" name="user_code" value="{{ old('user_code') ? old('user_code') : $score_info['user_code'] }}" placeholder="{{ trans('admin_score.user_code') }}">
                                                                @error('user_code')
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
                                                                <label for="score">{{ trans('admin_score.score') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="number" class="form-control @error('score') is-invalid @enderror" id="score" name="score"  value="{{ old('score') ? old('score') : $score_info['score'] }}" placeholder="{{ trans('admin_score.score') }}">
                                                                @error('score')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="ranking">{{ trans('admin_score.ranking') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('ranking') is-invalid @enderror" name="ranking" value="{{ old('ranking') ? old('ranking') : $score_info['ranking'] }}" id="ranking" placeholder="{{ trans('admin_score.ranking') }}">
                                                                @error('ranking')
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
                                                                <label for="min">{{ trans('admin_score.min') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="number" class="form-control @error('min') is-invalid @enderror" id="min" name="min"  value="{{ old('min') ? old('min') : $score_info['min'] }}" placeholder="{{ trans('admin_score.min') }}">
                                                                @error('min')
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
                                                                <label for="max">{{ trans('admin_score.max') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="number" class="form-control @error('max') is-invalid @enderror" id="max" name="max"  value="{{ old('max') ? old('max') : $score_info['max'] }}" placeholder="{{ trans('admin_score.max') }}">
                                                                @error('max')
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
                                                                <label for="average">{{ trans('admin_score.average') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="number" class="form-control @error('average') is-invalid @enderror" id="average" name="average"  value="{{ old('average') ? old('average') : $score_info['average'] }}" placeholder="{{ trans('admin_score.average') }}">
                                                                @error('average')
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.score') }}">{{ trans('button.back') }}</a>
                                                                <button type="submit" class="btn btn-primary mr-1 mb-1">{{ trans('button.edit') }}</button>
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