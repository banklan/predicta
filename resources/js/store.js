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

const bkmkCode = JSON.parse(localStorage.getItem('bkmkCode')) || []

export const store = new Vuex.Store({
    state: {
        isBusy: false,
        // api: 'http://localhost:8000/api',
        api: 'https://surepredict.herokuapp.com/api',
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
        tipSubscribed: false,
        subscriptionDeleted: false,
        adminUpdatedUser: false,
        adminDeletedUser: false,
        newUserCreated: false,
        userProfileUpdated: false,
        adminUpdatedTip: false,
        feedbackPosted: false,
        adminDeleteFeedbackThread: false,
        adminDeleteEnquiry: false,
        adminProfileUpdated: false,
        bkmkCode: bkmkCode,
        pstPkey: 'pk_test_27753e86c032bcdd6758c5bf79e1048f1e7efbe8',
        pstSecKey: 'sk_test_ca4f805874f7dfa0640646e947b8e11725f5bb06',
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
        },
        subscriptionDeleted(state)
        {
            return state.subscriptionDeleted
        },
        adminUpdatedUser(state)
        {
            return state.adminUpdatedUser
        },
        adminDeletedUser(state)
        {
            return state.adminDeletedUser
        },
        newUserCreated(state)
        {
            return state.newUserCreated
        },
        userProfileUpdated(state)
        {
            return state.userProfileUpdated
        },
        adminUpdatedTip(state)
        {
            return state.adminUpdatedTip
        },
        feedbackPosted(state)
        {
            return state.feedbackPosted
        },
        adminDeleteFeedbackThread(state)
        {
            return state.adminDeleteFeedbackThread
        },
        adminDeleteEnquiry(state)
        {
            return state.adminDeleteEnquiry
        },
        adminProfileUpdated(state)
        {
            return state.adminProfileUpdated
        },
        bookmakersCode(state)
        {
            return state.bkmkCode
        },
        pstPkey(state)
        {
            return state.pstPkey
        },
        pstSecKey(state)
        {
            return state.pstSecKey
        },
    },
    actions: {},
    mutations: {
        adminLoginSuccess(state, payload)
        {
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
            state.expertUserDeleted = false
            state.newCountryAdded = false
            state.dailyTipDeleted = false
            state.subscriptionDeleted = false
            state.adminUpdatedTip = false
            state.feedbackPosted = false
            state.adminDeleteFeedbackThread = false
            state.adminDeleteEnquiry = false
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
        },
        resetUsersFlashMsg(state)
        {
            state.tipSubscribed = false
        },
        subscriptionDeleted(state){
            state.subscriptionDeleted = true
        },
        adminUpdatedUser(state)
        {
            state.adminUpdatedUser = true
        },
        adminDeletedUser(state)
        {
            state.adminDeletedUser = true
        },
        newUserCreated(state)
        {
            state.newUserCreated = true
        },
        updatedUserProfile(state, payload)
        {
            state.authUser.first_name = payload.first_name
            state.authUser.last_name = payload.last_name
            state.authUser.phone = payload.phone
            state.authUser.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            state.userProfileUpdated = true
        },
        userProfilePicUpdated(state, payload)
        {
            state.authUser = Object.assign({}, payload.user, {token: payload.token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            state.userProfileUpdated = true
        },
        adminUpdatedTip(state, payload)
        {
            state.adminUpdatedTip = true
        },
        feedbackPosted(state, payload){
            state.feedbackPosted = true
        },
        adminDeletedfeedbackThread(state, payload)
        {
            state.adminDeleteFeedbackThread = true
        },
        adminDeleteEnquiry(state, payload)
        {
            state.adminDeleteEnquiry = true
        },
        updatedAdminProfile(state, payload)
        {
            state.authAdmin.first_name = payload.first_name
            state.authAdmin.last_name = payload.last_name
            state.authAdmin.phone = payload.phone
            state.authAdmin.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
        },
        adminProfilePicUpdated(state, payload)
        {
            state.authAdmin = Object.assign({}, payload.user, {token: payload.token})
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
            state.adminProfileUpdated = true
        },
        addBookmakersCode(state, payload)
        {
            let code = JSON.parse(localStorage.getItem('bkmkCode'))
            state.bkmkCode = code
        },
        clearbkmkCode(state)
        {
            window.localSorage.removeItem('bkmkCode')
            state.bkmkCode = []
        },
    },
})
