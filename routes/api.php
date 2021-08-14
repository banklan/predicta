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
    Route::get('get_featured_daily_tips', 'TipController@getFeaturedDailyTips');
    Route::get('get_won_daily_tips', 'TipController@getWonDailyTips');
    Route::get('get_top_experts', 'TipController@getTopExpertsBrief');
    Route::get('get_all_experts', 'TipController@getAllExperts');
    Route::get('get_tip_expert/{id}', 'TipController@getTipExpert');
    Route::get('get_open_events_for_expert/{id}', 'TipController@getExpertOpenEvents');
    // Route::get('get_expert_winning_forecasts/{id}', 'TipController@getExpertWonEvents');
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');
    Route::post('confirm_user_email', 'UserController@confirmEmail');
    // Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');
});

// users protected routes
Route::group(['middleware' => 'jwt.auth',  'prefix' => 'auth'], function($router){
    Route::post('logout', 'AuthController@logout');
    Route::post('auth_user', 'AuthController@AuthUser');
    Route::get('get_expert_winning_forecasts/{id}', 'TipController@getExpertWonEvents');
    Route::post('subscribe_to_expert_tips', 'SubscriptionController@subscribeToExpertTips');
    Route::get('get_subscription/{id}', 'SubscriptionController@getSubscription');
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
    Route::get('get_paginated_leagues', 'AdminController@getPgntdLeagues');
    Route::post('delete_league/{id}', 'AdminController@delLeague');
    Route::post('update_league/{id}', 'AdminController@updateLeague');
    Route::post('create_new_league', 'AdminController@createLeague');
    Route::get('get_paginated_teams', 'AdminController@getPgntdTeams');
    Route::get('get_all_leagues', 'AdminController@getAllLeagues');
    Route::post('delete_team/{id}', 'AdminController@deleteTeam');
    Route::post('update_team/{id}', 'AdminController@updateTeam');
    Route::post('create_new_team', 'AdminController@createNewTeam');
    // Route::post('admin_filter_models', 'AdminController@filterModels');
    Route::get('admin_filter_teams_by_league/{id}', 'AdminController@filterTeamsByLeague');
    Route::get('admin_get_league/{id}', 'AdminController@getLeague');
    Route::post('admin_search_for_teams', 'AdminController@searchForTeams');
    Route::get('get_all_bookmakers', 'AdminController@getBookmakers');
    Route::post('create_new_bookmaker', 'AdminController@createNewBookmaker');
    Route::post('update_bookmaker/{id}', 'AdminController@updateBookmaker');
    Route::post('delete_bookmaker/{id}', 'AdminController@deleteBookmaker');
    Route::get('get_pgntd_markets', 'AdminController@getPgntdMarkets');
    Route::post('create_new_market', 'AdminController@createNewMarket');
    Route::post('update_market/{id}', 'AdminController@updateMarket');
    Route::post('delete_market/{id}', 'AdminController@deleteMarket');
    Route::get('get_teams_for_a_league/{id}', 'AdminController@getTeamsForALeague');
    Route::get('get_all_markets', 'AdminController@getAllMarkets');
    Route::get('get_leagues_for_a_country/{id}', 'AdminController@getLeaguesForCountry');
    Route::post('create_daily_tips', 'AdminController@createDailyTips');
    Route::get('get_pgntd_daily_tips', 'AdminController@getPgntdDailyTips');
    Route::get('admin_get_daily_tip_summary/{id}', 'AdminController@getDailyTipSummary');
    Route::post('change_daily_tip_status/{id}', 'AdminController@adminChangeDailyTipsStatus');
    Route::post('remove_event_from_daily_tips/{id}', 'AdminController@removeEventFromDailyTips');
    Route::post('admin_delete_daily_tips/{tc}', 'AdminController@deleteDailyTips');
    Route::post('add_tip_to_daily_tips/{tc}', 'AdminController@addToDailyTips');
    Route::post('change_daily_tip_is_featured/{id}', 'AdminController@changeIsFeaturedOfDailyTip');
    Route::post('admin_delete_daily_tip_summary/{tc}', 'AdminController@deleteDailyTipSummary');
    Route::get('get_international_comps', 'AdminController@getIntnlCompetitions');
    Route::get('get_national_teams', 'AdminController@getNationalTeams');
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


