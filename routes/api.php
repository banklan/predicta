<?php

use Illuminate\Http\Request;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//normal routes
Route::group(['middleware' => 'api'], function ($router) {
    Route::get('countries', 'CountryController@getAll');
    Route::get('leagues', 'LeagueController@getAll');
    Route::get('leagues_by_country/{country}', 'LeagueController@getLeaguesByCountry');
    Route::get('teams_by_league/{id}', 'TeamController@getTeamsByLeague');
    Route::get('get_all_tips', 'TipController@getAllTips');
    Route::get('get_odd_types', 'TipController@getOddTypes');
    Route::get('get_all_intnl_competitions', 'LeagueController@getAllIntnlCompetitions');
    Route::get('get_all_national_teams', 'TeamController@getAllNatnlTeams');
});

Route::group(['middleware' => 'api',  'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


//admin routes
Route::group(['middleware' => 'assign.guard:admin-api', 'prefix' => 'auth-admin'], function ($router) {
    Route::post('login', 'AdminAuthController@login');
});

Route::group(['prefix' => 'auth-admin', 'middleware' => ['assign.guard:admin-api', 'jwt.auth']], function($router){
    Route::get('all_superusers', 'AdminController@getAllSuperUsers');
    Route::get('get_super_user/{id}', 'AdminController@getSuperUser');
    Route::post('super_user/{id}/update', 'AdminController@updateSuperUser');
    Route::post('super_user/{id}/delete', 'AdminController@deleteSuperUser');
    Route::post('change_superuser_status/{id}', 'AdminController@toggleSuperUserStatus');
    Route::post('change_superuser_password/{id}', 'AdminController@updateSuperUserPassword');
    Route::post('super_user_create', 'AdminController@createSuperUser');

    Route::post('logout', 'AdminAuthController@logout');
});


// expert routes
Route::group(['middleware' => 'assign.guard:expert-api', 'prefix' => 'auth-expert'], function ($router) {
    Route::post('login', 'ExpertAuthController@login');
    Route::post('register', 'ExpertAuthController@register');
    Route::post('confirm_email', 'ExpertController@confirmEmail');
});

Route::group(['middleware' => ['assign.guard:expert-api', 'jwt.auth'], 'prefix' => 'auth-expert'], function($router){
    Route::post('logout', 'ExpertAuthController@logout');
    Route::post('submit_expert_prediction', 'ExpertController@postPrediction');
    Route::get('get_expert_summaries', 'ExpertController@getAllForecastSummaries');
});


