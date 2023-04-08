<?php

Route::group(['namespace' => 'Botble\Department\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'departments', 'as' => 'department.'], function () {
            Route::resource('', 'DepartmentController')->parameters(['' => 'department']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'DepartmentController@deletes',
                'permission' => 'department.destroy',
            ]);
        });
    });

});
