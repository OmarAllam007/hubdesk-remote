<?php

use App\Http\Controllers\FPController;
use App\Letter;
use App\Reports\QueryReport;
use Illuminate\Routing\Router;
use Google\Client;
use Revolution\Google\Sheets\Sheets;

if ($login = env('LOGIN_AS')) {
    $user = \App\User::where('employee_id', $login)->first();
    Auth::login($user);
}


Route::get('google-sheet', function (\Illuminate\Support\Facades\Request $request) {

});


Route::group(['middleware' => ['auth']], function (\Illuminate\Routing\Router $r) {
    Route::get('admin/new_fp', [FPController::class, 'index']);
    Route::post('admin/new_fp/post', [FPController::class, 'postFP'])->name('admin.fp.post');
});

Route::get('/', 'HomeController@home')->middleware('lang');
Route::auth();
Route::get('logout', 'Auth\LoginController@logout');
Route::get('auth/google', 'Auth\AuthController@googleRedirect');
Route::get('auth/google/continue', 'Auth\AuthController@googleHandle');

Route::group(['prefix' => 'dashboard'], function (Router $r) {
    $r->get('select_business_unit', 'DashboardController@selectServiceUnit')
        ->name('dashboard.select_business_unit');
    $r->get('display/{businessUnit}', 'DashboardController@display')
        ->name('dashboard.display');
    $r->get('status-dashboard/{businessUnit}', 'StatusDashboardController@index')->name('dashboard.select_status_dashboard');
    $r->get('status-dashboard-data', 'StatusDashboardController@getData');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/user-information', 'UserController@getUserInformation')->name('user.information');
    Route::get('/user-information-pdf', 'UserController@getSalarySlipPdf')->name('user.salarySlipPdf');
    Route::get('/get-users', 'Admin\UserController@getusers');
    Route::get('/reset_password', 'UserController@getResetForm')->name('user.reset');
    Route::post('/reset_password', 'UserController@resetForm')->name('user.reset');
});

Route::group(['middleware' => ['auth', 'reset']], function () {
    Route::get('/get-users', 'Admin\UserController@getusers');

    Route::group(['prefix' => 'ticket'], function (\Illuminate\Routing\Router $r) {
        $r->get('create-ticket/business-unit/{business_unit}/category/{category}/subcategory/{subcategory?}/item/{item?}/subItem/{subItem?}', 'TicketController@createTicket')
            ->name('ticket.create-ticket');
        $r->get('create-new', 'TicketController@create')->name('ticket.create-wizard');
        $r->get('create-new/business-unit/{business_unit}', 'TicketController@selectCategory')->name('ticket.create.select_category');
        $r->get('create-new/business-unit/{business_unit}/category/{category}', 'TicketController@selectSubcategory')->name('ticket.create.select_subcategory');
        $r->get('create-new/business-unit/{business_unit}/subcategory/{subcategory}', 'TicketController@selectItem')->name('ticket.create.select_item');
        $r->get('create-new/business-unit/{business_unit}/item/{item}', 'TicketController@selectSubItem')->name('ticket.create.select_subItem');
        $r->post('edit-resolution/{ticket}', ['as' => 'ticket.edit-resolution', 'uses' => 'TicketController@editResolution']);
        $r->post('note/{ticket}', ['as' => 'ticket.note', 'uses' => 'TicketNoteController@store']);
        $r->post('note/update/{note}', ['as' => 'note.edit', 'uses' => 'TicketNoteController@update']);
        $r->post('remove-note/{note}', ['as' => 'note.remove', 'uses' => 'TicketNoteController@destroy']);
        $r->post('reply/{ticket}', ['as' => 'ticket.reply', 'uses' => 'API\TicketReplyController@store']);
        $r->post('jump', ['as' => 'ticket.jump', 'uses' => 'TicketController@jump']);
        $r->post('reassign/{ticket}', ['as' => 'ticket.reassign', 'uses' => 'TicketController@reassign']);
        $r->post('scope', ['as' => 'ticket.scope', 'uses' => 'TicketController@scope']);
        $r->get('duplicate/{ticket}', ['as' => 'ticket.duplicate', 'uses' => 'TicketController@duplicate']);
        $r->post('filter', ['as' => 'ticket.filter', 'uses' => 'TicketController@filter']);
        $r->get('clear', ['as' => 'ticket.clear', 'uses' => 'TicketController@clear']);
        $r->get('pickup/{ticket}', ['as' => 'ticket.pickup', 'uses' => 'TicketController@pickupTicket']);
        $r->get('tasks/{ticket}', ['as' => 'tasks.index', 'uses' => 'TaskController@index']);
        $r->get('tasks/edit/{task}', ['as' => 'tasks.edit', 'uses' => 'TaskController@edit']);
        $r->post('tasks/{ticket}', ['as' => 'tasks.store', 'uses' => 'TaskController@store']);
        $r->patch('tasks/{ticket}', ['as' => 'tasks.update', 'uses' => 'TaskController@update']);
        $r->delete('tasks/{task}', ['as' => 'tasks.delete', 'uses' => 'API\TaskController@destroy']);
        $r->get('print/{ticket}', ['as' => 'ticket.print', 'uses' => 'TicketPrintController@show']);
        $r->post('forward/{ticket}', ['as' => 'ticket.forward', 'uses' => 'TicketController@forward']);
        $r->post('complaint/{ticket}', ['as' => 'ticket.complaint', 'uses' => 'TicketController@complaint']);
        $r->post('survey_log/{ticket}/{survey}', ['as' => 'ticket.survey', 'uses' => 'SurveyLogController@update']);

        $r->get('survey/display/{user_survey}', ['as' => 'survey.display', 'uses' => 'SurveyController@displaySurvey']);
        $r->get('user_survey/display/{user_survey}', ['as' => 'user_survey.show', 'uses' => 'SurveyController@displayUserSurvey']);

        $r->get('download-attach/{attachment}', 'TicketController@downloadAttachment')->name('ticket.attachment.download');

        $r->post('resolution/{ticket}',
            ['as' => 'ticket.resolution', 'uses' => 'API\TicketReplyController@resolve']);

        $r->post('post_new_ticket', [\App\Http\Controllers\API\TicketController::class, 'store']);
        $r->post('post_new_experience_cert_ticket', [\App\Http\Controllers\API\ExperienceCertController::class, 'store']);
        $r->post('post_recruitment_requisition_ticket', [\App\Http\Controllers\API\RecruitmentRequisitionController::class, 'store']);
    });


    Route::resource('ticket', 'TicketController');

    Route::group(['prefix' => 'approval'], function (\Illuminate\Routing\Router $r) {
        $r->post('approval/{ticket}', ['as' => 'approval.send', 'uses' => 'ApprovalController@send']);
        $r->get('resend/{ticketApproval}', ['as' => 'approval.resend', 'uses' => 'ApprovalController@resend']);
        $r->get('/{ticketApproval}', ['as' => 'approval.show', 'uses' => 'ApprovalController@show']);
        $r->post('/{ticketApproval}', ['as' => 'approval.update', 'uses' => 'ApprovalController@update']);
        $r->delete('delete/{ticketApproval}', ['as' => 'approval.destroy', 'uses' => 'ApprovalController@destroy']);
    });

    Route::get('/get-tasks/{ticket}', ['as' => 'tasks.ticket', 'uses' => 'TaskController@getTasksOfTicket']);
    Route::resource('task', 'TaskController');

    Route::get('/home', 'HomeController@index');

    Route::get('/custom-fields', 'CustomFieldsController@render');
    Route::get('/custom_fields', 'CustomFieldsController@getFields');
    Route::get('/get-requester-info/{requester}', 'UserController@getUserInfo');

    Route::get('/report', 'ReportController@index');
    Route::get('/report/result', 'ReportController@show');
    Route::post('/report/result', 'ReportController@show');

    Route::get('language/{language}', ['as' => 'site.changeLanguage',
        'uses' => 'HomeController@changeLanguage'])->middleware('lang');

    Route::group(['prefix' => 'configurations'], function (Router $r) {
        $r->get('', 'ConfigurationController@index')->name('configurations.index');
        $r->resource('reply_template', 'ReplyTemplateController');
    });


});

