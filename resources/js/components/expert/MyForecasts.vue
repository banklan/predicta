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
                            <template v-if="forecasts.length > 0">
                                <table class="table table-condensed tabe-hover table-striped table-bordered">
                                    <thead>
                                        <tr class="primary white--text text-center caption">
                                            <th>Date</th>
                                            <th>Pred ID</th>
                                            <th>No of Games</th>
                                            <th>Odd</th>
                                            <th>Opened?</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="forecast in forecasts" :key="forecast.id" style="text-align:center">
                                            <td>{{ forecast.created_at | moment('DD/MM/YY') }} </td>
                                            <td><router-link :to="{name: 'ExpertForecastShow', params:{id: forecast.forecast_id}}">{{ forecast.forecast_id }} </router-link></td>
                                            <td>{{ forecast.event_count }} </td>
                                            <td>{{ forecast.forecast_odd }} </td>
                                            <td>{{ forecast.is_opened ? 'Yes' : 'No' }}</td>
                                            <td v-if="forecast.progress == '0'" style="text-align: center"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                            <td v-if="forecast.progress == '1'" style="text-align: center"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                            <td v-if="forecast.progress == '2'" style="text-align: center"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <v-card-actions class="my-5">
                                    <span class="pl-4">
                                        <v-btn color="primary" @click.prevent="getForecasts(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getForecasts(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getForecasts(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getForecasts(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                                    </span>
                                    <span class="pl-8">
                                        Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                    </span>
                                </v-card-actions>
                            </template>
                            <div v-else>
                                <v-alert type="info">You have not created any forecasts yet.</v-alert>
                            </div>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card raised elevation="12" min-height="40">
                    <v-card-title class="primary white--text sub_title justify-center">At a glance</v-card-title>
                    <v-card-text class="">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Forecasts:</th>
                                    <td>{{ total }}</td>
                                </tr>
                                <tr>
                                    <th>Running:</th>
                                    <td>{{ running }}</td>
                                </tr>
                                <tr>
                                    <th>% Won:</th>
                                    <td>{{ perc_won }}%</td>
                                </tr>
                            </tbody>
                        </table>
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
            forecasts: [],
            pagination: {},
            total: null,
            running: null,
            perc_won: null,
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetExpertFlashMsgs')
        next()
    },
    methods: {
        getForecasts(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-expert/get_pngtd_expert_summaries`
            axios.get(pag, this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.forecasts = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        getForecastSummary(){
            axios.get(this.api + '/auth-expert/get_forecast_summary', this.expertHeader)
            .then((res) => {
                let running = res.data.filter((item) => item.progress_status === 0)
                this.running = running.length
                let won = res.data.filter((item)=> item.progress === '1')
                let perc = (won.length * 100) / this.total
                this.perc_won = Math.round(perc)
                console.log(res.data)
            })
        }
    },
    created(){
        this.getForecasts()
        this.getForecastSummary()
    }
}
</script>

<style lang="css" scoped>
    .v-card .v-card__text{
        overflow: scroll !important;
    }
</style>
