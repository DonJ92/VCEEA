@extends('layouts.admin')

@section('title', trans('admin_account.title'))

@section('content')
    <script>
        $(window).on('load', function() {
            $('#sidemenu_admin_list').addClass('active');
            getAccountList();
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                getAccountList();
            }
        });

        function getAccountList() {
            var token = $("input[name=_token]").val();
            var admin_code = $('#admin_code').val();
            var name = $('#name').val();
            var id_code = $('#id_code').val();
            var email = $('#email').val();
            var phone = $('#phone').val();

            $.ajax({
                url: '{{ route('admin.account.getlist') }}',
                type: 'POST',
                data: {_token: token, admin_code: admin_code, name:name, id_code:id_code, email:email, phone:phone},
                dataType: 'JSON',
                success: function (response) {
                    if ( $.fn.DataTable.isDataTable( '#account_list_tbl' ) ) {
                        var account_list_tbl = $('#account_list_tbl').DataTable();
                        account_list_tbl.destroy();
                    }

                    datas = new Array();
                    if (response == undefined || response.length == 0) {
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var num = i + 1;

                            if (response[i].role == '{{ ADMIN_TOP }}')
                                var role = '{{ trans('common.admin_role.top') }}';
                            else
                                var role = '{{ trans('common.admin_role.normal') }}';

                            @if (Auth::user()->role == ADMIN_TOP)
                                var action = '<a class="text-primary" href="{{url('admin/account/edit')}}/' + response[i].id + '">{{ trans('button.edit') }}</a>&nbsp;&nbsp;&nbsp;<a class="text-danger" href="javascript:onDelete(' + response[i].id + ')">{{ trans('button.delete') }}</a>';
                            @else
                                var action = '';
                            @endif

                            datas.push([
                                num,
                                response[i].admin_code,
                                response[i].name,
                                response[i].id_code,
                                response[i].email,
                                response[i].phone,
                                role,
                                action
                            ]);
                        }
                    }

                    $('#account_list_tbl').dataTable({
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

                    $('#account_list_tbl').css('width', '');

                    console.log(response);
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
                        url: '{{ route('admin.account.delete') }}',
                        type: 'POST',
                        data: {_token: token, id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            getAccountList();
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
                            <h5 class="content-header-title float-left pr-1 mb-0">{{ trans('admin_account.title') }}</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">{{ trans('admin_account.header_menu') }}</li>
                                    <li class="breadcrumb-item active">{{ trans('admin_account.title') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with icon tabs section start -->
                <section id="account-search">
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
                                                            <label for="admin_code">{{ trans('admin_account.admin_code') }}</label>
                                                            <input type="text" class="form-control" id="admin_code" placeholder="{{ trans('admin_account.admin_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group">
                                                            <label for="name">{{ trans('admin_account.name') }}</label>
                                                            <input type="text" class="form-control" id="name" placeholder="{{ trans('admin_account.name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="id_code">{{ trans('admin_account.id_code') }}</label>
                                                            <input type="text" class="form-control" id="id_code" placeholder="{{ trans('admin_account.id_code') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="email">{{ trans('admin_account.email') }}</label>
                                                            <input type="text" class="form-control" id="email" placeholder="{{ trans('admin_account.email') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="phone">{{ trans('admin_account.phone') }}</label>
                                                            <input type="text" class="form-control" id="phone" placeholder="{{ trans('admin_account.phone') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 text-center">
                                                        <label for="buttons"></label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" onclick="getAccountList()">{{ trans('button.search') }}</button>
                                                            <button type="button" class="btn btn-dark mr-1 mb-1" onclick="reset()">{{ trans('button.reset') }}</button>
                                                            <a class="btn btn-success mr-1 mb-1" href="{{ route('admin.account.add') }}">{{ trans('button.add') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- body content step 1 end-->
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table" id="account_list_tbl">
                                                <thead class="text-center">
                                                <tr>
                                                    <th>{{ trans('admin_account.no') }}</th>
                                                    <th>{{ trans('admin_account.admin_code') }}</th>
                                                    <th>{{ trans('admin_account.name') }}</th>
                                                    <th>{{ trans('admin_account.id_code') }}</th>
                                                    <th>{{ trans('admin_account.email') }}</th>
                                                    <th>{{ trans('admin_account.phone') }}</th>
                                                    <th>{{ trans('admin_account.role') }}</th>
                                                    <th>{{ trans('common.action') }}</th>
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