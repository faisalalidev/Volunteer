<?php

Route::group(['namespace' => 'Botble\Region\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'regions', 'as' => 'region.'], function () {
            Route::resource('', 'RegionController')->parameters(['' => 'region']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'RegionController@deletes',
                'permission' => 'region.destroy',
            ]);
        });
    });

});
