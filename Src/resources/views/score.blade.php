@extends('layouts.app')

@section('title', trans('score.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_score').addClass('active');
            getScoreList();

            var currentYear = (new Date).getFullYear();

            for (var i = 0; i < 20; i++)
                $("#year").append(new Option(currentYear - i, currentYear - i));
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getScoreList();
            }
        });

        function getScoreList() {
            var token = $("input[name=_token]").val();
            var batch_id = $('#batch_title').val();
            var year = $('#year').val();
            var college_id = $('#college_name').val();
            var department_id = $('#department_name').val();
            var major_id = $('#major_name').val();
            var score = $('#score').val();
            var score_compare = $('#score_compare').val();
            var min = $('#min').val();
            var min_compare = $('#min_compare').val();
            var max = $('#max').val();
            var max_compare = $('#max_compare').val();
            var average = $('#average').val();
            var average_compare = $('#average_compare').val();

            $.ajax({
                url: '{{ route('score.getlist') }}',
                type: 'POST',
                data: {_token: token, batch_id: batch_id, year:year, college_id:college_id, department_id:department_id, major_id:major_id,
                    score:score, score_compare:score_compare, min:min, min_compare:min_compare, max:max, max_compare:max_compare, average:average, average_compare:average_compare,
                },
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#score_list_tbl' ) ) {
                        var score_list_tbl = $('#score_list_tbl').DataTable();
                        score_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;

                            if (response[i].college_url == null || response[i].college_url == "")
                                var college_name = response[i].college_name;
                            else
                                var college_name = '<a target="_blank" href="'+response[i].college_url+'">'+response[i].college_name+'</a>';

                            datas.push([
                                num,
                                response[i].batch_title,
                                response[i].batch_code,
                                response[i].year,
                                college_name,
                                response[i].department_name,
                                response[i].major_name,
                                response[i].score,
                                response[i].ranking,
                                response[i].max,
                                response[i].min,
                                response[i].average,
                            ]);
                        }
                    }

                    $('#score_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        "order": [[ 0, "asc" ]],
                        "bLengthChange": false,
                        "bFilter": false,
                        "info": false,
                        "language": {
                            "emptyTable": "{{ trans('common.no_data') }}",
                            "paginate": {
                                "previous": "{{ trans('common.previous') }}",
                                "next": "{{ trans('common.next') }}"
                            }
                        }
                    });

                    $('#score_list_tbl').css('width', '');
                }
            });
        }

        function onDelete(id) {
            Swal.fire({
                title: '{{ trans('common.delete_confirm') }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans('button.yes') }}',
                cancelButtonText: '{{ trans('button.no') }}',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                var token = $("input[name=_token]").val();

                if (result.value) {
                    $.ajax({
                        url: '{{ route('admin.score.delete') }}',
                        type: 'POST',
                        data: {_token: token, id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            getScoreList();
                        }
                    });
                }
            })
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('score.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item active">{{ trans('score.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="score-search">
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
                                        <form action="#" class="wizard-horizontal">
                                        @csrf
                                        <!-- body content step 1 -->
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="batch_title">{{ trans('score.batch_title') }}</label>
                                                            <select class="form-control" id="batch_title">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($batch_list as $batch_info)
                                                                    <option value="{{ $batch_info['id'] }}"> {{ $batch_info['title'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="year">{{ trans('score.year') }}</label>
                                                            <select class="form-control" id="year">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="college_name">{{ trans('score.college_name') }}</label>
                                                            <select class="form-control" id="college_name">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($college_list as $college_info)
                                                                    <option value="{{ $college_info['id'] }}"> {{ $college_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="department_name">{{ trans('score.department_name') }}</label>
                                                            <select class="form-control" id="department_name">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($department_list as $department_info)
                                                                    <option value="{{ $department_info['id'] }}"> {{ $department_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="major_name">{{ trans('score.major_name') }}</label>
                                                            <select class="form-control" id="major_name">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                @foreach($major_list as $major_info)
                                                                    <option value="{{ $major_info['id'] }}"> {{ $major_info['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="score">{{ trans('score.score') }}</label>
                                                            <div class="col-md-12 row">
                                                                <input type="number" class="form-control col-md-6" id="score" placeholder="{{ trans('score.score') }}">
                                                                &nbsp;
                                                                <select class="form-control col-md-5" id="score_compare">
                                                                    <option value="{{ MORE }}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{ LESS }}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="max">{{ trans('score.max') }}</label>
                                                            <div class="col-md-12 row">
                                                                <input type="number" class="form-control col-md-6" id="max" placeholder="{{ trans('score.max') }}">
                                                                &nbsp;
                                                                <select class="form-control col-md-5" id="max_compare">
                                                                    <option value="{{ MORE }}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{ LESS }}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="min">{{ trans('score.min') }}</label>
                                                            <div class="col-md-12 row">
                                                                <input type="number" class="form-control col-md-6" id="min" placeholder="{{ trans('score.min') }}">
                                                                &nbsp;
                                                                <select class="form-control col-md-5" id="min_compare">
                                                                    <option value="{{ MORE }}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{ LESS }}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="average">{{ trans('score.average') }}</label>
                                                            <div class="col-md-12 row">
                                                                <input type="number" class="form-control col-md-6" id="average" placeholder="{{ trans('score.average') }}">
                                                                &nbsp;
                                                                <select class="form-control col-md-5" id="average_compare">
                                                                    <option value="{{ MORE }}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{ LESS }}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getScoreList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="score_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('score.no') }}</th>
                                                    <th>{{ trans('score.batch_title') }}</th>
                                                    <th>{{ trans('score.batch_code') }}</th>
                                                    <th>{{ trans('score.year') }}</th>
                                                    <th>{{ trans('score.college_name') }}</th>
                                                    <th>{{ trans('score.department_name') }}</th>
                                                    <th>{{ trans('score.major_name') }}</th>
                                                    <th>{{ trans('score.score') }}</th>
                                                    <th>{{ trans('score.ranking') }}</th>
                                                    <th>{{ trans('score.max') }}</th>
                                                    <th>{{ trans('score.min') }}</th>
                                                    <th>{{ trans('score.average') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                </tbody>
                                            </table>
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