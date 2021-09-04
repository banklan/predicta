<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Expert Subscriptions Details" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <template v-if="expert">
                        <v-card-title class="subtitle-1 primary white--text justify-center">Expert Details </v-card-title>
                        <v-card-text class="subtitle-2 mt-1">
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr v-if="expert">
                                        <th>Expert ID:</th>
                                        <td>{{ expert.expert_id }}</td>
                                    </tr>
                                    <tr v-if="expert">
                                        <th>Expert Username:</th>
                                        <td>{{ expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscriptions:</th>
                                        <td>{{ subscriptions.length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Winning Rate(%):</th>
                                        <td>{{ expert.winning_rate }}</td>
                                    </tr>
                                    <tr>
                                        <th v-if="expert">Total Earning(&#8358;):</th>
                                        <td>{{ expert.total_earning / 100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Outstanding Payment(&#8358;):</th>
                                        <td>{{ outstanding | price }}</td>
                                    </tr>
                                   <tr v-if="expert">
                                        <th>Open Forecasts:</th>
                                        <td>{{ expert.open_events_count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                 <v-card v-if="!isLoading" light raised elevation="8" min-height="200" class="scroll">
                     <v-card-title class="justify-center subtitle-1 primary white--text">Earnings</v-card-title>
                     <v-card-text>
                         <template v-if="earnings.length > 0">
                            <table class="table table-condensed table-hover table-striped scroll">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Amount(&#8358;)</th>
                                        <th>Settled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="earn in earnings" :key="earn.id">
                                        <td>{{ earn.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ earn.subscription_id }}</td>
                                        <td>{{ earn.exp_amount / 100 | price }}</td>
                                        <td v-if="earn.is_settled"><v-icon color="green darken-2">mdi-check-all</v-icon></td>
                                        <td v-else><v-icon color="red darken-2">mdi-close</v-icon></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                There are no earnings for this expert yet.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="start" class="mn-n10">
            <v-col cols="12" md="12">
                 <v-card v-if="!isLoading" light raised elevation="8" min-height="200" class="scroll mt-5">
                     <v-card-title class="justify-center subtitle-1 primary white--text">Subscriptions</v-card-title>
                     <v-card-text>
                         <template v-if="subscriptions.length > 0">
                            <table class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Cat</th>
                                        <th>Amount</th>
                                        <th>Expiry</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sub in subscriptions" :key="sub.id">
                                        <td>{{ sub.created_at | moment('DD/MM/YY - H:ma') }}</td>
                                        <td>{{ sub.sub_id }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td>{{ sub.amount / 100 | price }}</td>
                                        <td>{{ sub.expiry | moment('DD/MM/YY - H:ma') }}</td>
                                        <td>{{ sub.is_active ? 'Active' : 'InActive' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                There are no subscriptions for this expert yet.
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
            id: this.$route.params.id,
            expert: null,
            subscriptions: [],
            earnings: [],
            isLoading: false,
            settled: 0,
            outstanding: 0,
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
        getExpertSubscriptions(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert_subs/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.subscriptions = res.data
            })
        },
        getExpertDetails(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.expert = res.data
                // console.log(res.data)
            })
        },
        getExpertEarnings(){
            axios.get(this.api + `/auth-admin/get_expert_earnings/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.earnings = res.data
                console.log(res.data)
            })
        },
        getExpertOutstandingEarning(){
            axios.get(this.api + `/auth-admin/admin_get_expert_earnings/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.settled = res.data.settled / 100
                this.outstanding = res.data.outstanding / 100
            })
        },
        // goToSub(sub){
        //     this.$router.push({name: 'AdminExpertSubscriptionsDetail', params: {id: sub.expert_id}})
        // }
    },
    created() {
        this.getExpertDetails()
        this.getExpertSubscriptions()
        this.getExpertEarnings()
        this.getExpertOutstandingEarning()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }

</style>
