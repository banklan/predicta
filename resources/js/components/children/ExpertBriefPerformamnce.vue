<template>
    <div>
       <v-card light raised outlined elevation="4" min-height="150" class="mt-5">
            <v-card-title class="primary white--text justify-center subtitle-1">Expert Forecasts</v-card-title>
            <v-card-text class="mt-4">
                <table class="table table-condensed table-striped table-hover">
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
                            <td>{{ perc_won > 0 ? perc_won : 0}}</td>
                        </tr>
                        <tr>
                            <th class="primary--text go_to" @click="goToSub">Total Subscriptions:</th>
                            <td>{{ count }}</td>
                        </tr>
                        <tr>
                            <th>Active Subscriptions:</th>
                            <td>{{ active.length }}</td>
                        </tr>
                        <tr>
                            <th>Total Earned:</th>
                            <td>&#8358;{{ settled | price }}</td>
                        </tr>
                        <tr>
                            <th>Outstanding Payment:</th>
                            <td>&#8358;{{ outstanding | price }}</td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
            <v-card-actions class="justify-center pb-8 mx-3">
                <v-btn block dark color="primary darken-2" :to="{name: 'AdminExpertForecasts', params:{id: expert}}">View Expert Forecasts</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['expert', 'active', 'count'],
    data() {
        return {
            total: null,
            running: null,
            total_won: null,
            perc_won: null,
            settled: 0,
            outstanding: 0,
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
        getForecasts(){
            axios.get(this.api + `/auth-admin/get_expert_forecast_summary/${this.expert}`, this.adminHeaders)
            .then((res)=>{
                console.log(res.data)
                this.total = res.data.length
                let running = res.data.filter((item) => item.is_opened === true)
                this.running = running.length
                let won = res.data.filter((item)=> item.progress === '1')
                let total_won = won.length
                this.total_won = total_won
                let perc = (total_won * 100) / this.total
                this.perc_won = Math.round(perc)
            })
        },
        getEarning(){
            axios.get(this.api + `/auth-admin/admin_get_expert_earnings/${this.expert}`, this.adminHeaders)
            .then((res) => {
                // console.log(res.data)
                this.settled = res.data.settled / 100
                this.outstanding = res.data.outstanding / 100
            })
        },
        goToSub(){
            this.$router.push({name: 'AdminExpertSubscriptionsDetail', params: {id: this.expert}})
        }
    },
    created() {
        this.getForecasts()
        this.getEarning()
    },
}
</script>

<style lang="css" scoped>
    table tr .go_to{
        cursor: pointer;
    }
</style>
