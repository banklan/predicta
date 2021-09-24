<template>
    <v-container>
        <v-row class="mt-2" justify="space-around">
            <v-col cols="12" md="6" offset-md="6">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="Dashboard" />
            </v-col>
        </v-row>
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else justify="space-around" wrap class="mt-5">
            <v-col cols="12" md="7">
                <v-card elevation="6" light raised min-height="200" class="scroll">
                    <v-card-title class="justify-center primary white--text subtitle-1">My Subscriptions</v-card-title>
                    <v-card-text class="">
                        <template v-if="subscriptions.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Odd Cat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sub in subscriptions.slice(0, 5)" :key="sub.id">
                                        <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                        <td class="primary--text font-weight-bold" @click="goToSub(sub)">{{ sub.sub_id }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td>{{ sub.expiry_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                You have no subscriptions at the moment.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n5" v-if="subscriptions.length > 0">
                        <v-btn large text color="primary darken-2" :to="{name: 'Subscriptions'}">View All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card elevation="6" light raised min-height="200" class="">
                    <v-card-title class="justify-center primary white--text subtitle-1">Top Tip Experts</v-card-title>
                    <v-card-text class="">
                        <template v-if="hotExperts.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Expert</th>
                                        <th>Win Rate(%)</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="exp in hotExperts" :key="exp.id" @click="goToTipExpert(exp)">
                                        <td>{{ exp.username }}</td>
                                        <td align="center">{{ exp.winning_rate }}</td>
                                        <td align="center">{{ exp.open_events_count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n4">
                        <v-btn text large color="primary darken-2" :to="{name: 'AllTipExperts'}">View All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            subscriptions: [],
            hotExperts: [],
            isLoading: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        isLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getSubscriptions(){
            this.isLoading = true
            axios.get(this.api + '/auth/get_my_subscriptions', this.authHeaders).then((res)=>{
                this.isLoading = false
                this.subscriptions = res.data
                // console.log(res.data)
            })
        },
        goToSub(sub){
            this.$router.push({name: 'SubscriptionView', params:{sub_id: sub.sub_id}})
        },
        getHopTipExperts(){
            this.isLoading = true
            axios.get(this.api + '/auth/get_hot_tip_experts', this.authHeaders).then((res) => {
                this.isLoading = false
                this.hotExperts = res.data
                // console.log(res.data)
            })
        },
        goToTipExpert(exp){
            this.$router.push({name: 'TipExpertView', params:{id: exp.expert_id}})
        }
    },
    created() {
        this.getSubscriptions()
        this.getHopTipExperts()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !Important;
    }
    table tbody tr{
        cursor: pointer;
    }
</style>
