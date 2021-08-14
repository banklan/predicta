import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import { totalmem } from 'os';
import router from './routes'

Vue.use(Vuex, axios);

const auth_admin = window.localStorage.getItem('authAdmin')
const authAdmin = auth_admin ? JSON.parse(auth_admin) : null

const adminlogged_in = window.localStorage.getItem('adminIsLoggedIn')
const adminIsLoggedIn = adminlogged_in ? true : false

const auth_expert = window.localStorage.getItem('authExpert')
const authExpert = auth_expert ? JSON.parse(auth_expert) : null

const expert_loggedin = window.localStorage.getItem('expertIsLoggedIn')
const expertIsLoggedIn = expert_loggedin ? true : false

const forecast_odd = window.localStorage.getItem('foreCastOdd')
const foreCastOdd = forecast_odd ? forecast_odd : null

const tips = window.localStorage.getItem('forecast')
const forecast = tips ? JSON.parse(tips) : []

const total_odds = window.localStorage.getItem('totalOdds')
const totalOdds = total_odds ? total_odds : 0

const dt = window.localStorage.getItem('dailyTips')
const dailyTips = dt ? JSON.parse(dt) : []

const auth_user = window.localStorage.getItem('authUser')
const authUser = auth_user ? JSON.parse(auth_user) : null

const user_loggedin = window.localStorage.getItem('userIsLoggedIn')
const userIsLoggedIn = user_loggedin ? true : false

const redirect = window.localStorage.getItem('redirOnlogin')
const redirectOnLogin = redirect ? redirect : null


