@extends('layouts.admin')

@section('title', trans('admin_student.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_user').addClass('active');
            getStudentList();
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getStudentList();
            }
        });

        function getStudentList() {
            var token = $("input[name=_token]").val();
            var user_code = $('#user_code').val();
            var name = $('#name').val();
            var gender = $('#gender').val();
            var birthday = $('#birthday').val();
            var id_code = $('#id_code').val();
            var email = $('#email').val();
            var phone = $('#phone').val();

            $.ajax({
                url: '{{ route('admin.student.getlist') }}',
                type: 'POST',
                data: {_token: token, user_code:user_code, name: name, gender:gender, birthday:birthday, id_code:id_code, email:email, phone:phone},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#student_list_tbl' ) ) {
                        var student_list_tbl = $('#student_list_tbl').DataTable();
                        student_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;

                            if (response[i].gender == '{{ FEMALE }}')
                                var gender = '{{ trans('constant.female') }}';
                            else
                                var gender = '{{ trans('constant.male') }}';

                            datas.push([
                                num,
                                response[i].user_code,
                                response[i].name,
                                gender,
                                formatDate(response[i].birthday),
                                response[i].id_code,
                                response[i].email,
                                response[i].phone,
                            ]);
                        }
                    }

                    $('#student_list_tbl').dataTable({
                        data: datas,
                        pageLength: 10,
                        "order": [[ 0, "asc" ]],
                        "bLengthChange": false,
                        "bFilter": false,
                        "info": false,
                        "autoWidth": true,
                        "language": {
                            "emptyTable": "{{ trans('common.no_data') }}",
                            "paginate": {
                                "previous": "{{ trans('common.previous') }}",
                                "next": "{{ trans('common.next') }}"
                            }
                        }
                    });

                    $('#student_list_tbl').css('width', '');

                    console.log(response);
                }
            });
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
    </script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_student.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item active">{{ trans('admin_student.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="student-search">
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
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="user_code">{{ trans('admin_student.user_code') }}</label>
                                                            <input type="text" class="form-control" id="user_code" placeholder="{{ trans('admin_student.user_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="name">{{ trans('admin_student.name') }}</label>
                                                            <input type="text" class="form-control" id="name" placeholder="{{ trans('admin_student.name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="gender">{{ trans('admin_student.gender') }}</label>
                                                            <select class="form-control" id="gender">
                                                                <option value="">{{ trans('common.all') }}</option>
                                                                <option value="{{ MALE }}">{{ trans('constant.male') }}</option>
                                                                <option value="{{ FEMALE }}">{{ trans('constant.female') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="birthday">{{ trans('admin_student.birthday') }}</label>
                                                            <div class="col-lg-12 row">
                                                                <input type="date" class="form-control col-sm-7" id="birthday" placeholder="{{ trans('admin_student.birthday') }}">
                                                                &nbsp;
                                                                <select class="form-control col-sm-4" id="birthday_compare">
                                                                    <option value="{{ MORE }}">{{trans('constant.compare.more')}}</option>
                                                                    <option value="{{ LESS }}">{{trans('constant.compare.less')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="id_code">{{ trans('admin_student.id_code') }}</label>
                                                            <input type="text" class="form-control" id="id_code" placeholder="{{ trans('admin_student.id_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="email">{{ trans('admin_student.email') }}</label>
                                                            <input type="text" class="form-control" id="email" placeholder="{{ trans('admin_student.email') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="phone">{{ trans('admin_student.phone') }}</label>
                                                            <input type="text" class="form-control" id="phone" placeholder="{{ trans('admin_student.phone') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getStudentList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="student_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('admin_student.no') }}</th>
                                                    <th>{{ trans('admin_student.user_code') }}</th>
                                                    <th>{{ trans('admin_student.name') }}</th>
                                                    <th>{{ trans('admin_student.gender') }}</th>
                                                    <th>{{ trans('admin_student.birthday') }}</th>
                                                    <th>{{ trans('admin_student.id_code') }}</th>
                                                    <th>{{ trans('admin_student.email') }}</th>
                                                    <th>{{ trans('admin_student.phone') }}</th>
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