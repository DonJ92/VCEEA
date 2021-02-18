@extends('layouts.admin')

@section('title', trans('admin_restriction.add_title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_restriction').addClass('active');
        });
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_restriction.add_title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_restriction.header_menu') }}</li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.restriction') }}">{{ trans('admin_restriction.title') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('admin_restriction.add_title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="restriction-add-form">
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
                                        <form method="post" action="{{ route('admin.restriction.add.submit') }}" class="wizard-horizontal">
                                            @csrf

                                            <fieldset>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="name">{{ trans('admin_restriction.name') }}<span class="text-danger">&nbsp;*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="{{ trans('admin_restriction.name') }}">
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
                                                                <label for="batch">{{ trans('admin_restriction.batch') }}<span class="text-danger">&nbsp;*</span>&nbsp;&nbsp;&nbsp;<span>{{ trans('admin_restriction.desc_01') }}</span></label>
                                                                <select class="form-control @error('batch') is-invalid @enderror" style="height: 300px" multiple="multiple" id="batch" name="batch[]">
                                                                    @foreach($batch_list as $batch_info)
                                                                        <option value="{{ $batch_info['id'] }}" @if(!empty(old('batch')) && in_array($batch_info['id'], old('batch'))) selected @endif  > {{ $batch_info['title'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('batch')
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
                                                                <label for="description">{{ trans('admin_restriction.description') }}</label>
                                                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" placeholder="{{ trans('admin_restriction.description') }}">
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
                                                                <label for="status">{{ trans('admin_restriction.status') }}<span class="text-danger">&nbsp;*</span></label>
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
                                                                <a class="btn btn-dark mr-1 mb-1" href="{{ route('admin.restriction') }}">{{ trans('button.back') }}</a>
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