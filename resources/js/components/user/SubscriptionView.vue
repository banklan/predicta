<template>
     <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" :title="`Subscription ${sub_id}`" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="7">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="mt-5 scroll">
                    <v-card-text>
                        <template v-if="subscription">
                            <table class="table table-condensed table-hover table-striped sub_table">
                                <thead>
                                    <tr>
                                        <th style="border-top: none">Forecast ID</th>
                                        <th style="border-top: none">Total Odd</th>
                                        <th style="border-top: none">No of Events</th>
                                        <th style="border-top: none">Bet9ja</th>
                                        <th style="border-top: none">BetKing</th>
                                        <th style="border-top: none">Merrybet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="event in subscription.events" :key="event.id" @click="showEvent(event)">
                                        <td class="primary--text">{{ event.forecast_id }}</td>
                                        <td>{{ event.total_odds | formatOdds }}</td>
                                        <td>{{ event.event_count }}</td>
                                        <td>{{ event.bet9ja }}</td>
                                        <td>{{ event.betking }}</td>
                                        <td>{{ event.merrybet }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The subscriptions you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Subscription Detail </v-card-title>
                    <v-card-text class="mt-5">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody v-if="subscription">
                                <tr>
                                    <th style="border-top: none">Subscription ID:</th>
                                    <td style="border-top: none">{{ subscription.sub_id }}</td>
                                </tr>
                                <tr>
                                    <th>Tip Expert:</th>
                                    <td>{{ subscription.expert && subscription.expert.username }}</td>
                                </tr>
                                <tr>
                                    <th>Odd Cat:</th>
                                    <td>{{ subscription.odd_cat }}</td>
                                </tr>
                                <tr>
                                    <th>Amount(&#8358;):</th>
                                    <td>{{ subscription.amount / 100 | price }}</td>
                                </tr>
                                <tr>
                                    <th>To Expire:</th>
                                    <td>{{ subscription.expiry | moment('DD/MM/YYYY - HH:mma') }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Status:</th>
                                    <td><span :class="subscription.expiry_status == 'Active' ? 'active' : 'expired'">{{ subscription.expiry_status }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="tipSubscribed" :timeout="5000" top color="green darken-1 white--text">
            Thank you for subscribing.
            <v-btn text color="white--text" @click="tipSubscribed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            sub_id: this.$route.params.sub_id,
            isLoading: false,
            subscription: null,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        tipSubscribed(){
            return this.$store.getters.tipSubscribed
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUsersFlashMsg')
        next()
    },
    methods: {
        getSubscription(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_subscription/${this.$route.params.sub_id}`, this.authHeaders)
            .then((res)=> {
                this.isLoading = false
                this.subscription = res.data
                console.log(res.data)
            })
        },
        showEvent(event){
            // console.log(event)
            this.$router.push({name: 'SubscriptionTipView', params:{sub_id: this.$route.params.sub_id, pred_id: event.forecast_id}})
        }
    },
    created() {
        this.getSubscription()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll {
        overflow-x: scroll;
    }
    table.sub_table tr{
        cursor: pointer;
    }
    table tr .active{
        font-weight: bold;
        color: #00a800;
    }
    table tr .expired{
        font-weight: bold;
        color: rgb(238, 23, 23);
    }
</style>
