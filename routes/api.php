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
    Route::get('get_brief_won_daily_tips', 'TipController@getBriefWonDailyTips');
    Route::get('get_top_experts', 'TipController@getTopExpertsBrief');
    Route::get('get_all_experts', 'TipController@getAllExperts');
    Route::get('get_tip_expert/{id}', 'TipController@getTipExpert');
    Route::get('get_open_events_for_expert/{id}', 'TipController@getExpertOpenEvents');
    Route::get('get_experts_by_winning_rate', 'TipController@getExpertsByWinningRate');
    Route::get('get_todays_tips', 'TipController@getTodaysTips');
    Route::get('get_won_tips', 'TipController@getWonTips');
    Route::post('send_enquiry', 'UserController@sendEnquiry');
    Route::get('get_brief_won_expert_forecasts', 'TipController@getWonExpertForecasts');
    Route::get('get_won_experts_forecasts', 'TipController@getAllWonExpertForecasts');
    Route::post('join_mailing_list', 'UserController@joinMailingList');
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('confirm_user_email', 'UserController@confirmEmail');
});

// users protected routes
Route::group(['middleware' => 'jwt.auth',  'prefix' => 'auth'], function($router){
    Route::post('logout', 'AuthController@logout');
    Route::post('auth_user', 'AuthController@AuthUser');
    Route::get('get_expert_winning_forecasts/{id}', 'TipController@getExpertWonEvents');
    Route::post('subscribe_to_expert_tips', 'SubscriptionController@subscribeToExpertTips');
    Route::get('get_subscription/{id}', 'SubscriptionController@getSubscription');
    Route::get('get_forecast_summary/{id}', 'SubscriptionController@getForecastSummary');
    Route::get('get_my_subscriptions', 'SubscriptionController@getMySubscriptions');
    Route::get('check_if_subscribed/{odd}/{expert}', 'SubscriptionController@checkIfSubscribed');
    Route::get('subscription_count/{odd}/{expert}', 'SubscriptionController@subscriptionCount');
    Route::get('get_hot_tip_experts', 'SubscriptionController@getHotTipExperts');
    Route::post('update_profile', 'UserController@updateProfile');
    Route::post('update_profile_picture', 'UserController@updateProfilePicture');
    Route::post('confirm_current_pswd', 'UserController@confirmCurrentPassword');
    Route::post('update_profile_password', 'UserController@updateProfilePassword');
    Route::post('submit_users_feedback', 'UserController@submitUsersFeedback');
    Route::get('get_users_outbox_messages', 'UserController@getUsersOutboxMsgs');
    Route::post('user_delete_outbox_msg/{msg}', 'UserController@userDelOutboxMsg');
    Route::get('get_users_inbox_messages', 'UserController@getUsersInboxMsgs');
    Route::post('user_delete_inbox_msg/{msg}', 'UserController@userDelInboxMsg');
    Route::get('get_user_feedback/{id}', 'UserController@getUserFeedback');
    Route::post('update_feedback_is_read/{id}', 'UserController@updateFeedbackIsRead');
    Route::get('get_feedback_parent/{id}', 'UserController@getFeedbackParent');
    Route::get('get_feedback_thread/{id}', 'UserController@getFeedbackThread');
    Route::get('get_feedback_outbox/{id}', 'UserController@getOutboxMsg');
    Route::post('reply_feedback', 'UserController@replyFeedback');
    Route::get('get_odd_price/{odd}', 'UserController@getOddPrice');
    Route::get('get_sub_payment_details/{sub}', 'UserController@getSubsrPaymentDetails');
    Route::post('follow_expert/{expert}', 'UserController@followExpert');
    Route::get('check_if_followed/{expert}', 'UserController@checkFollow');
    Route::get('get_followed_experts', 'UserController@getFollowedExperts');
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
    Route::post('remove_event_from_daily_tips/{id}', 'AdminController@removeEventFromDailyTips');
    Route::post('admin_delete_daily_tips/{tc}', 'AdminController@deleteDailyTips');
    Route::post('add_tip_to_daily_tips/{tc}', 'AdminController@addToDailyTips');
    Route::post('change_daily_tip_is_featured/{id}', 'AdminController@changeIsFeaturedOfDailyTip');
    Route::post('admin_delete_daily_tip_summary/{tc}', 'AdminController@deleteDailyTipSummary');
    Route::get('get_international_comps', 'AdminController@getIntnlCompetitions');
    Route::get('get_national_teams', 'AdminController@getNationalTeams');
    Route::get('admin_get_paginated_subscriptions', 'AdminController@getPgntdSubscriptions');
    Route::get('admin_get_subscription/{id}', 'AdminController@getSubscription');
    Route::post('admin_delete_subscription/{id}', 'AdminController@adminDelSubscription');
    Route::post('admin_toggle_forecast_availability/{id}', 'AdminController@updateExpertForecastAvail');
    Route::get('get_all_plans', 'PlanController@adminGetAllPlans');
    Route::post('admin_delete_plan/{id}', 'PlanController@adminDelPlan');
    Route::post('admin_update_plan/{id}', 'PlanController@updatePlan');
    Route::post('admin_create_plan', 'PlanController@createPlan');
    Route::get('get_paginated_users', 'AdminController@getPgntdUsers');
    Route::post('update_user_status/{id}', 'AdminController@updateUserStatus');
    Route::get('admin_get_user/{id}', 'AdminController@adminGetUser');
    Route::post('admin_update_user/{id}', 'AdminController@updateUser');
    Route::post('admin_del_user/{id}', 'AdminController@adminDelUser');
    Route::post('update_user_password/{id}', 'AdminController@updateUserPassword');
    Route::post('admin_create_user', 'AdminController@adminCreateUser');
    Route::post('admin_change_subscription_status/{sub}', 'AdminController@changeSubStatus');
    Route::get('get_todays_subscriptions', 'AdminController@getTodaysSubscriptions');
    Route::get('get_week_payments', 'AdminController@getWeekPayment');
    Route::get('get_week_earnings', 'AdminController@getWeekEarnings');
    Route::get('get_newly_reg_experts', 'AdminController@getNewlyRegExperts');
    Route::get('get_newly_reg_users', 'AdminController@getNewlyRegUsers');
    Route::get('get_latest_forecasts', 'AdminController@getLatestForecasts');
    Route::get('get_latest_daily_tips', 'AdminController@getLatestDailyTips');
    Route::get('admin_get_users_counts', 'AdminController@getAllUsersCounts');
    Route::get('get_daily_tip_success_rate', 'AdminController@getDailyTipsSuccessRate');
    Route::get('get_daily_stats', 'AdminController@getDailyStats');
    Route::get('get_pgntd_payments', 'AdminController@getPgntdPayments');
    Route::get('get_subscription_payment_details/{id}', 'AdminController@getSubPymtDetails');
    Route::get('get_pgntd_earnings', 'AdminController@getPgntdEarnings');
    Route::get('get_earning_details/{id}', 'AdminController@getEarningDetail');
    Route::post('change_earning_is_settled_status/{id}', 'AdminController@changeEarningStatus');
    Route::get('admin_get_expert_earnings/{id}', 'AdminController@getExpertEarning');
    Route::get('admin_get_expert_subs/{id}', 'AdminController@getExpertSubscriptions');
    Route::get('get_exp_outstanding_earnings/{id}', 'AdminController@getExpertOutstandingEarnings');
    Route::get('get_expert_earnings/{id}', 'AdminController@getExpertEarnings');
    Route::get('admin_get_single_daily_tip/{code}/{id}', 'AdminController@getSingleDailyTip');
    Route::post('admin_update_daily_tip/{id}', 'AdminController@adminUpdateSingleDailyTip');
    Route::get('admin_get_daily_tip_summary_with_code/{code}', 'AdminController@getDailyTipSummaryWithCode');
    Route::get('get_paginated_inbox_feedbacks', 'AdminController@getPgntdInboxFeedbacks');
    Route::get('admin_get_user_feedback/{id}', 'AdminController@adminGetUserFeedback');
    Route::get('admin_get_feedback_parent/{id}', 'AdminController@adminGetFeedbackParent');
    Route::get('admin_get_inbox_feedback_thread/{id}', 'AdminController@adminGetInboxFeedbackThread');
    Route::post('admin_update_feedback_is_read/{id}', 'AdminController@updateFeedbackIsRead');
    Route::post('admin_post_feedback_reply/{id}', 'AdminController@adminReplyFeedback');
    Route::get('get_paginated_outbox_feedbacks', 'AdminController@getPgntdOutboxFeedbacks');
    Route::post('admin_delete_outbox_msg/{id}', 'AdminController@adminDelOutboxMsg');
    Route::post('admin_delete_feedback_thread/{id}', 'AdminController@adminDelFeedbackThread');
    Route::post('get_feedback_search_result', 'AdminController@getFeedbackSearchResult');
    Route::get('all_enquiries', 'AdminController@getPgntdEnquiries');
    Route::get('get_unread_enquiry_count', 'AdminController@getUnreadEnquiryCount');
    Route::post('admin_del_enquiry/{id}', 'AdminController@adminDelEnquiry');
    Route::get('get_enquiry/{id}', 'AdminController@adminGetEnquiry');
    Route::post('enquiry_was_read/{id}', 'AdminController@adminReadEnquiry');
    Route::get('check_new_feedback', 'AdminController@newFbkCount');
    Route::get('check_unread_enquiry', 'AdminController@unreadEnqCount');
    Route::post('admin_search_for_daily_tips', 'AdminController@searchForDailyTip');
    Route::post('admin_search_for_experts', 'AdminController@searchForExperts');
    Route::post('admin_search_for_users', 'AdminController@searchForUsers');
    Route::post('admin_search_for_expert_forecasts', 'AdminController@searchForExpertForecasts');
    Route::post('admin_search_for_subscriptions', 'AdminController@searchForSubscription');
    Route::get('admin_get_all_subscriptions', 'AdminController@getAllSubscriptions');
    Route::post('admin_search_for_payments', 'AdminController@searchForPayments');
    Route::post('admin_search_for_earnings', 'AdminController@searchForEarnings');
    Route::post('update_admin_profile', 'AdminController@updateAdminProfile');
    Route::post('confirm_current_pswd', 'AdminController@confirmCurrentPassword');
    Route::post('update_admin_profile_password', 'AdminController@updateAdminProfilePassword');
    Route::post('update_admin_profile_picture', 'AdminController@updateAdminProfilePicture');
    Route::get('get_admin_mailing_list', 'AdminController@getMailingList');
    Route::post('toggle_mailing_list_status/{user}', 'AdminController@toggleMailingListStatus');
    Route::post('admin_add_to_mailing_list', 'AdminController@adminAddToMailingList');
    Route::post('delete_user_from_mailing_list/{user}', 'AdminController@delUserFromMailingList');
    Route::get('admin_check_daily_tips_mailing/{id}', 'AdminController@checkDailyTipMailing');
    Route::post('mail_daily_tips/{id}', 'AdminController@mailDailyTips');
    Route::get('get_admin_mailed_tips', 'AdminController@getMailedTips');
    Route::post('delete_mailed_daily_tip/{id}', 'AdminController@deleteMailedDailyTip');
    Route::post('admin_create_bulk_markets', 'AdminController@createBulkMarkets');
    Route::post('admin_create_bulk_teams', 'AdminController@createBulkTeams');
    Route::get('get_pgntd_follows', 'AdminController@getPaginatedFollows');
    Route::get('admin_get_users_follows/{user}', 'AdminController@getUserFollows');
    Route::get('admin_get_expert_follows/{expert}', 'AdminController@getExpertFollows');
    Route::post('admin_delete_follow/{follow}', 'AdminController@delFollow');
    Route::get('admin_get_all_experts', 'AdminController@getAllExperts');
    Route::get('filter_follows_by_experts/{exp}', 'AdminController@filterFollowsByExpert');
    Route::post('create_new_bookmaker_without_logo', 'AdminController@createBookmakerWithoutLogo');
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
    Route::get('get_expert_forecast_summary', 'ExpertController@getAtAglance');
    Route::get('get_expert_subscriptions', 'ExpertController@getExpertSubscriptions');
    Route::get('get_expert_subscription/{id}', 'ExpertController@getExpertSubscription');
    Route::get('get_expert_earnings', 'ExpertController@getExpertEarnings');
    Route::get('get_subscriber_subs/{id}', 'ExpertController@getUserOtherSubscriptions');
    Route::get('get_forecast_performance', 'ExpertController@getExpertForecastPerformance');
    Route::get('get_expert_followers', 'ExpertController@getExpertFollowers');
    Route::get('get_all_expert_followers', 'ExpertController@getAllExpertFollowers');
});


