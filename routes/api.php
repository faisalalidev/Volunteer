<?php

    Route::post('register', 'AuthenticationController@register');
    Route::post('login', 'AuthenticationController@login');
    Route::post('password/forgot', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('resend-verify-account-email', 'VerificationController@resend');
    Route::get('jk', 'DataListController@jkList');
    Route::get('region', 'DataListController@regionList');
    Route::get('department', 'DataListController@departmentList');
    Route::get('location', 'DataListController@locationtList');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('logout', 'AuthenticationController@logout');
        Route::get('me', 'ProfileController@getProfile');
        Route::put('me', 'ProfileController@updateProfile');
        Route::post('update/avatar', 'ProfileController@updateAvatar');
        Route::put('update/password', 'ProfileController@updatePassword');
        Route::post('checkin', 'DataListController@checkin');
        Route::get('get-user', 'DataListController@getUser');
    });

