<template>
    <v-container>
        <v-row class="mb-n3 mt-1" justify="space-around">
            <v-col cols="3">
                <v-btn dark small rounded color="primary darken-2" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9"></v-col>
        </v-row>
        <v-row class="ml-n10 mt-5">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="fcs.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Expert Forecasts <v-chip color="primary lighten-2" v-if="fcs.length > 0">{{ fcs.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Forecast ID</th>
                                        <th>Expert</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(fc, i) in fcs" :key="fc.id">
                                        <td @click="showForecast(fc)">{{ fc.published }}</td>
                                        <td @click="showForecast(fc)" class="primary--text font-weight-bold">{{ fc.forecast_id }}</td>
                                        <td @click="showForecast(fc)" class="primary--text font-weight-bold" v-if="fc.expert">{{ fc.expert.username }}</td>
                                        <td @click="showForecast(fc)">{{ fc.forecast_odd }}</td>
                                        <td v-if="fc.progress == '0'" style="text-align: left"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        <td v-if="fc.progress == '1'" style="text-align: left"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                        <td v-if="fc.progress == '2'" style="text-align: left"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search returned no expert forecasts.
                            </v-alert>
                        </v-card-text>
                    </template>
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
            users: []
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
        },
    },
    methods: {
        getResult(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/admin_search_for_expert_forecasts', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.fcs = res.data
            })
        },
         showForecast(fc){
            this.$router.push({name: 'AdminForecastsByExpertsShow', params: {fc: fc.forecast_id}})
        },
    },
    created() {
        this.getResult()
    },
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td{
        white-space: nowrap;
    }
</style>
