<template>
    <v-container>
        <admin-top-panel title="Payments" />
        <v-row justify="center" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="6">
                <admin-search model="Payment" searchFor="payments"/>
            </v-col>
        </v-row>
        <v-row class="mt-n4 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Payments</v-card-title>
                    <v-card-text>
                        <template v-if="payments.length > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Trx ID</th>
                                        <th>Subscrp ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr v-for="pymt in payments" :key="pymt.id">
                                        <td @click="showPymt(pymt)">{{ pymt.created_at | moment('DD/MM/YY - H:sa') }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.tx_id }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.subscription_id }}</td>
                                        <td v-if="pymt.user" @click="showPymt(pymt)">{{ pymt.user.fullname }}</td>
                                        <td @click="showPymt(pymt)">{{ pymt.amount / 100 | price }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no payments.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="total > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getExperts(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            payments: [],
            pagination: {},
            total: 0,
            isLoading: false,
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getPayments(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_payments`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.payments = res.data.data
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
        showPymt(pymt){
            this.$router.push({name: 'AdminPaymentDetail', params:{trx: pymt.tx_id}})
        },
    },
    created(){
        this.getPayments()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr td{
        cursor: pointer;
        white-space: nowrap !important;
    }
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
</style>
