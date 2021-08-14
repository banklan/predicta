<template>
    <v-container>
        <expert-top-panel />
        <v-row justify="space-around" wrap class="mt-5">
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised min-height="300" color="#f72585">
                    <v-card-title class="white--text sub_title justify-center">My Forecasts</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Total Forecasts:</th>
                                    <td>{{ total }}</td>
                                </tr>
                                <tr>
                                    <th>Correct Forecasts:</th>
                                    <td>{{ total_won }}</td>
                                </tr>
                                <tr>
                                    <th>Winning Rate(%)</th>
                                    <td>{{ perc_won }}</td>
                                </tr>
                                <tr>
                                    <th>Total Subscriptions:</th>
                                    <td>122</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n4">
                        <v-btn outlined color="white" :to="{name: 'MyForecasts'}">My Forecasts</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised color="#1d9478" min-height="300">
                    <v-card-title class="white--text sub_title justify-center">Current Forecasts</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Total Forecasts:</th>
                                    <td>{{ total }}</td>
                                </tr>
                                <tr>
                                    <th>Total Won</th>
                                    <td>{{ total_won }}</td>
                                </tr>
                                <tr>
                                    <th>Running</th>
                                    <td>{{ running }}</td>
                                </tr>
                                <tr>
                                    <th>Earnings</th>
                                    <td>&#8358;10,500</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-6 mt-n4">
                        <v-btn outlined color="white">Current Forecast</v-btn>
                        <v-btn outlined color="white" :to="{name: 'NewForecast'}">New Forecast</v-btn>
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
            total: null,
            total_won: null,
            running: null,
            perc_won: null
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
            axios.get(this.api + '/auth-expert/get_forecast_summary', this.expertHeader)
            .then((res) => {
                this.total = res.data.length
                let running = res.data.filter((item) => item.progress === 2)
                this.running = running.length
                let won = res.data.filter((item)=> item.progress === '1')
                let total_won = won.length
                this.total_won = total_won
                let perc = (total_won * 100) / this.total
                this.perc_won = Math.round(perc)
                console.log(res.data)
            })
        }
    },
    created(){
        this.getForecastSummary()
    }
}
</script>

<style lang="css" scoped>
    table tr{
        color: #fff !important;
    }
</style>
