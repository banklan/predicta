<template>
    <v-container>
        <expert-top-panel title="Expert Dashboard"/>
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else justify="center" wrap class="mt-5">
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised min-height="300" color="#3225f7">
                    <v-card-title class="white--text sub_title justify-center">Forecasts Briefs</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Total Forecasts:</th>
                                    <td>{{ total }}</td>
                                </tr>
                                <tr>
                                    <th>Subscriptions:</th>
                                    <td>{{ subscriptions.length }}</td>
                                </tr>
                                <tr>
                                    <th>Open Forecasts:</th>
                                    <td>{{ opened }}</td>
                                </tr>
                                <tr>
                                    <th>Expired Forecasts:</th>
                                    <td>{{ played }}</td>
                                </tr>
                                <tr>
                                    <th>Forecast Won:</th>
                                    <td>{{ total_won }}</td>
                                </tr>
                                <tr>
                                    <th>Forecast Lost:</th>
                                    <td>{{ total_lost }}</td>
                                </tr>
                                <tr>
                                    <th>Winning Rate(%)</th>
                                    <td>{{ perc_won }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n4">
                        <v-btn outlined color="white" :to="{name: 'MyForecasts'}">My Forecasts</v-btn>
                    </v-card-actions>
                </v-card>
                <v-card elevation="8" dark raised min-height="300" class="fc_perf mt-5">
                    <v-card-title class="white--text sub_title justify-center">Forecast Performance</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Forecast ID</th>
                                    <th>Cat</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fc in perfs" :key="fc.id" @click="goToFc(fc)">
                                    <td>{{ fc.created_at | moment('D/M/YY') }}</td>
                                    <td>{{ fc.forecast_id }}</td>
                                    <td>{{ fc.forecast_odd }}</td>
                                    <td v-if="fc.prog_status == 2"><v-icon color="green darken-1">mdi-check-all</v-icon></td>
                                    <td v-if="fc.prog_status == 1"><v-icon color="red darken-2">mdi-close</v-icon></td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised color="#047149" min-height="100" class="mb-5 latest_sub">
                    <v-card-title class="white--text sub_title justify-center">Latest Subscriptions</v-card-title>
                    <v-card-text class="">
                        <table class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Sub ID</th>
                                    <th>Cat</th>
                                    <th>Subscriber</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sub in subscriptions.slice(0, 5)" :key="sub.id">
                                    <td>{{ sub.created_at | moment('D/M/YY') }}</td>
                                    <td @click="goToSub(sub)">{{ sub.sub_id }}</td>
                                    <td>{{ sub.odd_cat }}</td>
                                    <td v-if="sub.user">{{ sub.user.fullname }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn outlined color="white--text" :to="{name: 'ExpertSubscriptions' }">All Subscriptions</v-btn>
                    </v-card-actions>
                </v-card>
                <v-card elevation="8" dark raised color="#f3137a" min-height="100" class="earnings">
                    <v-card-title class="white--text sub_title justify-center">Earnings</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <template v-if="earnings.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="earn in earnings.slice(0, 5)" :key="earn.id">
                                        <td>{{ earn.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ earn.subscription_id }}</td>
                                        <td>{{ earn.exp_amount / 100 | price }}</td>
                                        <td>{{ earn.is_settled == 1 ? 'Settled' : 'Not Settled' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                You have no outstanding earnings.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-6 mt-n4" v-if="earnings.length > 0">
                        <v-btn outlined color="white" :to="{name: 'ExpertEarnings'}">View All Earnings</v-btn>
                    </v-card-actions>
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
            opened: null,
            played: null,
            total: null,
            total_won: null,
            total_lost: null,
            perc_won: null,
            opened_subs: null,
            expired_subs: null,
            earnings: [],
            oustanding_earnings: [],
            subscriptions: [],
            perfs: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authExpert(){
            return this.$store.getters.authExpert
        },
        expertHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authExpert.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getForecastSummary(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_forecast_summary', this.expertHeader)
            .then((res) => {
                // console.log(res.data)
                this.isLoading = false
                this.total = res.data.length
                let opened = res.data.filter((item) => item.is_available)
                this.opened = opened.length > 0 ? opened.length : 0
                this.played = parseInt(this.total - opened.length)
                let won = res.data.filter((item)=> item.progress === '1')

                let total_won = won.length
                this.total_won = total_won
                this.total_lost = this.played - total_won
                if(this.total > 0 && this.played > 0){
                    let perc = (total_won * 100) / this.played
                    this.perc_won = Math.round(perc)
                }else{
                    this.perc_won = 0
                }
            })
        },
        getExpertSubscriptions(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_subscriptions', this.expertHeader).then((res) => {
                // console.log(res.data)
                this.isLoading = false
                this.subscriptions = res.data
                this.total_subs = res.data.length
                let open = res.data.filter((item)=> item.status == 1)
                this.opened_subs = open.length
                this.expired_subs = this.total_subs - open.length

            })
        },
        getExpertEarnings(){
            axios.get(this.api + '/auth-expert/get_expert_earnings', this.expertHeader).then((res) => {
                this.earnings = res.data
                this.oustanding_earnings = this.earnings.filter((item) => item.is_settled == 0)
            })
        },
        goToSub(sub){
            this.$router.push({name: 'ExpertSubscriptionView', params: {id: sub.sub_id}})
        },
        getExpertPerformance(){
            axios.get(this.api + '/auth-expert/get_forecast_performance', this.expertHeader)
            .then((res) => {
                console.log(res.data)
                this.perfs = res.data
            })
        },
        goToFc(fc){
            this.$router.push({name: 'ExpertForecastShow', params: {id: fc.forecast_id}})
        }
    },
    created(){
        this.getExpertSubscriptions()
        this.getForecastSummary()
        this.getExpertEarnings()
        this.getExpertPerformance()
    }
}
</script>

<style lang="css" scoped>
    v-card{
        overflow-x: scroll;
    }
    table tr{
        color: #fff !important;
    }
    table tbody tr{
        cursor: pointer !important;
    }
    .latest_sub{
        background-image: linear-gradient(to bottom right,rgb(8 60 2 / 95%), rgb(10 16 5 / 82%));
    }
    .fc_perf{
        background-image: linear-gradient(to bottom right,rgb(14 2 60 / 95%), rgb(0 60 70 / 80%));
    }
    .earnings{
        background-image: linear-gradient(to bottom right, rgb(243, 19, 122), rgb(162 10 87 / 80%));
    }
</style>
