/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VeeValidate from 'vee-validate';
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'
import 'animate.css'

import vuetify from './plugins/vuetify';
import Routes from './routes';
import { store } from './store';
import App from './components/App'
import Moment from 'vue-moment'
import './filters'

Vue.use(VueRouter)
Vue.use(VeeValidate)
Vue.use(Moment)

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    linkActiveClass: 'font-semibold'
})

router.beforeEach((to, from, next) => {
    const requireAdminsAuth = to.matched.some(rec => rec.meta.requireAdminsAuth);
    const authAdmin = store.state.authAdmin
    if (requireAdminsAuth && authAdmin == null) {
        next('/admin/login')
    }else if(to.path == '/admin/login' && authAdmin){
        next('/admin')
    }else {
        next()
    }
});

router.beforeEach((to, from, next) => {
    const requireExpertAuth = to.matched.some(rec => rec.meta.requireExpertAuth);
    const authExpert = store.state.authExpert

    if (requireExpertAuth && authExpert == null) {
        next('/expert-login')
    }else if(to.path == '/expert-login' && authExpert){
        next('/expert')
    }else if(to.path == '/expert-register' && authExpert){
        next('/expert')
    }else if(to.path.substring(0, 6) == '/admin'  && authExpert){
        next('/expert')
    }else if(to.path == '/dashboard' && authExpert){
        next('/expert')
    }else if (to.path == '/subscriptions' && authExpert) {
        next('/expert')
    }else if (to.path == '/user-account' && authExpert) {
        next('/expert')
    }else {
        next()
    }
});

router.beforeEach((to, from, next) => {
    const requireUsersAuth = to.matched.some(rec => rec.meta.requireUsersAuth);
    const authUser = store.state.authUser
    if (requireUsersAuth && authUser == null) {
        store.commit('redirectOnLogin', to.path)
        next('/login')
    }else if(to.path == '/login' && authUser){
        next('/dashboard')
    }else if(to.path == '/register' && authUser){
        next('/dashboard')
    }else if(to.path.substring(0, 6) == '/admin' && authUser){
        next('/dashboard')
    }else if(to.path.substring(0, 7) == '/expert' && authUser){
        next('/dashboard')
    }else {
        next()
    }
});

//axios interceptor for expired token
axios.interceptors.response.use(null, (err) =>
{
    if (err.response.status == 401) {
        store.commit('logOutAuthUser')
        store.commit('logOutAdmin')
        store.commit('logOutExpert')
        if (router.currentRoute.name !== ('AdminLogin' || 'UserLogin')) {
            router.push('/')
        }
        router.push('/')
    }
    return Promise.reject(err)
})


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('tips-route', require('./components/children/TipsRoute.vue').default);
Vue.component('sure-odds', require('./components/children/SureOdds.vue').default);
Vue.component('won-tips', require('./components/children/WonTips.vue').default);
Vue.component('top-experts', require('./components/children/TopExpertsBrief.vue').default);
Vue.component('won-expert-forecasts', require('./components/children/WonExpertForecasts.vue').default);
Vue.component('tips-stats', require('./components/children/TipsStats.vue').default);
Vue.component('expert-navbar', require('./components/includes/ExpertNavbar.vue').default);
Vue.component('auth-navbar', require('./components/includes/AuthUserNavbar.vue').default);
Vue.component('expert-top-panel', require('./components/children/ExpertTopPanel.vue').default);
Vue.component('club-competition-forecast', require('./components/children/ClubCompetitionForecast.vue').default);
Vue.component('intnl-competition-forecast', require('./components/children/IntnlCompetitionForecast.vue').default);
Vue.component('expert-other-routes', require('./components/children/ExpertOtherRoutes.vue').default);
Vue.component('expert-brief-performance', require('./components/children/ExpertBriefPerformamnce.vue').default);
Vue.component('admin-top-panel', require('./components/children/AdminTopPanel.vue').default);
Vue.component('admin-team-filter', require('./components/children/AdminTeamFilter.vue').default);
Vue.component('admin-filter', require('./components/children/AdminFilter.vue').default);
Vue.component('admin-search', require('./components/children/AdminSearch.vue').default);
Vue.component('create-daily-tips-international', require('./components/children/DailyTipsInternational.vue').default);
Vue.component('create-daily-tips-club', require('./components/children/DailyTipsClub.vue').default);
Vue.component('admin-dailytip-summary', require('./components/children/AdminDailyTipSummary.vue').default);
Vue.component('admin-new-club-tip', require('./components/children/AdminNewClubTip.vue').default);
Vue.component('admin-update-dailytip-summary', require('./components/children/AdminModifyDailyTip.vue').default);
Vue.component('experts-premium-tips', require('./components/children/ExpertsPremiumTips.vue').default);
Vue.component('authuser-top-panel', require('./components/children/AuthUserTopPanel.vue').default);
Vue.component('expert-outstanding-earnings', require('./components/children/AdminExpertOutstandingEarning.vue').default);
Vue.component('newuser-feedback', require('./components/children/NewUserFeedback.vue').default);
Vue.component('toggle-mail-user-status', require('./components/children/AdminToggleMailListUserStatus.vue').default);
Vue.component('admin-daily-tips-mailing', require('./components/children/AdminDailyTipsMailing.vue').default);

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    vuetify,
    store
});
