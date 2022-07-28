<?php



Route::group(['prefix' => 'list'], function (\Illuminate\Routing\Router $r) {
    Route::get('/sap-info','ListController@getSAPUser');

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
    $r->get('fields', 'ListController@ticket_fields');

});
