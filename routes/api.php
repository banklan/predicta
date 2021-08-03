<?php

use Illuminate\Http\Request;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//general routes
Route::group(['middleware' => 'api'], function ($router) {
    Route::get('countries', 'CountryController@getAll');
    Route::get('leagues', 'LeagueController@getAll');
    Route::get('leagues_by_country/{country}', 'LeagueController@getLeaguesByCountry');
    Route::get('teams_by_league/{id}', 'TeamController@getTeamsByLeague');
    Route::get('get_all_tips', 'TipController@getAllTips');
    Route::get('get_odd_types', 'TipController@getOddTypes');
    Route::get('get_all_intnl_competitions', 'LeagueController@getAllIntnlCompetitions');
    Route::get('get_all_national_teams', 'TeamController@getAllNatnlTeams');
    Route::get('get_all_banks', 'CountryController@getAllBanks');
    Route::get('get_all_bookmakers', 'TipController@getAllBookmakers');
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

    Route::get('get_all_banks', 'AdminController@getAllBanks');
    Route::get('get_paginated_experts', 'AdminController@getPgntdExperts');
    Route::post('expert_create', 'AdminController@createNewExpert');
    Route::get('admin_get_expert/{id}', 'AdminController@getExpert');
    Route::post('admin_update_expert/{id}', 'AdminController@updateExpert');
    Route::post('toggle_expert_status/{id}', 'AdminController@toggleExpertStatus');
    Route::post('admin_del_expert/{id}', 'AdminController@deleteExpert');
    Route::post('change_expert_password/{id}', 'AdminController@changeExpertPswd');
    Route::get('get_expert_forecast_summary/{id}', 'AdminController@getExpertForecastSummary');
    Route::get('admin_get_expert_forecasts/{id}', 'AdminController@adminGetExpertForecasts');
    Route::get('admin_get_single_expert_forecast/{id}', 'AdminController@adminGetSingleExpertForecasts');
    Route::get('admin_get_expert_event/{id}', 'AdminController@adminGetExpertEvent');
    Route::post('change_event_status/{id}', 'AdminController@adminChangeExpertEventStatus');
    Route::get('admin_get_expert_forecast_summary/{id}', 'AdminController@adminGetExpertforecastSummary');
    Route::get('admin_get_paginated_forecasts', 'AdminController@adminGetPgntdExpertforecasts');
    Route::post('admin_del_expert_forecast/{id}', 'AdminController@adminDeleteExpertForecast');
    Route::get('get_paginated_countries', 'AdminController@getPgntdCountries');
    Route::post('create_country', 'AdminController@createCountry');
    Route::post('update_country/{id}', 'AdminController@updateCountry');
    Route::post('delete_country/{id}', 'AdminController@deleteCountry');
    Route::post('delete_bank/{id}', 'AdminController@deleteBank');
    Route::post('update_bank/{id}', 'AdminController@updateBank');
    Route::post('create_new_bank', 'AdminController@createBank');
    Route::get('get_all_countries', 'AdminController@getAllCountries');
    // Route::get('get_paginated_leagues', 'AdminController@getPgntdLeagues');
    Route::post('delete_league/{id}', 'AdminController@delLeague');
    Route::post('update_league/{id}', 'AdminController@updateLeague');
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
    Route::get('get_pngtd_expert_summaries', 'ExpertController@getPgntdForecastSummaries');
    Route::get('get_expert_forecast_summary/{id}', 'ExpertController@getExpertSummary');
    Route::get('get_expert_forecasts/{id}', 'ExpertController@getExpertForecasts');
    Route::post('update_profile', 'ExpertController@UpdateProfile');
    Route::post('update_profile_picture', 'ExpertController@UpdateProfilePic');
    Route::post('confirm_current_pswd', 'ExpertController@confirmCurrentPassword');
    Route::post('update_profile_password', 'ExpertController@updateAccountPassword');
    Route::post('add_bank_details', 'ExpertController@addBankDetails');
    Route::get('get_expert_bank_details', 'ExpertController@getBankDetails');
    Route::post('update_expert_bank_details', 'ExpertController@updateExpertBankDetails');
    Route::get('get_forecast_summary', 'ExpertController@getAtAglance');
});


