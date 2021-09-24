<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="2">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <expert-top-panel title="Subscriptions"/>
            </v-col>
        </v-row>
        <v-row justify="space-around" class="mt-5">
            <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="6">
                <v-card raised elevation="10" min-height="100">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Subscription &nbsp;<span v-if="sub">{{ sub.sub_id }}</span> </v-card-title>
                    <v-card-text>
                        <template v-if="sub">
                            <table class="table table-condensed table-hover table-striped ">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">Date</th>
                                        <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscriber</th>
                                        <td>{{ sub.user && sub.user.fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Category</th>
                                        <td>{{ sub.odd_cat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry</th>
                                        <td>{{ sub.expiry | moment('DD/MM/YY') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ sub.expiry_status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Earning(&#8358;)</th>
                                        <td v-if="sub.earning">{{ sub.earning.exp_amount / 100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td v-if="sub.earning" :class="sub.earning.is_settled ? 'settled' : 'not_settled'">{{ sub.earning.is_settled ? 'Settled' : 'Not Settled'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The subscription you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else raised elevation="10" min-height="100">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Other Subscriptions By &nbsp;<span v-if="sub">{{ sub.user && sub.user.first_name }}</span> </v-card-title>
                    <v-card-text>
                        <template v-if="otherSubs.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub ID</th>
                                        <th>Odd</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sub in otherSubs" :key="sub.id">
                                        <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                        <td class="primary--text font-weight-bold" @click="goToSub(sub)">{{ sub.sub_id }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td>{{ sub.expiry_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- {{ otherSubs }} -->
                        </template>
                        <template v-else>
                            <div class='my-3 body_text'>There are no other subscriptions for this subscriber.</div>
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
            isLoading: false,
            sub:null,
            otherSubs: []
        }
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getSubscription(id)
                this.getOtherSubs(id)
            },
            immediate: true,
            deep: true
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authExpert(){
            return this.$store.getters.authExpert
        },
        expertHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authExpert.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getSubscription(){
            this.isLoading = true
            axios.get(this.api + `/auth-expert/get_expert_subscription/${this.$route.params.id}`, this.expertHeader).then((res) => {
                this.isLoading = false
                this.sub = res.data
                let sub = res.data
                // console.log(res.data)
            })
        },
        getOtherSubs(){
            this.isLoading = true
            axios.get(this.api + `/auth-expert/get_subscriber_subs/${this.$route.params.id}`, this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.otherSubs = res.data
            })
        },
        goToSub(sub){
            this.$router.push({name: 'ExpertSubscriptionView', params:{id: sub.sub_id}})
        }
    },
    created(){
        this.getSubscription()
        this.getOtherSubs()
    }
}
</script>

<style lang="css" scoped>
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr{
        cursor: pointer;
    }
    table tbody tr .settled{
        color:#029002;
        font-weight: bold;
    }
    table tbody tr .not_settled{
        color: rgb(209, 0, 0);
        font-weight: bold;
    }
</style>
