<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="loading" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name', '辽宁省高考志愿辅助系统') }}">
    <meta name="keywords" content="{{ config('app.name', '辽宁省高考志愿辅助系统') }}">
    <title>{{ config('app.name', '辽宁省高考志愿辅助系统') }} - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/wizard.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <!-- END: Custom CSS-->
</head>
<body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">

<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/scripts/configs/vertical-menu-dark.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/footer.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<!-- END: Page JS-->

<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                    </ul>
                    <h4 class="text-bold-600 brand-text mb-0">高考志愿辅助系统（管理者）</h4>
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="{{ route('home') }}"><i class="ficon bx bx-user-pin"></i>&nbsp;{{ trans('common.user_site') }}</a></li>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link nav-link-expand" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span class="user-name"><i class="ficon bx bx-user-circle"></i>&nbsp;{{ Auth::user()->name }}</span></div><span><i class="bx bxs-down-arrow"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="{{ route('admin.setting') }}"><i class="bx bx-wrench mr-50"></i> {{trans('common.admin_side_menu.setting')}}</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item" href="" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off mr-50"></i> {{trans('common.logout')}}</a>
                        </div>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('admin.plan') }}">
                    <div class="brand-logo"><img class="logo" src="{{ asset('app-assets/images/logo/logo.png') }}" /></div>
                    <h2 class="text-bold-600 brand-text mb-0">辽宁省</h2>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class=" navigation-header"><span>{{trans('common.admin_side_menu.basic_info_group')}}</span></li>
            <li id="sidemenu_college" class=" nav-item"><a href="{{ route('admin.college') }}"><i class="bx bxs-school"></i><span class="menu-title" data-i18n="college">{{trans('common.admin_side_menu.college')}}</span></a></li>
            <li id="sidemenu_department" class=" nav-item"><a href="{{ route('admin.department') }}"><i class="bx bxs-graduation"></i><span class="menu-title" data-i18n="department">{{trans('common.admin_side_menu.department')}}</span></a></li>
            <li id="sidemenu_major" class=" nav-item"><a href="{{ route('admin.major') }}"><i class="bx bxs-book"></i><span class="menu-title" data-i18n="major">{{trans('common.admin_side_menu.major')}}</span></a></li>
            <li id="sidemenu_subject" class=" nav-item"><a href="{{ route('admin.subject') }}"><i class="bx bxs-book-open"></i><span class="menu-title" data-i18n="subject">{{trans('common.admin_side_menu.subject')}}</span></a></li>
            <li class=" nav-item"><hr style="border-color: #2b3754"></li>
            <li class=" navigation-header"><span>{{trans('common.admin_side_menu.voluntary_management_group')}}</span></li>
            <li id="sidemenu_batch" class=" nav-item"><a href="{{ route('admin.batch') }}"><i class="bx bx-collection"></i><span class="menu-title" data-i18n="batch">{{trans('common.admin_side_menu.batch')}}</span></a></li>
            <li id="sidemenu_restriction" class=" nav-item"><a href="{{ route('admin.restriction') }}"><i class="bx bx-error"></i><span class="menu-title" data-i18n="restriction">{{trans('common.admin_side_menu.restriction')}}</span></a></li>
            <li id="sidemenu_plan" class=" nav-item"><a href="{{ route('admin.plan') }}"><i class="bx bxs-book-content"></i><span class="menu-title" data-i18n="plan">{{trans('common.admin_side_menu.plan')}}</span></a></li>
            <li class=" nav-item"><hr style="border-color: #2b3754"></li>
            <li id="sidemenu_user" class=" nav-item"><a href="{{ route('admin.student') }}"><i class="bx bxs-user-detail"></i><span class="menu-title" data-i18n="user">{{trans('common.admin_side_menu.user')}}</span></a></li>
            <li class=" nav-item"><hr style="border-color: #2b3754"></li>
            <li id="sidemenu_score_history" class=" nav-item"><a href="{{ route('admin.score') }}"><i class="bx bx-history"></i><span class="menu-title" data-i18n="score_history">{{trans('common.admin_side_menu.score_history')}}</span></a></li>
            <li class=" nav-item"><hr style="border-color: #2b3754"></li>
            <li id="sidemenu_log" class=" nav-item"><a href="{{ route('admin.log') }}"><i class="bx bx-notepad"></i><span class="menu-title" data-i18n="log">{{trans('common.admin_side_menu.log')}}</span></a></li>
            <li class=" nav-item"><hr style="border-color: #2b3754"></li>
            <li class=" navigation-header"><span>{{trans('common.admin_side_menu.system_setting_group')}}</span></li>
            <li id="sidemenu_admin_list" class=" nav-item"><a href="{{ route('admin.account') }}"><i class="bx bx-group"></i><span class="menu-title" data-i18n="admin_list">{{trans('common.admin_side_menu.admin_list')}}</span></a></li>
            <li id="sidemenu_setting" class=" nav-item"><a href="{{ route('admin.setting') }}"><i class="bx bx-wrench"></i><span class="menu-title" data-i18n="setting">{{trans('common.admin_side_menu.setting')}}</span></a></li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
@yield('content')
</body>
</html>