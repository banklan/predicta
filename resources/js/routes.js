import NotFound from './components/NotFound';
import Welcome from './components/Welcome';
import TodaysTips from './components/TodaysTips';
import TomorrowsTips from './components/TomorrowsTips';
import WonTips from './components/WonTips';
import AllExperts from './components/AllExperts';
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
import ExpertRegister from './components/expert/ExpertRegister';
import ExpertLogin from './components/expert/ExpertLogin';
import ExpertEmailConfirmation from './components/expert/ExpertEmailConfirmation';
import ExpertDashboard from './components/expert/ExpertDashboard';
import NewForecast from './components/expert/NewForecast';
import MyForecasts from './components/expert/MyForecasts';
import ExpertForecastShow from './components/expert/ExpertForecastShow';
import ExpertAccount from './components/expert/Account';
import ExpertSubscriptions from './components/expert/ExpertSubscriptions';
// import ExpertUpdateBankDetails from './components/expert/ExpertUpdateBankDetails';


export default [
    {path: '*', name: 'NotFound', component: NotFound},
    { path: '/', name: 'Welcome', component: Welcome },
    { path: '/todays-tips', name: 'TodaysTips', component: TodaysTips },
    { path: '/tomorrows-tips', name: 'TomorrowsTips', component: TomorrowsTips },
    { path: '/won-tips', name: 'WonTips', component: WonTips },
    { path: '/tip-experts', name: 'AllExperts', component: AllExperts },
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
