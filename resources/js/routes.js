import NotFound from './components/NotFound';
import Welcome from './components/Welcome';
import TodaysTips from './components/TodaysTips';
import TomorrowsTips from './components/TomorrowsTips';
import WonTips from './components/WonTips';
import AllExperts from './components/AllExperts';
import TipExpertView from './components/TipExpertView';
import TipOddView from './components/user/TipOddView';
import AdminLogin from './components/admin/AdminLogin';
import AdminDashboard from './components/admin/AdminDashboard';
import AdminSuperUsersList from './components/admin/AdminSuperUsersList';
import AdminUpdateSuperUser from './components/admin/AdminUpdateSuperUser';
import AdminSuperUserDetail from './components/admin/AdminSuperUserDetail';
import AdminCreateSuperUser from './components/admin/AdminCreateSuperUser';
import AdminExpertList from './components/admin/AdminExpertList';
import AdminExpertDetail from './components/admin/AdminExpertDetail';
import AdminCreateExpert from './components/admin/AdminCreateExpert';
import AdminExpertUpdate from './components/admin/AdminExpertUpdate';
import AdminExpertForecasts from './components/admin/AdminExpertForecasts';
import AdminExpertForecast from './components/admin/AdminExpertForecast';
import AdminSingleExpertEvent from './components/admin/AdminSingleExpertEvent';
import AdminForecastsByExperts from './components/admin/AdminForecastsByExperts';
// import AdminForecastsByExpertsShow from './components/admin/AdminForecastsByExpertsShow';
import AdminCountryList from './components/admin/AdminCountryList';
import AdminCreateCountry from './components/admin/AdminCreateCountry';
import AdminBanksList from './components/admin/AdminBanksList';
import AdminLeaguesList from './components/admin/AdminLeagueList';
import AdminTeamList from './components/admin/AdminTeamList';
import AdminFilterTeams from './components/admin/AdminFilterTeamsResult';
import AdminFilterResult from './components/admin/AdminFilterResult';
import AdminSearchTeamRes from './components/admin/AdminSearchTeamRes';
import AdminBookmakersList from './components/admin/AdminBookmakersList';
import AdminMarketList from './components/admin/AdminMarketList';
import AdminDailyTips from './components/admin/AdminDailyTips';
import AdminCreateDailyTips from './components/admin/AdminCreateDailyTips';
import AdminDailyTipShow from './components/admin/AdminDailyTipShow';
import AdminDailyTipAddNew from './components/admin/AdminDailyTipAddNew';
import ExpertRegister from './components/expert/ExpertRegister';
import ExpertLogin from './components/expert/ExpertLogin';
import ExpertEmailConfirmation from './components/expert/ExpertEmailConfirmation';
import ExpertDashboard from './components/expert/ExpertDashboard';
import NewForecast from './components/expert/NewForecast';
import MyForecasts from './components/expert/MyForecasts';
import ExpertForecastShow from './components/expert/ExpertForecastShow';
import ExpertAccount from './components/expert/Account';
import ExpertSubscriptions from './components/expert/ExpertSubscriptions';
import UserLogin from './components/user/UserLogin.vue';
import UserRegister from './components/user/Register.vue';
import UserEmailConfirmation from './components/user/EmailConfirmation.vue';
import UserDashboard from './components/user/Dashboard.vue';
import SubscriptionView from './components/user/SubscriptionView.vue';
// import ExpertUpdateBankDetails from './components/expert/ExpertUpdateBankDetails';


