<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteSeradmin.college.addviceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Users\PlanController@index')->name('home');

Auth::routes();

Route::get('/plan', 'Users\PlanController@index')->name('plan');
Route::post('/plan/getlist', 'Users\PlanController@getPlanList')->name('plan.getlist');
Route::post('/plan/follow', 'Users\PlanController@follow')->name('plan.follow');
Route::post('/plan/unfollow', 'Users\PlanController@unfollow')->name('plan.unfollow');

Route::get('/follow', 'Users\FollowController@index')->name('follow');
Route::post('/follow/getlist', 'Users\FollowController@getFollowList')->name('follow.getlist');
Route::post('/follow/getrestriction', 'Users\FollowController@getRestriction')->name('follow.getrestriction');
Route::post('/follow/simulate', 'Users\FollowController@simulate')->name('follow.simulate');

Route::get('/recommend', 'Users\RecommendController@index')->name('recommend');
Route::post('/recommend/getlist', 'Users\RecommendController@getPlanList')->name('recommend.getlist');

Route::get('/simulate', 'Users\SimulateController@index')->name('simulate');
Route::post('/simulate/getlist', 'Users\SimulateController@getSimulateList')->name('simulate.getlist');
Route::post('/simulate/moveup', 'Users\SimulateController@moveUp')->name('simulate.moveup');
Route::post('/simulate/movedown', 'Users\SimulateController@moveDown')->name('simulate.movedown');
Route::post('/simulate/delete', 'Users\SimulateController@delete')->name('simulate.delete');
Route::post('/simulate/deletesimulate', 'Users\SimulateController@deleteSimulate')->name('simulate.deletesimulate');
Route::post('/simulate/export', 'Users\SimulateController@export')->name('simulate.export');

Route::get('/score', 'Users\ScoreController@index')->name('score');
Route::post('/score/getlist', 'Users\ScoreController@getScoreList')->name('score.getlist');

Route::get('/setting', 'Users\SettingController@index')->name('setting');
Route::post('/setting/profile', 'Users\SettingController@updateProfile')->name('setting.profile');
Route::post('/setting/password', 'Users\SettingController@updatePassword')->name('setting.password');

