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
                <div class="subtitle-1 mb-5 text-center" v-if="payments.length > 0">Search for <strong> {{ q }}</strong> returns {{ payments.length | pluralize('payment')}}</div>
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="payments.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Payments <v-chip color="primary lighten-2" v-if="payments.length > 0">{{ payments.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Trx ID</th>
                                        <th>Subscrp ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="pymt in payments" :key="pymt.id">
                                        <td @click="showPymt(pymt)">{{ pymt.created_at | moment('DD/MM/YY - H:sa') }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.tx_id }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.subscription_id }}</td>
                                        <td v-if="pymt.user" @click="showPymt(pymt)">{{ pymt.user.fullname }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.amount / 100 | price }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search returned no payments.
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
            payments: [],
            q: this.$route.query.q
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
            axios.post(this.api + '/auth-admin/admin_search_for_payments', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.payments = res.data
            })
        },
        showPymt(pymt){
            this.$router.push({name: 'AdminPaymentDetail', params:{trx: pymt.tx_id}})
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
</style>
