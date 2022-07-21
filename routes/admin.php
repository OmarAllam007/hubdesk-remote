<?php


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
    $r->get('/users/google/sync', 'Admin\UserController@googleSync')->name('user.google.sync');

    $r->get('/users/labour-office/upload', 'Admin\UserController@showLabourOfficeUsersUploadForm')->name('user.labour_office.upload');
    $r->post('users/labour/upload', 'Admin\UserController@submitLabourOfficeUpload')->name('user.labour.submit.upload');

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


    Route::get('/sap-user/{id}', function ($id) {
        if (auth()->id() == 1021) {
            $user = \App\User::where('employee_id', $id)->first();
            if ($user) {
                $user = \App\User::where('employee_id', $user->employee_id)->first();
                $sapApi = new \App\Helpers\SapApi($user);
                $sapApi->getUserInformation();
                return $sapApi->sapUser->getEmployeeSapInformation();
            } else {
                return 'User not registered in system';
            }
        }else{
            return 'Not allowed to view this page';
        }


    });
});
