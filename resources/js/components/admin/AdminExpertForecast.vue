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
        <v-row class="justify-start mt-5 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center subtitle-1 primary darken-2 white--text">Forecast {{ forecast_id }} </v-card-title>
                    <v-card-text>
                        <template v-if="forecast.length > 0">
                            <template v-for="(fc, index) in forecast">
                                <admin-expert-forecast-single-card :fc="fc" :index="index" :key="fc.id" />
                            </template>

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
                    <v-card-title class="justify-center subtitle-1 primary darken-2 white--text"> Summary </v-card-title>
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
                                    <tr>
                                        <th>Availability</th>
                                        <td>{{ forecastSummary.is_available ? 'Available' : 'Not Available' }}</td>
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
                    <v-card-actions class="justify-center pb-6">
                        <v-btn color="primary darken-2" @click="toggleAvailDial = true">{{ forecastSummary.is_available ? 'Make Unavailable' : 'Make Available'}}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="toggleAvailDial" max-width="500">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Make this forecast {{ forecastSummary.is_available ? ' not available': ' available' }} for Subscription?</v-card-title>
                <v-card-actions class="pb-8 justify-center mt-5">
                    <v-btn text color="red darken--2" @click="toggleAvailDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="toggleAvailability">Yes, Make {{ forecastSummary.is_available ? ' not available': ' available' }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="availUpdated" :timeout="4000" top color="green darken-1 white--text">
            The forecast availability has been updated successfully.
            <v-btn text color="white--text" @click="availUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="availUpdtFail" :timeout="6000" top color="red darken-1 white--text">
            there was an error while trying to update the forecast availability. Please try again.
            <v-btn text color="white--text" @click="availUpdtFail = false">Close</v-btn>
        </v-snackbar>
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
            toggleAvailDial: false,
            isUpdating: false,
            availUpdated: false,
            availUpdtFail: false
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
        getForecastSummary(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert_forecast_summary/${this.$route.params.fc}`, this.adminHeaders)
            .then((res)=>{
                this.isLoading = false
                this.forecastSummary = res.data
                // console.log(res.data)
            })
        },
        toggleAvailability(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_toggle_forecast_availability/${this.$route.params.fc}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.toggleAvailDial = false
                this.availUpdated = true
                this.forecastSummary.is_available = res.data
            }).catch(() => {
                this.isUpdating = false
                this.availUpdtFail = true
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
        overflow-x: scroll !important;
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

