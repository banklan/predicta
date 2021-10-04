<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel :title="`Subscription ${sub}`" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <template v-if="subscription">
                        <v-card-title class="subtitle-1 primary white--text justify-center">Subscription </v-card-title>
                        <v-card-text class="subtitle-2 mt-1">
                            <table class="table table-hover table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">Subscription ID</th>
                                        <td>{{ subscription.sub_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>User</th>
                                        <td>{{ subscription.user && subscription.user.fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert</th>
                                        <td>{{ subscription.expert && subscription.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Category</th>
                                        <td>{{ subscription.odd_cat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount(&#8358;)</th>
                                        <td>{{ subscription.amount/100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date / Time</th>
                                        <td>{{ subscription.created_at | moment('DD/MM/YY') }} - {{ subscription.created_at | moment('H:ma') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry</th>
                                        <td>{{ subscription.expiry | moment('DD/MM/YY') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry Status</th>
                                        <td>{{ subscription.expiry_status }}</td>
                                    </tr>
                                    <tr v-if="subscription.payment">
                                        <th>Transaction ID</th>
                                        <td class="primary--text go_to" @click="goToPymt(subscription.payment)">{{ subscription.payment.tx_id }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                        <v-card-actions class="justify-space-around mb-5">
                            <v-btn width="40%" dark color="primary darken-2" @click="expConfirmDial = true">{{ subscription.status ? 'Disable' : 'Enable' }}</v-btn>
                            <v-btn dark width="40%" color="red darken-2" @click="delConfirmDial = true"><v-icon>delete_forever</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-alert type="warning" border="left" class="mt-5">
                            The subscription you are trying to view does not exist.
                        </v-alert>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card light raised elevation="8" min-height="200" class="scroll">
                    <template v-if="subscription">
                        <v-card-title class="subtitle-1 primary white--text justify-center">Events </v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">Tip Expert</th>
                                        <td v-if="subscription.expert" @click="goToExpert(subscription.expert.id)" class="primary--text go_to">{{ subscription.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Count</th>
                                        <td v-if="subscription.events">{{ subscription.events.length }} </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="subtitle-1 my-2 text-center">Events </div>
                            <template v-for="event in subscription.events">
                                <v-card flat :key="event.id">
                                    <v-card-text>
                                        <table class="table table-condensed table-hover">
                                            <thead></thead>
                                            <tbody>
                                                <tr>
                                                    <th>Forecast ID</th>
                                                    <td v-if="subscription.events" @click="goToForecast(event, subscription.expert)" class="primary--text go_to">{{ event.forecast_id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Event Count</th>
                                                    <td>{{ event.event_count }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Availability</th>
                                                    <td>{{ event.is_available ? 'Available' : 'Not Available' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>{{ event.prog_status == 0 ? 'Opened' : 'Not Opened' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Odds</th>
                                                    <td>{{ event.total_odds | formatOdds }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Published</th>
                                                    <td>{{ event.published }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-card-text>
                    </template>

                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="delConfirmDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Delete Subscription?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    Are you sure you want to delete this subscription? User and Expert will no longer see the subscription in their account if you proceed to delete.
                </v-card-text>
                <v-card-actions class="pb-5 justify-center">
                    <v-btn text color="red darken--2" @click="delConfirmDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delSubscription" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="expConfirmDial" max-width="480">
            <v-card min-height="150" v-if="subscription">
                <v-card-title class="subtitle-1 primary white--text justify-center">{{ subscription.status ? 'Disable ' : 'Enable '}} this Subscription?</v-card-title>
                <v-card-actions class="mt-5 justify-center">
                    <v-btn text color="red darken--2" @click="expConfirmDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateStatus" width="50%">Yes, {{ subscription.status ? 'Disable' : 'Enable'}}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="delSubFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to delete this subscription. Please try again.
            <v-btn text color="white--text" @click="delSubFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="statusChangeFail" :timeout="5000" top color="red darken-1 white--text">
            There was an error while trying to change the status of this subscription. Please try again.
            <v-btn text color="white--text" @click="statusChangeFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="statusChanged" :timeout="4000" top color="green darken-1 white--text">
            Subscription status changed successfully.
            <v-btn text color="white--text" @click="statusChanged = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            sub: this.$route.params.sub,
            subscription: null,
            isLoading: false,
            delConfirmDial: false,
            isUpdating: false,
            eventRemoved: false,
            delSubFail: false,
            expConfirmDial: false,
            statusChangeFail: false,
            statusChanged:  false
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
        getSubscription(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_subscription/${this.$route.params.sub}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.subscription = res.data
                // console.log(res.data)
            })
        },
        goToExpert(expert){
            this.$router.push({name: 'AdminExpertDetail', params:{id: expert}})
        },
        goToForecast(event, expert){
            this.$router.push({name: 'AdminExpertForecast', params:{expert: expert.expert_id, fc: event.forecast_id}})
        },
        delSubscription(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_delete_subscription/${this.$route.params.sub}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.delConfirmDial = false
                this.$store.commit('subscriptionDeleted')
                this.$router.push({name: 'AdminSubscriptionsList'})
                console.log(res.data)
            }).catch(() => {
                this.isUpdating = false
                this.delConfirmDial = false
                this.delSubFail = true
            })
        },
        updateStatus(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_change_subscription_status/${this.subscription.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.expConfirmDial = false
                this.subscription.status = res.data
                this.statusChanged = true
            }).catch(() => {
                this.isUpdating = false
                this.expConfirmDial = false
                this.statusChangeFail = true
            })
            // console.log(this.subscription.status)
        },
        goToPymt(pymt){
            this.$router.push({name: 'AdminPaymentDetail', params: {trx: pymt.tx_id}})
        }
    },
    created(){
        this.getSubscription()
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    table tbody tr td.go_to{
        cursor: pointer !important;
    }

    .v-card{
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
