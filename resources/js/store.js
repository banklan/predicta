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
        newForecastCreated: false
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
        logOut(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('isLoggedIn')
            localStorage.removeItem('authService')
            state.isLoggedIn = false
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
        }
    },
})
