<template>
    <v-container>
        <v-row :class="$vuetify.breakpoint.smAndDown ? 'ml-n3':''">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel :title="`Pymt Trx ID ${trx_id}`" />
            </v-col>
        </v-row>
        <v-row class="mt-4" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Payment ID <span class="ml-1" v-if="trx_id">{{ trx_id }}</span></v-card-title>
                    <v-card-text>
                        <template v-if="payment">
                            <table class="table table-hover table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">Date:</th>
                                        <td>{{ payment.created_at | moment('DD/MM/YY') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction ID:</th>
                                        <td>{{ payment.tx_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscription ID:</th>
                                        <td v-if="payment.subscription" @click="goToSub(payment.subscription)" class="primary--text">{{ payment.subscription.sub_id }}</td>
                                    </tr>
                                    <tr v-if="payment.user">
                                        <th>Subscriber:</th>
                                        <td @click="goToUser(payment.user)" class="primary--text">{{ payment.user.fullname }}</td>
                                    </tr>
                                    <tr v-if="payment.subscription">
                                        <th>Tip Expert:</th>
                                        <td class="primary--text" @click="goToExpert(payment.subscription.expert)">{{ payment.subscription.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd Category:</th>
                                        <td>{{ payment.subscription.odd_cat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td>{{ payment.amount / 100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ payment.status ? 'Successful' : 'Failed' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Message:</th>
                                        <td>{{ payment.message }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ref ID:</th>
                                        <td>{{ payment.ref_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trans ID:</th>
                                        <td>{{ payment.trans }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trans Ref:</th>
                                        <td>{{ payment.trx_ref }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Mode:</th>
                                        <td>{{ payment.mode }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The payment details you are tryig to view is invalid.
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
            trx_id: this.$route.params.trx,
            isLoading: false,
            payment: null
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
        getPayment(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_subscription_payment_details/${this.$route.params.trx}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.payment = res.data
                console.log(res.data)
            })
        },
        goToSub(sub){
            this.$router.push({name:'AdminSubscriptionShow', params: {sub: sub.sub_id}})
        },
        goToUser(user){
            this.$router.push({name:'AdminUserShow', params: {id: user.id}})
        },
        goToExpert(expert){
            this.$router.push({name:'AdminExpertDetail', params: {id: expert.id}})
        },
    },
    created(){
        this.getPayment()
    }
}
</script>

<style lang="css" scoped>
    .table tbody tr{
        cursor: pointer;
    }
    table tbody tr td, table tbody tr th{
        white-space: nowrap !important;
    }
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
</style>
