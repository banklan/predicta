<template>
    <v-container>
        <admin-top-panel title="Dashboard" />
        <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else justify="start" class="mt-5 ml-n10">
            <v-col cols="12" md="5">
                <v-card color="#019413" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Model Counts</v-card-title>
                    <v-card-text>
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr>
                                    <th>Admins:</th>
                                    <td>{{ usersCount.admins }}</td>
                                </tr>
                                <tr>
                                    <th>Experts:</th>
                                    <td>{{ usersCount.experts }}</td>
                                </tr>
                                <tr>
                                    <th>Logged-in Experts:</th>
                                    <td>{{ usersCount.loggedInExperts }}</td>
                                </tr>
                                <tr>
                                    <th>Inactive Experts:</th>
                                    <td>{{ usersCount.disabledExperts }}</td>
                                </tr>
                                <tr>
                                    <th>Users:</th>
                                    <td>{{ usersCount.users }}</td>
                                </tr>
                                <tr>
                                    <th>Logged-in Users:</th>
                                    <td>{{ usersCount.loggedInUsers }}</td>
                                </tr>
                                <tr>
                                    <th>Inactive Users:</th>
                                    <td>{{ usersCount.disabledUsers }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card color="#ff0869" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Daily Stats</v-card-title>
                    <v-card-text>
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr>
                                    <th>Daily Tips Today:</th>
                                    <td>{{ dailyStats.dt }}</td>
                                </tr>
                                <tr>
                                    <th>Forecasts Today:</th>
                                    <td>{{ dailyStats.ft }}</td>
                                </tr>
                                <tr>
                                    <th>Subscription Today:</th>
                                    <td>{{ subsToday.length }}</td>
                                </tr>
                                <tr>
                                    <th>Subscription This Week:</th>
                                    <td>{{ dailyStats.subs }}</td>
                                </tr>
                                <tr>
                                    <th>Experts Joined Today:</th>
                                    <td>{{ dailyStats.exps }}</td>
                                </tr>
                                <tr>
                                    <th>Users Joined Today:</th>
                                    <td>{{ dailyStats.users }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card color="#06acde" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Top Experts</v-card-title>
                    <v-card-text>
                        <template v-if="topExperts.length == 0">
                            <div class="body_text white--text">There are no top experts yet.</div>
                        </template>
                        <table v-else class="table table-condensed table-hover">
                            <thead class="white--text">
                                <th>ID</th>
                                <th>Username</th>
                                <th>Success Rate(%)</th>
                            </thead>
                            <tbody class="white--text">
                                <tr v-for="exp in topExperts" :key="exp.id" @click="goToExpert(exp)">
                                    <td>{{ exp.expert_id }}</td>
                                    <td>{{ exp.username }}</td>
                                    <td>{{ exp.winning_rate }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card color="#ea6a0f" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Subscriptions Today <span class="ml-2"><v-chip color="orange lighten-2" v-if="subsToday">{{ subsToday.length }}</v-chip></span></v-card-title>
                    <v-card-text v-if="!isLoading">
                        <template v-if="subsToday.length == 0">
                            <div class="body_text white--text">There are no subscriptions for today yet.</div>
                        </template>
                        <table v-else class="table table-condensed table-hover">
                            <thead class="white--text">
                                <tr>
                                    <th>Sub ID</th>
                                    <th>User</th>
                                    <th>Expert</th>
                                    <th>Cat</th>
                                </tr>
                            </thead>
                            <tbody class="white--text">
                                <tr v-for="sub in subsToday" :key="sub.id" @click="goToSub(sub)">
                                    <td>{{ sub.sub_id }}</td>
                                    <td v-if="sub.user">{{ sub.user.last_name }}  {{ sub.user.first_name.substring(0, 1) }}.</td>
                                    <td v-if="sub.expert">{{ sub.expert.username }}</td>
                                    <td>{{ sub.odd_cat }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="10">
                <v-card color="#81018a" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Week's Payments <span class="ml-2"><v-chip color="purple lighten-2" v-if="payments">{{ payments.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="payments.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Sub ID</th>
                                        <th>Trx ID</th>
                                        <th>Amount(&#8358;)</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="pymt in payments" :key="pymt.id">
                                        <td>{{ pymt.created_at | moment('DD/MM/YY') }}</td>
                                        <td v-if="pymt.user">{{ pymt.user.last_name }}  {{ pymt.user.first_name.substring(0, 1) }}. </td>
                                        <td v-if="pymt.subscription">{{ pymt.subscription.sub_id }} </td>
                                        <td>{{ pymt.tx_id }}</td>
                                        <td>{{ pymt.amount / 100 | price }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                There are no payments made this week.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="10">
                <v-card color="#3809f1" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Week's Earnings <span class="ml-2"><v-chip color="blue darken-2" v-if="earnings">{{ earnings.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                         <template v-if="earnings.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Expert</th>
                                        <th>Expert Share(&#8398;)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="earn in earnings" :key="earn.id">
                                        <td>{{ earn.created_at | moment('DD/MM/YY') }}</td>
                                        <td v-if="earn.subscription">{{ earn.subscription.sub_id }}</td>
                                        <td v-if="earn.subscription">{{ earn.subscription.expert.username }}</td>
                                        <td>{{ earn.exp_amount / 100 | price }}</td>
                                        <td>{{ earn.is_settled ? 'Settled' : 'Not Settled' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                There are no earnings yet for this week.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card color="#c70053" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">New Experts <span class="ml-2"><v-chip color="pink lighten-2" v-if="newExperts.length > 0">{{ newExperts.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="newExperts.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>Expert ID</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="exp in newExperts" :key="exp.id" @click="goToExpert(exp)">
                                        <td>{{ exp.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ exp.expert_id }}</td>
                                        <td>{{ exp.username }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                There are no new experts within the last 24hours.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card color="#015800" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">New Users <span class="ml-2"><v-chip color="green lighten-2" v-if="newUsers.length > 0">{{ newUsers.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="newUsers.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="user in newUsers" :key="user.id" @click="goToUser(user)">
                                        <td>{{ user.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.surname }} {{ user.first_name.substring(0,1) }}.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                There are no new users within the last 24hours.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="10">
                <v-card color="#021b3c" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Latest Forecasts</v-card-title>
                    <v-card-text>
                        <template v-if="forecasts.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>ForecastID</th>
                                        <th>Expert</th>
                                        <th>No of Events</th>
                                        <th>Cat</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="fc in forecasts" :key="fc.id" @click="goToForecast(fc)">
                                        <td>{{ fc.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ fc.forecast_id }}</td>
                                        <td v-if="fc.expert">{{ fc.expert.username }}</td>
                                        <td>{{ fc.event_count }}</td>
                                        <td>{{ fc.forecast_odd }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                There are no new users within the last 24hours.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="10">
                <v-card color="#2f4202" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Today's Tips</v-card-title>
                    <v-card-text>
                        <template v-if="dailyTips.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Country</th>
                                        <th>League</th>
                                        <th>Game</th>
                                        <th>Tip</th>
                                        <th>Odd</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="tip in dailyTips" :key="tip.id" @click="goToDailyTip(tip)">
                                        <td>{{ tip.country }}</td>
                                        <td>{{ tip.league }}</td>
                                        <td>{{ tip.home }} Vs {{ tip.away }}</td>
                                        <td>{{ tip.tip }}</td>
                                        <td>{{ tip.odd }}</td>
                                        <td>{{ tip.event_time }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="body_text white--text">
                                Daily tips have not been created for today.
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card color="#005848" dark raised outlined elevation="4" min-height="200">
                    <v-card-title class="sub_title white--text justify-center">Daily Tips Winning Rate</v-card-title>
                    <v-card-text>
                        <template v-if="dTipSuccessRate.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead class="white--text">
                                    <tr>
                                        <th>Date</th>
                                        <th>Tip Code</th>
                                        <th>Event Count</th>
                                        <th>Status</th>
                                        <th>WR(%)</th>
                                    </tr>
                                </thead>
                                <tbody class="white--text">
                                    <tr v-for="tip in dTipSuccessRate" :key="tip.id">
                                        <td>{{ tip.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ tip.tip_code }}</td>
                                        <td>{{ tip.event_count }}</td>
                                        <td>{{ tip.status }}</td>
                                        <td>{{ tip.success }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            topExperts: [],
            subsToday: [],
            payments: [],
            earnings: [],
            newExperts: [],
            newUsers: [],
            forecasts: [],
            dailyTips: [],
            usersCount: [],
            dTipSuccessRate: [],
            dailyStats: []
        }
    },
    computed:{
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        }
    },
    methods: {
        getTopExperts(){
            this.isLoading = true
            axios.get(this.api + '/get_top_experts').then((res) =>{
                this.isLoading = false
                this.topExperts = res.data
            })
        },
        getSubscriptionsToday(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_todays_subscriptions', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.subsToday = res.data
            })
        },
        getPayments(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_week_payments', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.payments = res.data
            })
        },
        getEarnings(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_week_earnings', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.earnings = res.data
            })
        },
        getNewExperts(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_newly_reg_experts', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.newExperts = res.data
            })
        },
        getNewUsers(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_newly_reg_users', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.newUsers = res.data
            })
        },
        getLatestForecasts(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_latest_forecasts', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.forecasts = res.data
            })
        },
        getDailyTips(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_latest_daily_tips', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.dailyTips = res.data
                // console.log(res.data)
            })
        },
        getUsersCounts(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/admin_get_users_counts', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.usersCount = res.data
            })
        },
        getDailyTipSuccessRt(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_daily_tip_success_rate', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.dTipSuccessRate = res.data
            })
        },
        getDailyStats(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_daily_stats', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.dailyStats = res.data
                console.log(res.data)
            })
        },
        goToExpert(exp){
            this.$router.push({name: 'AdminExpertDetail', params:{id: exp.id}})
        },
        goToSub(sub){
            this.$router.push({name: 'AdminSubscriptionShow', params:{sub: sub.sub_id}})
        },
        goToUser(user){
            this.$router.push({name: 'AdminUserShow', params:{id: user.id}})
        },
        goToForecast(fc){
            this.$router.push({name: 'AdminForecastsByExpertsShow', params:{fc: fc.forecast_id}})

        },
        goToDailyTip(tip){
            this.$router.push({name: 'AdminDailyTipShow', params:{id: tip.id, code: tip.tip_code}})
        }
    },
    created(){
        this.getUsersCounts()
        this.getDailyStats()
        this.getDailyTipSuccessRt()
        this.getTopExperts()
        this.getSubscriptionsToday()
        this.getPayments()
        this.getEarnings()
        this.getNewExperts()
        this.getNewUsers()
        this.getLatestForecasts()
        this.getDailyTips()

    }
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    table tbody tr:hover{
        color: #fbff05;
    }
    .v-card{
        overflow-x: scroll !important;
    }
</style>
