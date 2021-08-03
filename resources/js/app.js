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

import vuetify from './plugins/vuetify';
import Routes from './routes';
import { store } from './store';
import App from './components/App'
import Moment from 'vue-moment'
import './filters'


Vue.use(VueRouter)
// Vue.use(axios)
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
    // console.log(authAdmin)
    if (requireExpertAuth && authExpert == null) {
        next('/expert-login')
    }else if(to.path == '/expert-login' && authExpert){
        next('/expert')
    }else {
        next()
    }
});

//axios interceptor for expired token
axios.interceptors.response.use(null, (err) =>
{
    if (err.response.status == 401) {
        store.commit('logOut')
        store.commit('logOutAdmin')
        store.commit('logOutExpert')
        if (router.currentRoute.name !== ('AdminLogin' || 'Login')) {
            router.push('/')
        }
        router.push('/')
    }
    return Promise.reject(err)
})


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('tips-route', require('./components/children/TipsRoute.vue').default);
Vue.component('sure-odds', require('./components/children/SureOdds.vue').default);
Vue.component('expert-navbar', require('./components/includes/ExpertNavbar.vue').default);
Vue.component('expert-top-panel', require('./components/children/ExpertTopPanel.vue').default);
Vue.component('club-competition-forecast', require('./components/children/ClubCompetitionForecast.vue').default);
Vue.component('intnl-competition-forecast', require('./components/children/IntnlCompetitionForecast.vue').default);
Vue.component('expert-other-routes', require('./components/children/ExpertOtherRoutes.vue').default);
Vue.component('expert-brief-performance', require('./components/children/ExpertBriefPerformamnce.vue').default);
Vue.component('admin-top-panel', require('./components/children/AdminTopPanel.vue').default);

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    vuetify,
    store
});
