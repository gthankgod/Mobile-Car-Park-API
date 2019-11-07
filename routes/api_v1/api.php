<?php

use Illuminate\Support\Facades\Route;

/*
 * API V1 Routes
 */

Route::prefix('auth')->namespace('Auth')->group(function () {
    Route::post('phone-registration-status', 'RegistrationStatusController@withPhone');
    Route::post('request-otp', 'OTPController@send');
    Route::post('verify-otp', 'OTPController@verify');
    Route::prefix('register')->group( function () {
        Route::post('admin', 'RegisterController@admin')->middleware('admin');
        Route::post('partner', 'RegisterController@partner');
        Route::post('user', 'RegisterController@user');
    });

    Route::prefix('login')->group( function () {
        Route::post('/', 'LoginController@adminAndPartner');
        Route::post('user', 'LoginController@userWithPhone');
        Route::post('user-email', 'LoginController@UserWthEmail');
        Route::post('facebook', 'FacebookLoginController');
    });
});

Route::prefix('user')->middleware('auth')->group( function () {
    Route::get('/', 'UserProfileController@show');
    Route::put('/', 'UserProfileController@update');
    Route::patch('/settings', 'UserProfileController@manageProfile');
});

Route::prefix('vehicles')->middleware('auth')->group( function () {
    Route::get('/', 'VehiclesController@index');
    Route::post('/', 'VehiclesController@store');
    Route::put('{id}', 'VehiclesController@update');
    Route::delete('{id}', 'VehiclesController@delete');
});

Route::group(['prefix' => 'park', 'middleware' => 'isuser'], function () {
    Route::group(['prefix' => 'sa', 'middleware' => 'superAdmin'], function () {
        Route::get('/', function(){});
        Route::get('inactive', 'CarParkController@showSuperInActive');
        Route::get('bookings', 'CarParkBookingController@showSuperBookings');
        Route::get('bookings/current', 'CarParkBookingController@superCurrent');
        Route::get('bookings/history', 'CarParkBookingController@superHistory');
        Route::get('bookings/{park_id}', 'CarParkBookingController@showSuperSingleBooking');
    });
    
    Route::group(['middleware' => 'admin'], function () {
        Route::post('/', 'CarParkController@store');
        Route::put('{id}', 'CarParkController@update');
        Route::get('users/{park_id}', 'CarParkBookingController@getUsers');
        Route::get('bookings/{park_id}', 'CarParkBookingController@carParksBooking');
        Route::delete('revoke-booking/{booking_id}', 'CarParkBookingController@revoke');
        Route::get('activated-parks', 'CarParkController@showAdminActive');
        Route::get('deactivated-parks', 'CarParkController@showInActive');
        Route::patch('set-active/{park_id}', 'CarParkController@activate');
        Route::patch('set-inactive/{park_id}', 'CarParkController@deactivate');
        Route::delete('{id}', 'CarParkController@delete');
        Route::get('booking/current/{park_id}', 'CarParkBookingController@carParkCurrent');
        Route::get('booking/history/{park_id}', 'CarParkBookingController@carParkHistory');
    });
    
    Route::get('booking/my-history', 'CarParkBookingController@carParkHistory');
    Route::get('booking/my-current', 'CarParkBookingController@carParkCurrent');
    Route::post('book/{park_id}', 'CarParkBookingController');
    Route::put('book/{booking_id}', 'CarParkBookingController@update');
    Route::get('active', 'CarParkController@showActive');
    Route::get('/', 'CarParkController@apiIndex');
    Route::get('all', 'CarParkController@index');
    Route::get('{id}', 'CarParkController@show');
});


Route::prefix('users')->middleware('admin')->group(function () {
    Route::get('/', 'AdminUsersController@index');
    Route::get('{user_id}', 'AdminUsersController@show');
});
