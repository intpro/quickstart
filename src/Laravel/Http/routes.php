<?php

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function()
{
    Route::get('/create/init/{block}',              ['as' => 'create_init', 'uses' => 'Interpro\QuickStorage\AdminCreateController@createInitBlock']);
    Route::get('/create/init',                      ['as' => 'create_init', 'uses' => 'Interpro\QuickStorage\AdminCreateController@createInit']);

    //Для групп внутри блоков:
    Route::get('/create/groupitem/{block}/{group}/{owner_id}', ['as' => 'create_groupitem', 'uses' => 'Interpro\QuickStorage\AdminCreateController@createGroupItem']);

    //Сохранение
    Route::post('/update/block',                    ['as' => 'update_block',     'uses' => 'Interpro\QuickStorage\AdminUpdateController@updateBlock']);
    Route::post('/update/groupitem',                ['as' => 'update_groupitem', 'uses' => 'Interpro\QuickStorage\AdminUpdateController@updateGroupItem']);

    //Удаление
    Route::delete('/delete/groupitem/{id}',         ['as' => 'delete_groupitem', 'uses' => 'Interpro\QuickStorage\AdminDeleteController@deleteGroupItem']);

});
