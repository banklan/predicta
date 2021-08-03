<template>
    <v-container>
        <admin-top-panel title="Expert Forecasts" />
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="sub_title primary white--text justify-center">Expert Forecasts</v-card-title>
                    <v-card-text>
                        <template v-if="forecasts.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Forecast ID</th>
                                        <th>Expert</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="expert_list">
                                    <tr v-for="(fc, i) in forecasts" :key="fc.id">
                                        <td @click="showForecast(fc)">{{ fc.published }}</td>
                                        <td @click="showForecast(fc)">{{ fc.forecast_id }}</td>
                                        <td @click="showForecast(fc)" v-if="fc.expert">{{ fc.expert.username }}</td>
                                        <td @click="showForecast(fc)">{{ fc.forecast_odd }}</td>
                                        <td v-if="fc.progress == '0'" style="text-align: left"><v-icon color="red darken-2">mdi-close</v-icon> </td>
                                        <td v-if="fc.progress == '1'" style="text-align: left"><v-icon color="green darken-1">mdi-check-all</v-icon> </td>
                                        <td v-if="fc.progress == '2'" style="text-align: left"><v-icon color="orange darken-2">mdi-minus</v-icon> </td>
                                        <td><v-btn small text color="red darken-2" @click="confirmDel(fc, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left">
                                There are currently no expert forecasts.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5 justify-space-around">
                        <span class="pl-4 pb-2">
                            <v-btn color="primary" @click.prevent="getForecasts(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getForecasts(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getForecasts(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getForecasts(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center pt-8 mb-4">Do you really want to delete this forecast?</v-card-title>
                <v-card-text class="text-center mt-4 subtitle-1">
                    If you proceed to delete, the forecast and all the games in the acca will be deleted permanently.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="deleteForecast" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="forecastDeleted" :timeout="4000" top color="green darken-1 white--text">
            Forecast has been deleted successfully.
            <v-btn text color="white--text" @click="forecastDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            confirmDelDial: false,
            forecasts: [],
            pagination: {},
            forecastToDelIndex: null,
            forecastToDel: null,
            isDeleting: false,
            forecastDeleted: false
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
        confirmDel(fc, i){
            this.forecastToDel = fc
            this.forecastToDelIndex = i
            this.confirmDelDial = true
        },
        getForecasts(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/admin_get_paginated_forecasts`
            this.isLoading = true
            axios.get(pag, this.adminHeaders)
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
            }).catch(() => {
                this.isLoading = false
            })
        },
        showForecast(fc){
            this.$router.push({name: 'AdminForecastsByExpertsShow', params: {fc: fc.forecast_id}})
        },
        deleteForecast(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_del_expert_forecast/${this.forecastToDel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.forecasts.splice(this.forecastToDelIndex, 1)
                this.forecastDeleted = true
                this.confirmDelDial = false
            })
        }
    },
    created() {
        this.getForecasts()
    },
}
</script>

<style lang="css" scoped>
    table .expert_list tr td{
        cursor: pointer;
    }
    .scroll .v-card__text{
        overflow: scroll !important;
    }
</style>
