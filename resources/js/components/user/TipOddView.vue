<template>
    <v-container>
        <tips-route />
        <v-row class="mt-n7">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100">
                    <v-card-title class="subtitle-1 primary white--text justify-center">{{ odd }} odds tips by Tip Expert &nbsp;<span v-if="expert">{{ expert.username }}</span>&nbsp;<v-chip v-if="tips.length > 0" color="primary darken-2">{{ tips.length }}</v-chip></v-card-title>
                    <v-card-text>
                        <table class="table table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Forecast ID</th>
                                    <th>Total Odds</th>
                                    <th>No of Games</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tip in tips" :key="tip.id">
                                    <td>{{ tip.forecast_id }}</td>
                                    <td>{{ tip.total_odds | formatOdds }}</td>
                                    <td>{{ tip.event_count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n5">
                        <v-btn v-if="isSubscribed" text large color="primary darken-2" disabled>Already Subscribed</v-btn>
                        <v-btn v-else text large color="primary darken-2" @click="makePaymentDial = true">Subscribe</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Forecast Details</v-card-title>
                    <v-card-text class="mt-3">
                        <template v-if="expert">
                            <table class="table table-condensed table-striped table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="40%">Username:</th>
                                        <td>{{ expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Category:</th>
                                        <td>{{ odd }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscribers:</th>
                                        <td v-if="subCount">{{ subCount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The tip you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
                <v-card light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Winning Forecasts By <span v-if="expert">{{ expert.username }}</span></v-card-title>
                    <v-card-text>
                        <template v-if="winnings.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Event ID</th>
                                        <th>Odd</th>
                                        <th>Subscribers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="won in winnings" :key="won.id">
                                        <td>{{ won.published }}</td>
                                        <td>{{ won.forecast_id }}</td>
                                        <td>{{ won.total_odds | formatOdds }}</td>
                                        <td>{{ won.published }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type='info' border="left" class="mt-5">
                                This tip expert has no winning events.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="makePaymentDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Make Payment</v-card-title>
                <v-card-text class="text-center subtitle-1 mt-5">
                    Make payment before subscribing?
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="makePaymentDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="subscribe" width="50%">Make Payment</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            expert: null,
            tips: [],
            odd: this.$route.params.odd,
            winnings: [],
            makePaymentDial: false,
            isBusy: false,
            isSubscribed: false,
            subCount: null
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
    },
    methods: {
        getExperts(){
            this.isLoading = true
            axios.get(this.api + `/get_tip_expert/${this.$route.params.expert}`).then((res) => {
                this.isLoading = false
                this.expert = res.data
                let predicts = this.expert.prediction_summary
                let tips = predicts.filter(item => item.is_opened == true && item.forecast_odd == this.$route.params.odd)
                this.tips = tips
            })
        },
        getWinningForecasts(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_expert_winning_forecasts/${this.$route.params.expert}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.winnings = res.data
                // console.log(res.data)
            })
        },
        subscribe(){
            this.isBusy = true
            axios.post(this.api + `/auth/subscribe_to_expert_tips`, {
                odd: this.$route.params.odd,
                expert: this.$route.params.expert
            }, this.authHeaders).then((res)=>{
                console.log(res.data)
                this.isBusy = false
                this.makePaymentDial = false
                this.$store.commit('tipSubscribed', res.data)
                this.$router.push({name: 'SubscriptionView', params:{sub_id: res.data.sub_id}})
            })
        },
        checkIfSubscribed(){
            axios.get(this.api + `/auth/check_if_subscribed/${this.$route.params.odd}/${this.$route.params.expert}`, this.authHeaders)
            .then((res) => {
                this.isSubscribed = res.data.status
                // console.log(res.data.status)
            })
        },
        getSubscriptionCount(){
            axios.get(this.api + `/auth/subscription_count/${this.$route.params.odd}/${this.$route.params.expert}`, this.authHeaders)
            .then((res) => {
                this.subCount = res.data
                // console.log(res.data)
            })
        }
    },
    created(){
        this.getExperts()
        this.getWinningForecasts()
        this.checkIfSubscribed()
        this.getSubscriptionCount()
    }
}
</script>
