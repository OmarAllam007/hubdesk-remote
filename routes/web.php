<?php

use App\Letter;
use Illuminate\Routing\Router;


if ($login = env('LOGIN_AS')) {
    $user = \App\User::where('employee_id', $login)->first();
    Auth::login($user);
}

Route::get('en/internship-application', 'InternshipController@index')->name('internship.en');
Route::get('ar/internship-application', 'InternshipController@ar_index')->name('internship.ar');
Route::post('internship-application-post', 'InternshipController@apply')
    ->name('internship.post');

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

Route::group(['prefix' => 'list'], function (\Illuminate\Routing\Router $r) {
    $r->get('/subcategory/{cat_id?}', 'ListController@subcategory');
    $r->get('/item/{subcat_id?}', 'ListController@item');
    $r->get('/subitem/{item_id?}', 'ListController@subitem');
    $r->get('/category', 'ListController@category');
    $r->get('/individual-category', 'ListController@individualCategory');
    $r->get('/folders/{business_unit}', 'ListController@folders');
    $r->get('/location', 'ListController@location');
    $r->get('/business-unit', 'ListController@businessUnit');
    $r->get('/priority', 'ListController@priority');
    $r->get('/urgency', 'ListController@urgency');
    $r->get('/impact', 'ListController@impact');
    $r->get('/support-groups', 'ListController@supportGroup');
    $r->get('/technician-groups', 'ListController@technicianGroup');
    $r->get('/technician', 'ListController@technician');
    $r->get('/group-technicians/{group?}', 'ListController@technicians');
    $r->get('/status', 'ListController@status');
    $r->get('/requester', 'ListController@requester');
    $r->get('/group', 'ListController@supportGroup');
    $r->get('/approvers', 'ListController@approvers');
    $r->get('/templates', 'ListController@templates');
    $r->get('/employees', 'ListController@employees');

    $r->get('/list-statuses/{ticket}', 'ListController@getStatus');
    $r->get('/dashboard-business-unit', 'ListController@dashboardBusinessUnit');

//    KGS LIST Routes
    $r->get('kgs_category', 'ListController@kgs_category');
    $r->get('kgs_subcategory', 'ListController@kgs_subcategory');
    $r->get('kgs_item', 'ListController@kgs_item');
    $r->get('kgs_subitem', 'ListController@kgs_subitem');

// Ajax List
    $r->get('replies/{ticket}', 'API\TicketReplyController@index');
    $r->get('approvals/{ticket}', 'API\TicketApprovalController@index');
//    $r->get('/approvers-list', 'ListController@approversList');
    $r->get('/reply-templates', 'ListController@reply_templates');

    $r->get('tasks/{ticket}', 'API\TaskController@index');
    $r->get('logs/{ticket}', 'API\LogController@index');


});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function (\Illuminate\Routing\Router $r) {
    Route::get('question/{survey}', ['uses' => 'QuestionController@index', 'as' => 'question.index']);
    Route::get('question/create/{survey}', ['uses' => 'QuestionController@create', 'as' => 'question.create']);
    Route::get('question/edit/{question}', ['uses' => 'QuestionController@edit', 'as' => 'question.edit']);
    Route::get('question/show/{question}', ['uses' => 'QuestionController@show', 'as' => 'question.show']);
    Route::delete('question/{question}', ['uses' => 'QuestionController@destroy', 'as' => 'question.destroy']);
    Route::patch('question/{question}', ['uses' => 'QuestionController@update', 'as' => 'question.update']);
    Route::post('question/{survey}', ['uses' => 'QuestionController@store', 'as' => 'question.store']);

    Route::get('answer/{question}', ['uses' => 'AnswerController@index', 'as' => 'answer.index']);
    Route::get('answer/create/{question}', ['uses' => 'AnswerController@create', 'as' => 'answer.create']);
    Route::get('answer/edit/{answer}', ['uses' => 'AnswerController@edit', 'as' => 'answer.edit']);
    Route::get('answer/show/{answer}', ['uses' => 'AnswerController@show', 'as' => 'answer.show']);
    Route::delete('answer/{answer}', ['uses' => 'AnswerController@destroy', 'as' => 'answer.destroy']);
    Route::patch('answer/{answer}', ['uses' => 'AnswerController@update', 'as' => 'answer.update']);
    Route::post('answer/{answer}', ['uses' => 'AnswerController@store', 'as' => 'answer.store']);


    $r->get('', 'Admin\DashboardController@index');
    $r->get('/system-admin', 'Admin\DashboardController@systemAdmin')->name('system_admin');

    $r->resource('region', 'Admin\RegionController');
    $r->resource('division', 'Admin\DivisionController');
    $r->resource('city', 'Admin\CityController');
    $r->resource('location', 'Admin\LocationController');
    $r->resource('business-unit', 'Admin\BusinessUnitController');
    $r->resource('branch', 'Admin\BranchController');
    $r->resource('department', 'Admin\DepartmentController');
    $r->resource('category', 'Admin\CategoryController');
    $r->resource('subcategory', 'Admin\SubcategoryController');
    $r->resource('item', 'Admin\ItemController');
    $r->resource('subItem', 'Admin\SubItemController');
    $r->resource('status', 'Admin\StatusController');
    $r->resource('group', 'Admin\GroupController');
    $r->get('user-group', 'Admin\GroupController@userGroups')->name('group.user_groups');
    $r->resource('role', 'Admin\RoleController');
    $r->resource('priority', 'Admin\PriorityController');
    $r->resource('urgency', 'Admin\UrgencyController');
    $r->resource('impact', 'Admin\ImpactController');
    $r->resource('business-rule', 'Admin\BusinessRuleController');
    $r->resource('sla', 'Admin\SlaController');
    $r->resource('user', 'Admin\UserController');
    $r->get('users/upload', 'Admin\UserController@showUploadForm')->name('user.upload');
    $r->post('users/upload', 'Admin\UserController@submitUploadForm')->name('user.submit.upload');
    $r->get('/users/google/sync','Admin\UserController@googleSync')->name('user.google.sync');

    Route::resource('survey', 'SurveyController');


    Route::group(['prefix' => 'group'], function () {
        Route::post('add-user/{group}', ['uses' => 'Admin\GroupController@addUser', 'as' => 'admin.group.add-user']);
        Route::delete('remove-user/{group}/{user}', ['uses' => 'Admin\GroupController@removeUser', 'as' => 'admin.group.remove-user']);
    });

    Route::group(['prefix' => 'role'], function () {
        Route::post('add-user/{role}', ['uses' => 'Admin\roleController@addUser', 'as' => 'admin.role.add-user']);
        Route::delete('remove-user/{role}/{user}', ['uses' => 'Admin\roleController@removeUser', 'as' => 'admin.role.remove-user']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('ldap-import', ['as' => 'user.ldap-import', 'uses' => 'Admin\UserController@ldapImport']);
    });
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

Route::group(['prefix' => 'reports'], function () {
    Route::get('query/create', 'QueryReportController@create')->name('reports.query.create');
    Route::post('query/store', 'QueryReportController@store')->name('reports.query.store');
    Route::get('query/{report}/edit', 'QueryReportController@edit')->name('reports.query.edit');
    Route::post('query/{report}/update', 'QueryReportController@update')->name('reports.query.update');
    Route::get('query/{report}/show', 'QueryReportController@show')->name('reports.query_report.show');

    Route::resource('folder', 'ReportFolderController');

    Route::get('custom_report/create', 'CustomReportController@create')->name('reports.custom_report.create');
    Route::post('custom_report/store', 'CustomReportController@store')->name('reports.custom_report.store');
    Route::get('custom_report/{report}/edit', 'CustomReportController@edit')->name('reports.custom_report.edit');
    Route::post('custom_report/{report}/update', 'CustomReportController@update')->name('reports.custom_report.update');
    Route::get('custom_report/{report}/show', 'CustomReportController@show')->name('reports.custom_report.show');


    Route::get('scheduled_report/all', 'ScheduledReportController@index')->name('reports.scheduled_report.index');
    Route::get('scheduled_report/create', 'ScheduledReportController@create')->name('reports.scheduled_report.create');
    Route::post('scheduled_report/store', 'ScheduledReportController@store')->name('reports.scheduled_report.store');
    Route::get('scheduled_report/{report}/edit', 'ScheduledReportController@edit')->name('reports.scheduled_report.edit');
    Route::post('scheduled_report/{report}/update', 'ScheduledReportController@update')->name('reports.scheduled_report.update');
    Route::get('scheduled_report/{report}/show', 'ScheduledReportController@show')->name('reports.scheduled_report.show');
    Route::post('scheduled_report/{report}/execute', 'ScheduledReportController@execute')->name('reports.scheduled_report.execute');
});

Route::get('/business-unit', 'BusinessUnitController@index')->name('business-unit.index');

Route::get('/business-unit/{business_unit}', 'BusinessUnitController@show')->name('business-unit.show');

Route::get('/category', 'CategoryController@index')->name('category.index');

Route::get('/category/{category}', 'CategoryController@show')->name('category.show');

Route::get('/subcategory', 'SubcategoryController@index')->name('subcategory.index');


Route::get('/subcategory/{subcategory}', 'SubcategoryController@show')->name('subcategory.show');

Route::post('ajax/ticket', 'API\TicketController@index');
Route::post('ajax/filter-tickets', 'API\TicketController@filterTickets');

require __DIR__ . '/letters.php';