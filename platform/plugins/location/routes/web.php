<?php

Route::group(['namespace' => 'Botble\Location\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'locations', 'as' => 'location.'], function () {
            Route::resource('', 'LocationController')->parameters(['' => 'location']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'LocationController@deletes',
                'permission' => 'location.destroy',
            ]);
        });
    });

});