Route::prefix('admin')->group(function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    Route::get('/register', 'Auth\AdminRegisterController@showRegisterForm')->name('admin.register');
    Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');

    Route::get('/', 'Users\Admin\PlanController@index');
    Route::post('/getcitylist', 'Users\Admin\AdminController@getCity')->name('admin.getcitylist');

    Route::get('/college', 'Users\Admin\CollegeController@index')->name('admin.college');
    Route::post('/college/getlist', 'Users\Admin\CollegeController@getCollegeList')->name('admin.college.getlist');
    Route::get('/college/add', 'Users\Admin\CollegeController@add')->name('admin.college.add');
    Route::post('/college/add', 'Users\Admin\CollegeController@addSubmit')->name('admin.college.add.submit');
    Route::get('/college/edit/{id}', 'Users\Admin\CollegeController@edit')->name('admin.college.edit');
    Route::post('/college/edit', 'Users\Admin\CollegeController@editSubmit')->name('admin.college.edit.submit');
    Route::post('/college/delete', 'Users\Admin\CollegeController@delete')->name('admin.college.delete');

    Route::get('/department', 'Users\Admin\DepartmentController@index')->name('admin.department');
    Route::post('/department/getlist', 'Users\Admin\DepartmentController@getDepartmentList')->name('admin.department.getlist');
    Route::get('/department/add', 'Users\Admin\DepartmentController@add')->name('admin.department.add');
    Route::post('/department/add', 'Users\Admin\DepartmentController@addSubmit')->name('admin.department.add.submit');
    Route::get('/department/edit/{id}', 'Users\Admin\DepartmentController@edit')->name('admin.department.edit');
    Route::post('/department/edit', 'Users\Admin\DepartmentController@editSubmit')->name('admin.department.edit.submit');
    Route::post('/department/delete', 'Users\Admin\DepartmentController@delete')->name('admin.department.delete');

    Route::get('/major', 'Users\Admin\MajorController@index')->name('admin.major');
    Route::post('/major/getlist', 'Users\Admin\MajorController@getMajorList')->name('admin.major.getlist');
    Route::get('/major/add', 'Users\Admin\MajorController@add')->name('admin.major.add');
    Route::post('/major/add', 'Users\Admin\MajorController@addSubmit')->name('admin.major.add.submit');
    Route::get('/major/edit/{id}', 'Users\Admin\MajorController@edit')->name('admin.major.edit');
    Route::post('/major/edit', 'Users\Admin\MajorController@editSubmit')->name('admin.major.edit.submit');
    Route::post('/major/delete', 'Users\Admin\MajorController@delete')->name('admin.major.delete');

    Route::get('/subject', 'Users\Admin\SubjectController@index')->name('admin.subject');
    Route::post('/subject/getlist', 'Users\Admin\SubjectController@getSubjectList')->name('admin.subject.getlist');
    Route::get('/subject/add', 'Users\Admin\SubjectController@add')->name('admin.subject.add');
    Route::post('/subject/add', 'Users\Admin\SubjectController@addSubmit')->name('admin.subject.add.submit');
    Route::get('/subject/edit/{id}', 'Users\Admin\SubjectController@edit')->name('admin.subject.edit');
    Route::post('/subject/edit', 'Users\Admin\SubjectController@editSubmit')->name('admin.subject.edit.submit');
    Route::post('/subject/delete', 'Users\Admin\SubjectController@delete')->name('admin.subject.delete');

    Route::get('/batch', 'Users\Admin\BatchController@index')->name('admin.batch');
    Route::post('/batch/getlist', 'Users\Admin\BatchController@getBatchList')->name('admin.batch.getlist');
    Route::get('/batch/add', 'Users\Admin\BatchController@add')->name('admin.batch.add');
    Route::post('/batch/add', 'Users\Admin\BatchController@addSubmit')->name('admin.batch.add.submit');
    Route::get('/batch/edit/{id}', 'Users\Admin\BatchController@edit')->name('admin.batch.edit');
    Route::post('/batch/edit', 'Users\Admin\BatchController@editSubmit')->name('admin.batch.edit.submit');
    Route::post('/batch/delete', 'Users\Admin\BatchController@delete')->name('admin.batch.delete');

    Route::get('/restriction', 'Users\Admin\RestrictionController@index')->name('admin.restriction');
    Route::post('/restriction/getlist', 'Users\Admin\RestrictionController@getRestrictionList')->name('admin.restriction.getlist');
    Route::get('/restriction/add', 'Users\Admin\RestrictionController@add')->name('admin.restriction.add');
    Route::post('/restriction/add', 'Users\Admin\RestrictionController@addSubmit')->name('admin.restriction.add.submit');
    Route::get('/restriction/edit/{id}', 'Users\Admin\RestrictionController@edit')->name('admin.restriction.edit');
    Route::post('/restriction/edit', 'Users\Admin\RestrictionController@editSubmit')->name('admin.restriction.edit.submit');
    Route::post('/restriction/delete', 'Users\Admin\RestrictionController@delete')->name('admin.restriction.delete');

    Route::get('/plan', 'Users\Admin\PlanController@index')->name('admin.plan');
    Route::post('/plan/getlist', 'Users\Admin\PlanController@getPlanList')->name('admin.plan.getlist');
    Route::get('/plan/add', 'Users\Admin\PlanController@add')->name('admin.plan.add');
    Route::post('/plan/add', 'Users\Admin\PlanController@addSubmit')->name('admin.plan.add.submit');
    Route::get('/plan/edit/{id}', 'Users\Admin\PlanController@edit')->name('admin.plan.edit');
    Route::post('/plan/edit', 'Users\Admin\PlanController@editSubmit')->name('admin.plan.edit.submit');
    Route::post('/plan/delete', 'Users\Admin\PlanController@delete')->name('admin.plan.delete');

    Route::get('/student', 'Users\Admin\StudentController@index')->name('admin.student');
    Route::post('/student/getlist', 'Users\Admin\StudentController@getStudentList')->name('admin.student.getlist');

    Route::get('/score', 'Users\Admin\ScoreController@index')->name('admin.score');
    Route::post('/score/getlist', 'Users\Admin\ScoreController@getScoreList')->name('admin.score.getlist');
    Route::get('/score/add', 'Users\Admin\ScoreController@add')->name('admin.score.add');
    Route::post('/score/add', 'Users\Admin\ScoreController@addSubmit')->name('admin.score.add.submit');
    Route::get('/score/edit/{id}', 'Users\Admin\ScoreController@edit')->name('admin.score.edit');
    Route::post('/score/edit', 'Users\Admin\ScoreController@editSubmit')->name('admin.score.edit.submit');
    Route::post('/score/delete', 'Users\Admin\ScoreController@delete')->name('admin.score.delete');

    Route::get('/log', 'Users\Admin\LogController@index')->name('admin.log');
    Route::post('/log/getlist', 'Users\Admin\LogController@getLogList')->name('admin.log.getlist');

    Route::get('/account', 'Users\Admin\AccountController@index')->name('admin.account');
    Route::post('/account/getlist', 'Users\Admin\AccountController@getAccountList')->name('admin.account.getlist');
    Route::get('/account/add', 'Users\Admin\AccountController@add')->name('admin.account.add');
    Route::post('/account/add', 'Users\Admin\AccountController@addSubmit')->name('admin.account.add.submit');
    Route::get('/account/edit/{id}', 'Users\Admin\AccountController@edit')->name('admin.account.edit');
    Route::post('/account/edit', 'Users\Admin\AccountController@editSubmit')->name('admin.account.edit.submit');
    Route::post('/account/delete', 'Users\Admin\AccountController@delete')->name('admin.account.delete');

    Route::get('/setting', 'Users\Admin\SettingController@index')->name('admin.setting');
    Route::post('/setting/profile', 'Users\Admin\SettingController@updateProfile')->name('admin.setting.profile');
    Route::post('/setting/password', 'Users\Admin\SettingController@updatePassword')->name('admin.setting.password');
});
