<?php

Route::group(['prefix' => 'ticket'], function (\Illuminate\Routing\Router $r) {
    $r->get('', 'API\TicketController@index');
});
