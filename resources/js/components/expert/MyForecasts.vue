<template>
     <v-container>
        <expert-top-panel title="My Forecasts"/>
        <v-row justify="space-around" wrap class="mt-5">
            <v-col cols="12" md="8">
                <v-card raised elevation="12" min-height="40">
                    <v-card-title></v-card-title>
                    <v-card-text>
                        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
                        <template v-else>
                            <v-simple-table v-if="forecasts.length > 0" light fixed-header height="300">
                                <template v-slot:default>
                                    <thead color="primary darken-2">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pred ID</th>
                                            <th>No of Games</th>
                                            <th>Progress</th>
                                            <th>Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="forecast in forecasts" :key="forecast.id">
                                            <td>{{ forecast.created_at | moment('DD/MM/YY') }} </td>
                                            <td>{{ forecast.forecast_id }} </td>
                                            <td>{{ forecast.event_count }} </td>
                                            <td>{{ forecast.status }} </td>
                                            <td v-if="forecast.result == 'O'"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                            <td v-if="forecast.ev_result == 'L'"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                            <td v-if="forecast.ev_result == 'W'"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                            <div v-else>
                                <v-alert type="info">You have not created any forecasts yet.</v-alert>
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="newForecast" :timeout="6000" top color="green darken-1 white--text">
            Your new forecast has been submitted. Thank you.
            <v-btn text color="white--text" @click="newForecast = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            forecasts: []
        }
    },
    computed:{
        newForecast(){
            return this.$store.getters.newForecast
        },
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
        getForecasts(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_summaries', this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.forecasts = res.data
                console.log(res.data)
            })
        }
    },
    created(){
        this.getForecasts()
    }
}
</script>
