<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Expert Forecast" />
            </v-col>
        </v-row>
        <v-row class="justify-start mt-5 ml-n10">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text">Forecast {{ forecast_id }} Events </v-card-title>
                    <v-card-text>
                        <template v-if="forecast.length > 0">
                            <v-card raised min-height="100" v-for="(fc, index) in forecast" :key="fc.id" :class="index == 0 ? '' : 'mt-10'">
                                <v-card-text>
                                    <table class="table table-condensed table-hover">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <th width="35%">ID</th>
                                                <td>{{ fc.id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Event</th>
                                                <td>{{ fc.home }} Vs {{ fc.away }}</td>
                                            </tr>
                                            <tr>
                                                <th>Prediction</th>
                                                <td>{{ fc.tip }}</td>
                                            </tr>
                                            <tr>
                                                <th>Odd</th>
                                                <td>{{ fc.odd }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <td>{{ fc.date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Time</th>
                                                <td>{{ fc.time }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td v-if="fc.status == 0"><div class="status nyd"></div></td>
                                                <td v-if="fc.status == 1"><div class="status lost"></div></td>
                                                <td v-if="fc.status == 2"><div class="status won"></div></td>
                                                <!-- <td><v-select label="Change" dense :items="statuses" item-text="status" item-value="id"></v-select></td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </v-card-text>
                                <v-card-text class="d-flex align-items-start">
                                    <v-select solo dense small label="Change Status" :items="statuses" item-text="status" item-value="id" class="ml-3" v-model="newStatus"></v-select>
                                    <v-btn width="30%" dark color="primary rounded darken-2" class="ml-2" @click="changeStatus(fc)" :loading="isBusy">Save</v-btn>
                                </v-card-text>
                            </v-card>
                        </template>
                        <template v-else>
                            <v-alert type="error" border="left" class="mt-5">
                                There are no games in the forecast you are trying to view.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text"> Summary </v-card-title>
                    <v-card-text>
                        <template v-if="forecastSummary">
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">Forecast ID</th>
                                        <td>{{ forecastSummary.forecast_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert</th>
                                        <td v-if="forecastSummary.expert"><router-link :to="{name: 'AdminExpertDetail', params:{id: forecastSummary.expert.id}}">{{ forecastSummary.expert.fullname }} ({{ forecastSummary.expert.username }})</router-link></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td v-if="forecastSummary.progress == '0'" style="text-align: left"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        <td v-if="forecastSummary.progress == '1'" style="text-align: left"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                        <td v-if="forecastSummary.progress == '2'" style="text-align: left"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                    </tr>
                                    <tr>
                                        <th>Odd Categ</th>
                                        <td>{{ forecastSummary.forecast_odd }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Odds</th>
                                        <td>{{ forecastSummary.total_odds | formatOdds }}</td>
                                    </tr>
                                    <tr v-if="forecastSummary.bet9ja">
                                        <th>Bet9ja code</th>
                                        <td>{{ forecastSummary.bet9ja }}</td>
                                    </tr>
                                    <tr v-if="forecastSummary.betking">
                                        <th>BetKing code</th>
                                        <td>{{ forecastSummary.betking }}</td>
                                    </tr>
                                    <tr v-if="forecastSummary.merrybet">
                                        <th>Merrybet code</th>
                                        <td>{{ forecastSummary.merrybet }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="error" border="left" class="mt-5">
                                The forecast you are trying to view does not exist.
                            </v-alert>
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
            forecast_id: this.$route.params.fc,
            expert_id: this.$route.params.expert,
            forecast: [],
            forecastSummary: {},
            isLoading: false,
            statuses: [
                {id: 0, status: 'Not Decided'},
                {id: 1, status: 'Lost'},
                {id: 2, status: 'Won'},
            ],
            newStatus: null,
            isBusy: false
        }
    },
    computed: {
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
        getForecast(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_single_expert_forecast/${this.$route.params.fc}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.forecast = res.data
            })
        },
        viewGame(fc){
            this.$router.push({name: 'AdminSingleExpertEvent', params: {id: fc.id}})
        },
        changeStatus(fc){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/change_event_status/${fc.id}`, {
                status: this.newStatus
            }, this.adminHeaders)
            .then((res) => {
                this.isBusy = false
                let event = this.forecast.filter((item)=>item.id == fc.id)
                fc.status = res.data.status
                this.newStatus = null
                // console.log(res.data)
            })
        },
        getForecastSummary(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert_forecast_summary/${this.$route.params.fc}`, this.adminHeaders)
            .then((res)=>{
                this.isLoading = false
                this.forecastSummary = res.data
                console.log(res.data)
            })
        }
    },
    created(){
        this.getForecast()
        this.getForecastSummary()
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    .v-card .v-card__text{
        overflow: scroll !important;
    }
    .status{
        text-align: center;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    .nyd{
        background: #ffa501;
        border-radius: 1px solid #f59f02;
    }
    .lost{
        background: #f3420d;
        border-radius: 1px solid #f3420d;
    }
    .won{
        background: #00b900;
        border-radius: 1px solid #01a201;
    }
</style>

