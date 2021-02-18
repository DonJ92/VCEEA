@extends('layouts.admin')

@section('title', trans('admin_plan.edit_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_plan').addClass('active');
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_plan.add_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.plan') }}">{{ trans('admin_plan.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_plan.edit_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="plan-add-form">
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
                                        <form method="post" action="{{ route('admin.plan.edit.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <input type="hidden" name="id" value="{{ $plan_info['id'] }}">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="batch_title">{{ trans('admin_plan.batch_title') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('batch_title') is-invalid @enderror" id="batch_title" name="batch_title">
                                                                    <option></option>
                                                                    @foreach($batch_list as $batch_info)
                                                                        <option value="{{ $batch_info['id'] }}" @if((old('batch_title') ? old('batch_title') : $plan_info['batch_id']) == $batch_info['id']) selected @endif> {{ $batch_info['title'] }}</option>
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
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="college_name">{{ trans('admin_plan.college_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('college_name') is-invalid @enderror" id="college_name" name="college_name">
                                                                    <option></option>
                                                                    @foreach($college_list as $college_info)
                                                                        <option value="{{ $college_info['id'] }}" @if((old('college_name') ? old('college_name') : $plan_info['college_id']) == $college_info['id']) selected @endif> {{ $college_info['name'] }}</option>
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
                                                                <label for="department_name">{{ trans('admin_plan.department_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('department_name') is-invalid @enderror" id="department_name" name="department_name">
                                                                    <option></option>
                                                                    @foreach($department_list as $department_info)
                                                                        <option value="{{ $department_info['id'] }}" @if((old('department_name') ? old('$department_name') : $plan_info['department_id']) == $department_info['id']) selected @endif> {{ $department_info['name'] }}</option>
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
                                                                <label for="major_name">{{ trans('admin_plan.major_name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('major_name') is-invalid @enderror" id="major_name" name="major_name">
                                                                    <option></option>
                                                                    @foreach($major_list as $major_info)
                                                                        <option value="{{ $major_info['id'] }}" @if((old('major_name') ? old('major_name') : $plan_info['major_id']) == $major_info['id']) selected @endif> {{ $major_info['name'] }}</option>
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
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="subject">{{ trans('admin_plan.subject') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <select class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject">
                                                                    <option></option>
                                                                    @foreach($subject_list as $subject_info)
                                                                        <option value="{{ $subject_info['id'] }}" @if((old('subject') ? old('subject') : $plan_info['subject_id']) == $subject_info['id']) selected @endif> {{ $subject_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('subject')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="re_subject">{{ trans('admin_plan.subject') }}</label>
                                                                <select class="form-control @error('re_subject') is-invalid @enderror" id="re_subject" name="re_subject">
                                                                    <option></option>
                                                                    @foreach($subject_list as $subject_info)
                                                                        <option value="{{ $subject_info['id'] }}" @if((old('re_subject') ? old('re_subject') : $plan_info['re_subject_id']) == $subject_info['id']) selected @endif> {{ $subject_info['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('re_subject')
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
                                                                <label for="academic_year">{{ trans('admin_plan.academic_year') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="number" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year') ? old('academic_year') : $plan_info['academic_year_code'] }}" placeholder="{{ trans('admin_plan.academic_year') }}">
                                                                @error('academic_year')
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
                                                                <label for="cost">{{ trans('admin_plan.cost') }}</label>
                                                                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost') ? old('cost') : $plan_info['cost'] }}" placeholder="{{ trans('admin_plan.cost') }}">
                                                                @error('cost')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="recruitment_num">{{ trans('admin_plan.recruitment_num') }}</label>
                                                                <input type="number" class="form-control @error('recruitment_num') is-invalid @enderror" id="recruitment_num" name="recruitment_num" value="{{ old('recruitment_num') ? old('recruitment_num') : $plan_info['recruitment_num'] }}" placeholder="{{ trans('admin_plan.recruitment_num') }}">
                                                                @error('recruitment_num')
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
                                                                <label for="description">{{ trans('admin_plan.description') }}</label>
                                                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="score" name="description"  value="{{ old('description') ? old('description') : $plan_info['description'] }}" placeholder="{{ trans('admin_plan.description') }}">
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
                                                                <label for="status">{{ trans('admin_plan.status') }}<span class="text-danger">&nbsp;*</span></label>
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.plan') }}">{{ trans('button.back') }}</a>
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