Route::get('inlineimages/{any?}', 'SdpImagesController@redirect')->where('any', '(.*)');

Route::resource('error-log', 'ErrorLogController');
Route::resource('reports', 'ReportsController', ['parameters' => 'singular']);

Route::get('/business-unit', 'BusinessUnitController@index')->name('business-unit.index');
Route::get('/business-unit/{business_unit}', 'BusinessUnitController@show')->name('business-unit.show');
Route::get('/category', 'CategoryController@index')->name('category.index');
Route::get('/category/{category}', 'CategoryController@show')->name('category.show');
Route::get('/subcategory', 'SubcategoryController@index')->name('subcategory.index');
Route::get('/subcategory/{subcategory}', 'SubcategoryController@show')->name('subcategory.show');

Route::post('ajax/ticket', 'API\TicketController@index');
Route::post('ajax/filter-tickets', 'API\TicketController@filterTickets');

require __DIR__ . '/list.php';
require __DIR__ . '/letters.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/reports.php';


Route::get('e-card/admin/show/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'show'])
    ->name('e-card.admin.user.show');
Route::get('e-card/admin/download-card/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'downloadCard'])
    ->name('e-card.admin.user.download');
Route::get('show/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'show'])
    ->name('e-card.admin.user.show');

Route::group(['prefix' => 'e-card/admin', 'middleware' => 'ecard.admin'], function (Router $r) {
    $r->get('index', [\App\Http\Controllers\ECard\Admin\IndexController::class, 'index'])
        ->name('e-card.admin.user.index');
    $r->get('create', [\App\Http\Controllers\ECard\Admin\UserController::class, 'create'])
        ->name('e-card.admin.create');
    $r->post('store', [\App\Http\Controllers\ECard\Admin\UserController::class, 'store'])
        ->name('e-card.admin.store');
    $r->get('edit/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'edit'])
        ->name('e-card.admin.edit');
    $r->patch('update/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'update'])
        ->name('e-card.admin.user.update');
    $r->delete('update/{user}', [\App\Http\Controllers\ECard\Admin\UserController::class, 'destroy'])
        ->name('e-card.admin.user.destroy');
});



