<?php

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function()
{
    /**
     * Создает записи каждому блоку и записи для каждого поля блока (группы вообще не трогает)
     */
    Route::get('/init',                         ['as' => 'create_init', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@createInit']);

    /**
     * То же самое что и '/init', только для одного блока
     */
    Route::get('/init/{block}',                 ['as' => 'create_init', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@createInitBlock']);

    /**
     * Реинит блока делается если поля блока изменились в конфиге.
     * Реинит группы деляется если поля группы изменились в конфиге и происходит для каждого элемента группы
     *
     * Если блок отсутствует, то создает его и
     * поля для него (группы не трогает)
     */
    Route::get('/reinit_block/{block}',         ['as' => 'ri_block',    'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@reinitBlock']);

    /**
     * Создает записи полей (если нет) для элементов группы (блоки не трогает)
     */
    Route::get('/reinit_group/{block}/{group}', ['as' => 'ri_group',    'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@reinitGroup']);




    //Для групп внутри блоков:
    Route::get('/create/groupitem/{block}/{group}/{owner_id}', ['as' => 'create_groupitem', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@createGroupItem']);

    //Сохранение
    Route::post('/update/block',                    ['as' => 'update_block',     'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminUpdateController@updateBlock']);
    Route::post('/update/groupitem',                ['as' => 'update_groupitem', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminUpdateController@updateGroupItem']);

    Route::post('/create_group_image',              ['as' => 'c_gimg_item', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCreateController@createGroupImageItem']);

    //Удаление
    Route::delete('/delete/groupitem/{id}',         ['as' => 'delete_groupitem', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminDeleteController@deleteGroupItem']);


    //Картинки
    Route::post('/update_block_image',              ['as' => 'u_b_img', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@updateBlockImage']);
    Route::post('/update_group_image',              ['as' => 'u_g_img', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@updateGroupImage']);
    Route::post('/refresh_block_image',             ['as' => 'r_b_img', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@refreshBlockImage']);
    Route::post('/refresh_group_image',             ['as' => 'r_g_img', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@refreshGroupImage']);

    Route::post('/update_group_image_mass',         ['as' => 'u_g_img_m', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@updateGroupImageMass']);
    Route::post('/refresh_group_image_mass',        ['as' => 'r_g_img_m', 'uses' => 'Interpro\QuickStorage\Laravel\Http\ImageFileController@refreshGroupImageMass']);

    Route::post('/crop_group_image',                ['as' => 'cr_g_img', 'uses' => 'Interpro\QuickStorage\Laravel\Http\AdminCropImageController@cropGroupImage']);
});
