<template>
    <v-container>
        <v-row justify="space-between" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Expert Forecast" />
            </v-col>
        </v-row>
       <v-row class="justify-start ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="7">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center subtitle-1 primary darken-2 white--text">Forecasts for Expert </v-card-title>
                    <v-card-text>
                        <template v-if="forecasts.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Forecast ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="forecast in forecasts" :key="forecast.id" @click="openForecast(forecast)">
                                        <td>{{ forecast.published }}</td>
                                        <td>{{ forecast.forecast_id }}</td>
                                        <td v-if="forecast.progress == '0'" style="text-align: left"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        <td v-if="forecast.progress == '1'" style="text-align: left"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                        <td v-if="forecast.progress == '2'" style="text-align: left"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are no forecasts for this expert user.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text">Expert Summary </v-card-title>
                    <v-card-text v-if="expert">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Fullname:</th>
                                    <td>{{ expert.fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td>{{ expert.username }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ expert.email }}</td>
                                </tr>
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
                                    <td>{{ perc_won > 0 ? perc_won : 0}}</td>
                                </tr>
                                <tr>
                                    <th>Total Subscriptions:</th>
                                    <td>122</td>
                                </tr>
                            </tbody>
                        </table>
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
            id: this.$route.params.id,
            forecasts: [],
            expert: null,
            total: null,
            running: null,
            total_won: null,
            perc_won: null,
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
    methods:{
        getExpertForecasts(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_expert_forecast_summary/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.forecasts = res.data
                this.total = res.data.length
                let running = res.data.filter((item) => item.is_opened === true)
                this.running = running.length
                let won = res.data.filter((item)=> item.progress === '1')
                let total_won = won.length
                this.total_won = total_won
                let perc = (total_won * 100) / this.total
                this.perc_won = Math.round(perc)
            }).catch((err)=>{
                this.isLoading = false
                console.log(err)
            })
        },
        getExpert(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert/${this.$route.params.id}`, this.adminHeaders)
                .then((res) => {
                    this.isLoading = false
                    this.expert = res.data
                })
        },
        openForecast(forecast){
            console.log(forecast)
            this.$router.push({name: 'AdminExpertForecast', params: {expert: this.id, fc: forecast.forecast_id}})
        }
    },
    created() {
        this.getExpertForecasts()
        this.getExpert()
    },
}
</script>


<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    .v-card .v-card__text{
        overflow: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
</style>
