<template>
     <v-container>
        <v-row class="mt-5">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" :title="`Subscription ${sub_id}`" />
            </v-col>
        </v-row>
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row justify="center" v-else>
            <v-col cols="12" md="6">
                <v-card light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-title class="primary darken-2 white--text subtitle-1 justify-center">Events</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="forecast.predictions.length > 0">
                            <template v-for="pred in forecast.predictions">
                                <v-card :key="pred.id">
                                    <table class="table table-condensed table-hover sub_table">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <th>Country</th>
                                                <td>{{ pred.country }}</td>
                                            </tr>
                                            <tr>
                                                <th>League</th>
                                                <td>{{ pred.league }}</td>
                                            </tr>
                                            <tr>
                                                <th>Event Date/Time</th>
                                                <td>{{ pred.date }} : {{ pred.time }}</td>
                                            </tr>
                                            <tr>
                                                <th>Event</th>
                                                <td>{{ pred.home }} VS {{ pred.away }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tip</th>
                                                <td>{{ pred.tip }}</td>
                                            </tr>
                                            <tr>
                                                <th>Odd</th>
                                                <td>{{ pred.odd }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </v-card>
                            </template>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The forecasts you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols='12' md="5">
                <v-card light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Forecast Detail </v-card-title>
                    <v-card-text class="mt-5">
                        <table class="table table-condensed table-hover table-striped">
                            <thead></thead>
                            <tbody>
                                <template v-if="forecast">
                                    <tr>
                                        <th>Tip Expert:</th>
                                        <td>{{ forecast.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Forecast ID:</th>
                                        <td>{{ forecast.forecast_id}}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Category</th>
                                        <td>{{ forecast.forecast_odd}}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd</th>
                                        <td>{{ forecast.total_odds | formatOdds }}</td>
                                    </tr>
                                    <tr>
                                        <th>No of Events</th>
                                        <td>{{ forecast.event_count }}</td>
                                    </tr>
                                    <tr v-if="forecast.bet9ja">
                                        <th>Bet9ja Booking code</th>
                                        <td>{{ forecast.bet9ja }}</td>
                                    </tr>
                                    <tr v-if="forecast.betking">
                                        <th>Betking Booking code</th>
                                        <td>{{ forecast.betking }}</td>
                                    </tr>
                                    <tr v-if="forecast.merrybet">
                                        <th>Merrybet Booking code</th>
                                        <td>{{ forecast.merrybet }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="tipSubscribed" :timeout="5000" top color="green darken-1 white--text">
            Thank you for subscribing.
            <v-btn text color="white--text" @click="tipSubscribed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            sub_id: this.$route.params.sub_id,
            pred_id: this.$route.params.pred_id,
            isLoading: false,
            subscription: null,
            forecast: null
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        tipSubscribed(){
            return this.$store.getters.tipSubscribed
        }
    },
    methods: {
        getSubscription(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_subscription/${this.$route.params.sub_id}`, this.authHeaders)
            .then((res)=> {
                this.isLoading = false
                this.subscription = res.data
                console.log(res.data)
            })
        },
        getForecast(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_forecast_summary/${this.$route.params.pred_id}`, this.authHeaders).then((res) => {
                this.isLoading = false
                this.forecast = res.data
                console.log(res.data)
            })
        },

    },
    created() {
        this.getForecast()
        // this.getSubscription()
    },
}
</script>

<style lang="css" scoped>
    table.sub_table tr{
        cursor: pointer;
    }
</style>
