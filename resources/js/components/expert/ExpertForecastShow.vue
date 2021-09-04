<template>
    <v-container>
        <v-row justify="center" class="justify-center">
            <v-col cols="12" md="2">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <expert-top-panel :title="`Forecast ${id}`"/>
            </v-col>
        </v-row>
        <v-row justify="space-around" wrap>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <v-card raised elevation="10" min-height="300">
                        <v-card-title class="sub_title primary white--text justify-center">Forecast Details</v-card-title>
                        <v-card-text class="mt-5">
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th style="border-top: none">Forecast Id:</th>
                                        <td style="border-top: none">{{ summary.id }}</td>
                                    </tr>
                                    <tr>
                                        <th>No of Games:</th>
                                        <td>{{ summary.event_count }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Type:</th>
                                        <td>{{ summary.forecast_odd }} odds</td>
                                    </tr>
                                    <tr>
                                        <th>Opened/Closed:</th>
                                        <td>{{ summary.is_opened ? 'Opened': 'Closed' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Result:</th>
                                        <td v-if="summary.progress == '0'" ><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        <td v-if="summary.progress == '1'" ><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                        <td v-if="summary.progress == '2'"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                    </tr>
                                    <tr v-if="summary.bet9ja">
                                        <th>Bet9ja</th>
                                        <td>{{ summary.bet9ja }}</td>
                                    </tr>
                                    <tr v-if="summary.betking">
                                        <th>BetKing</th>
                                        <td>{{ summary.betking }}</td>
                                    </tr>
                                    <tr v-if="summary.merrybet">
                                        <th>Merrybet</th>
                                        <td>{{ summary.merrybet }}</td>
                                    </tr>
                                    <tr>
                                        <th>Published:</th>
                                        <td>{{ summary.created_at | moment("DD/MM/YY") }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Odds:</th>
                                        <td colspan="2">{{ totalOdds }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </v-card>
                    <v-card raised elevation="10" min-height="200">
                        <v-card-title class="primary white--text sub_title justify-center mt-5">Subscribers</v-card-title>
                        <v-card-text>

                        </v-card-text>
                    </v-card>
                </template>
            </v-col>
            <v-col cols="12" md="6">
                <v-card raised elevation="10" light>
                    <v-card-title class="primary white--text justify-center sub_title">Events</v-card-title>
                    <v-card-text class="pa-3">
                        <div v-for="(event, index) in forecasts" :key="event.id" class="px-3">
                            <div class="subtitle-2 py-1">{{ event.country }}</div>
                            <div class="pb-1">{{ event.event_date | moment("DD/MM/YY") }} - {{ event.event_time }}</div>
                            <v-row>
                                <v-col cols="9"><span class="primary--text body_text">{{ event.home }}</span> vs <span class="primary--text body_text">{{ event.away }}</span></v-col>
                                <v-col cols="3">
                                    <div v-if="event.status == 0" class="status open"></div>
                                    <div v-if="event.status == 1" class="status lost"></div>
                                    <div v-if="event.status == 2" class="status won"></div>
                                </v-col>
                            </v-row>
                            <div class="font-weight-bold">{{ event.tip }}</div>
                            <v-divider v-if="forecasts.length !== index + 1"></v-divider>
                        </div>
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
            id: this.$route.params.id,
            isLoading: false,
            summary: null,
            forecasts: [],
            totalOdds: 0
        }
    },
    computed:{
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
        getSummary(){
            this.isLoading = true
            axios.get(this.api + `/auth-expert/get_expert_forecast_summary/${this.$route.params.id}`, this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.summary = res.data
                let odds = res.data.total_odds
                let total_odds = parseFloat(odds / 100).toFixed(2)
                this.totalOdds = total_odds
                console.log(res.data)
            })
        },
        getForecasts(){
            this.isLoading = true
            axios.get(this.api + `/auth-expert/get_expert_forecasts/${this.$route.params.id}`, this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.forecasts = res.data
                console.log(res.data)
            })
        }
    },
    created(){
        this.getSummary()
        this.getForecasts()
    }
}
</script>

<style lang="css" scoped>
    .status{
        height: .8rem;
        width: .8rem;
    }
    .open{
        background: #e8bc1c;
        border: 1px solid  #f8c305;
    }
    .lost{
        background: #ff2300;
        border: 1px solid  #ff2300;
    }
    .won{
        background: #2bad00;
        border: 1px solid  #2aa501;
    }
</style>
