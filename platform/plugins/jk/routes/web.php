<?php

Route::group(['namespace' => 'Botble\Jk\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'jks', 'as' => 'jk.'], function () {
            Route::resource('', 'JkController')->parameters(['' => 'jk']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'JkController@deletes',
                'permission' => 'jk.destroy',
            ]);
        });
    });

});
