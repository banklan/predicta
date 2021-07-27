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
import ExpertRegister from './components/expert/ExpertRegister';
import ExpertLogin from './components/expert/ExpertLogin';
import ExpertEmailConfirmation from './components/expert/ExpertEmailConfirmation';
import ExpertDashboard from './components/expert/ExpertDashboard';
import NewForecast from './components/expert/NewForecast';
import MyForecasts from './components/expert/MyForecasts';


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
]