export default [
    {path: '*', name: 'NotFound', component: NotFound},
    { path: '/', name: 'Welcome', component: Welcome },
    { path: '/todays-tips', name: 'TodaysTips', component: TodaysTips },
    { path: '/tomorrows-tips', name: 'TomorrowsTips', component: TomorrowsTips },
    { path: '/won-tips', name: 'WonTips', component: WonTips },
    { path: '/tip-experts', name: 'AllTipExperts', component: AllExperts },
    { path: '/tip-expert/:id', name: 'TipExpertView', component: TipExpertView },
    {
        path: '/tip-detail/:odd/:expert', name: 'TipOddView', component: TipOddView,
        meta: {
            requireUsersAuth: true
        }
    },
    {
        path: '/subscriptions/:sub_id', name: 'SubscriptionView', component: SubscriptionView,
        meta: {
            requireUsersAuth: true
        }
    },
    { path: '/login', name: 'UserLogin', component: UserLogin },
    { path: '/register', name: 'UserRegister', component: UserRegister },
    { path: '/email-confirmation', name: 'UserEmailConfirmation', component: UserEmailConfirmation, props: true },
    {
        path: '/dashboard', name: 'UserDashboard', component: UserDashboard,
        meta: {
            requireUsersAuth: true
        }
    },

    // { path: '/tip-expert-premium/:odd/:expert', name: 'TipExpertPremShow', component: TipExpertPremShow },
    { path: '/admin/login', name: 'AdminLogin', component: AdminLogin },
    {
        path: '/admin', name: 'AdminDashboard', component: AdminDashboard,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users', name: 'AdminSuperUsersList', component: AdminSuperUsersList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/:id/update', name: 'AdminUpdateSuperUser', component: AdminUpdateSuperUser,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/create', name: 'AdminCreateSuperUser', component: AdminCreateSuperUser,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/:id', name: 'AdminSuperUserDetail', component: AdminSuperUserDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/experts', name: 'AdminExpertList', component: AdminExpertList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/create-expert', name: 'AdminCreateExpert', component: AdminCreateExpert,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/expert/:id', name: 'AdminExpertDetail', component: AdminExpertDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/update-expert/:id', name: 'AdminExpertUpdate', component: AdminExpertUpdate,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/expert/:id/forecasts', name: 'AdminExpertForecasts', component: AdminExpertForecasts,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/expert/:expert/forecast/:fc', name: 'AdminExpertForecast', component: AdminExpertForecast,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/forecast/event/:fc', name: 'AdminSingleExpertEvent', component: AdminSingleExpertEvent,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/forecasts-by-experts', name: 'AdminForecastsByExperts', component: AdminForecastsByExperts,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/forecasts-by-experts/:fc', name: 'AdminForecastsByExpertsShow', component: AdminExpertForecast,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/countries', name: 'AdminCountryList', component: AdminCountryList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/countries/create', name: 'AdminCreateCountry', component: AdminCreateCountry,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/banks', name: 'AdminBanksList', component: AdminBanksList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/leagues', name: 'AdminLeaguesList', component: AdminLeaguesList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/teams', name: 'AdminTeamList', component: AdminTeamList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/filter-result', name: 'AdminFilterResult', component: AdminFilterResult, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/teams/filter', name: 'AdminFilterTeams', component: AdminFilterTeams, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/teams/search', name: 'AdminSearchTeamRes', component: AdminSearchTeamRes, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/bookmakers', name: 'AdminBookmakersList', component: AdminBookmakersList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/markets', name: 'AdminMarketList', component: AdminMarketList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/daily-tips', name: 'AdminDailyTips', component: AdminDailyTips,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/daily-tips/create', name: 'AdminCreateDailyTips', component: AdminCreateDailyTips,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/daily-tips/:id/:code', name: 'AdminDailyTipShow', component: AdminDailyTipShow,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/daily-tips/:id/:code/add-tip', name: 'AdminDailyTipAddNew', component: AdminDailyTipAddNew,
        meta: {
            requireAdminsAuth: true
        }
    },
    // {
    //     path: '/admin/national-teams', name: 'AdminNationalTeams', component: AdminNationalTeams,
    //     meta: {
    //         requireAdminsAuth: true
    //     }
    // },
    {
        path: '/expert-register', name: 'ExpertRegister', component: ExpertRegister,
    },
    {
        path: '/expert-login', name: 'ExpertLogin', component: ExpertLogin,
    },
    {
        path: '/expert-email-confirmation', name: 'ExpertEmailConfirmation', component: ExpertEmailConfirmation, props: true
    },
    {
        path: '/expert', name: 'ExpertDashboard', component: ExpertDashboard,
        meta: {
            requireExpertAuth: true
        }
    },
    {
        path: '/expert/new-forecast', name: 'NewForecast', component: NewForecast,
        meta: {
            requireExpertAuth: true
        }
    },
    {
        path: '/expert/forecasts', name: 'MyForecasts', component: MyForecasts,
        meta: {
            requireExpertAuth: true
        }
    },
    {
        path: '/expert/forecast-summary/:id', name: 'ExpertForecastShow', component: ExpertForecastShow,
        meta: {
            requireExpertAuth: true
        }
    },
    {
        path: '/expert/account', name: 'ExpertAccount', component: ExpertAccount,
        meta: {
            requireExpertAuth: true
        }
    },
    {
        path: '/expert/subscriptions', name: 'ExpertSubscriptions', component: ExpertSubscriptions,
        meta: {
            requireExpertAuth: true
        }
    },
    // {
    //     path: '/expert/update-bank-details', name: 'ExpertUpdateBankDetails', component: ExpertUpdateBankDetails,
    //     meta: {
    //         requireExpertAuth: true
    //     }
    // },
]