export const store = new Vuex.Store({
    state: {
        isBusy: false,
        api: 'http://localhost:8000/api',
        adminIsLoggedIn: adminIsLoggedIn,
        authAdmin: authAdmin,
        adminUpdatedSuperUser: false,
        adminUserDeleted: false,
        newAdminCreated: false,
        authExpert: authExpert,
        expertIsLoggedIn: expertIsLoggedIn,
        foreCastOdd: foreCastOdd,
        forecast: forecast,
        totalOdds: totalOdds,
        newForecastCreated: false,
        expertProfileUpdated: false,
        newExpertCreated: false,
        adminUpdatedExpert: false,
        expertUserDeleted: false,
        newCountryAdded: false,
        dailyTips: dailyTips,
        newDaiyTipCreated: false,
        dailyTipDeleted: false,
        tipAddedToDailyTips: false,
        authUser: authUser,
        userIsLoggedIn: userIsLoggedIn,
        redirectOnLogin: redirectOnLogin,
        tipSubscribed: false
    },
    getters: {
        isBusy(state)
        {
            return state.isBusy
        },
        api(state)
        {
            return state.api;
        },
        adminIsLoggedIn(state)
        {
            return state.adminIsLoggedIn
        },
        authAdmin(state)
        {
            return state.authAdmin
        },
        adminUpdatedSuperUser(state)
        {
            return state.adminUpdatedSuperUser
        },
        adminUserDeleted(state)
        {
            return state.adminUserDeleted
        },
        adminCreated(state)
        {
            return state.newAdminCreated
        },
        authExpert(state)
        {
            return state.authExpert
        },
        expertIsLoggedIn(state)
        {
            return state.expertIsLoggedIn
        },
        foreCastOdd(state)
        {
            return state.foreCastOdd
        },
        getForecast(state)
        {
            return state.forecast
        },
        totalOdds(state)
        {
            return state.totalOdds
        },
        newForecast(state)
        {
            return state.newForecastCreated
        },
        expertProfileUpdated(state)
        {
            return state.expertProfileUpdated
        },
        newExpertCreated(state)
        {
            return state.newExpertCreated
        },
        adminUpdatedExpert(state)
        {
            return state.adminUpdatedExpert
        },
        expertUserDeleted(state)
        {
            return state.expertUserDeleted
        },
        newCountryAdded(state)
        {
            return state.newCountryAdded
        },
        getDailyTips(state)
        {
            return state.dailyTips
        },
        newDaiyTipCreated(state)
        {
            return state.newDaiyTipCreated
        },
        dailyTipDeleted(state)
        {
            return state.dailyTipDeleted
        },
        tipAddedToDailyTips(state)
        {
            return state.tipAddedToDailyTips
        },
        authUser(state)
        {
            return state.authUser
        },
        userIsLoggedIn(state)
        {
            return state.userIsLoggedIn
        },
        redirectOnLogin(state)
        {
            return state.redirectOnLogin
        },
        tipSubscribed(state)
        {
            return state.tipSubscribed
        }
    },
    actions: {},
    mutations: {
        adminLoginSuccess(state, payload)
        {
            // localStorage.removeItem('authUser')
            // localStorage.removeItem('isLoggedIn')
            // state.isLoggedIn = false
            // state.authUser = null
            state.adminIsLoggedIn = true
            state.authAdmin = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
            window.localStorage.setItem('adminIsLoggedIn', true)
        },
        logOutAdmin(state)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authAdmin = null
        },
        logOutAuthUser(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            state.userIsLoggedIn = false
            state.authUser = null
        },
        adminUpdatedSuperUser(state)
        {
            state.adminUpdatedSuperUser = true
        },
        resetAdminUpdatedFlashMsgs(state)
        {
            state.adminUpdatedSuperUser = false
            state.adminUserDeleted = false
            state.newAdminCreated = false
            state.newExpertCreated = false
            state.adminUpdatedExpert = false
            state.expertUserDeleted = false,
                state.newCountryAdded = false
            state.dailyTipDeleted = false
        },
        adminUserDeleted(state)
        {
            state.adminUserDeleted = true
        },
        newAdminCreated(state){
            state.newAdminCreated = true
        },
        logOutAdmin(state)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authAdmin = null
        },
        expertLoginSuccess(state, payload)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('isLoggedIn')
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.isLoggedIn = false
            state.authUser = null
            state.adminIsLoggedIn = false
            state.expertIsLoggedIn = true
            state.authadmin = null
            state.authExpert = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authExpert', JSON.stringify(state.authExpert))
            window.localStorage.setItem('expertIsLoggedIn', true)
        },
        setForecastOdd(state, payload)
        {
            localStorage.setItem('foreCastOdd', payload)
            state.foreCastOdd = payload
        },
        setTip(state, payload)
        {
            let forecast = localStorage.getItem('forecast')
            if (forecast) {
                // push a new object in the array and store back
            }
            // create a new array and store object in array in localStorage
            // localStorage.setItem('tips', )

        },
        addTipsToForecast(state, payload)
        {
            state.forecast.push(payload)
            localStorage.setItem('forecast', JSON.stringify(state.forecast))
            this.commit('updateTotalOdds')
        },
        removeTip(state, payload)
        {
            state.forecast.splice(payload, 1)
            localStorage.setItem('forecast', JSON.stringify(state.forecast))
            this.commit('updateTotalOdds')
        },
        updateTotalOdds(state, payload)
        {
            let odds = state.forecast.map(item=>item.odd).reduce((a, b) => parseFloat(a) * parseFloat(b), 1)
            odds = odds.toFixed(2)
            state.totalOdds = odds
            window.localStorage.setItem('totalOdds', odds)
        },
        clearForecast(state)
        {
            localStorage.removeItem('forecast')
            state.forecast = []
            localStorage.removeItem('totalOdds')
            state.totalOdds = 0
            localStorage.removeItem('foreCastOdd')
            state.foreCastOdd = null
        },
        logOutExpert(state)
        {
            localStorage.removeItem('authExpert')
            localStorage.removeItem('expertIsLoggedIn')
            state.expertIsLoggedIn = false
            state.authExpert = null
        },
        newForecastCreated(state)
        {
            state.newForecastCreated = true
        },
        updatedExpertProfile(state, payload)
        {
            state.authExpert.first_name = payload.first_name
            state.authExpert.last_name = payload.last_name
            state.authExpert.phone = payload.phone
            state.authExpert.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authExpert', JSON.stringify(state.authExpert))
            state.expertProfileUpdated = true
        },
        expertprofilePicUpdated(state, payload)
        {
            state.authExpert = Object.assign({}, payload.user, {token: payload.token})
            window.localStorage.setItem('authExpert', JSON.stringify(state.authExpert))
            state.expertProfileUpdated = true
        },
        resetExpertFlashMsgs(state, payload)
        {
            state.newForecastCreated = false
        },
        newExpertCreated(state, payload)
        {
            state.newExpertCreated = true
        },
        adminUpdatedExpert(state, payload)
        {
            state.adminUpdatedExpert = true
        },
        expertUserDeleted(state)
        {
            state.expertUserDeleted = true
        },
        newCountryAdded(state)
        {
            state.newCountryAdded = true
        },
        addDailyTips(state, payload)
        {
            state.dailyTips.unshift(payload)
            localStorage.setItem('dailyTips', JSON.stringify(state.dailyTips))
        },
        removeDailyTip(state, payload)
        {
            state.dailyTips.splice(payload, 1)
            localStorage.setItem('dailyTips', JSON.stringify(state.dailyTips))
        },
        newDaiyTipCreated(state)
        {
            state.newDaiyTipCreated = true
        },
        clearDailyTip(state)
        {
            localStorage.removeItem('dailyTips')
            state.dailyTips = []
        },
        dailyTipDeleted(state)
        {
            state.dailyTipDeleted = true
        },
        tipAddedToDailyTips(state)
        {
            this.tipAddedToDailyTips = true
        },
        userLoginSuccess(state, payload)
        {
            localStorage.removeItem('authExpert')
            localStorage.removeItem('isLoggedIn')
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.authExpert = null
            state.adminIsLoggedIn = false
            state.expertIsLoggedIn = false
            state.authadmin = null
            state.userIsLoggedIn = true
            state.authUser = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('userIsLoggedIn', true)
        },
        redirectOnLogin(state, payload)
        {
            window.localStorage.setItem('redirOnLogin', payload)
            state.redirectOnLogin = payload
        },
        tipSubscribed(state, payload)
        {
            state.tipSubscribed = true
        }
    },
})
