<template>
    <div>
        <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-card v-else light raised outlined elevation="4" min-height="150" class="mt-5">
            <v-card-title class="primary white--text justify-center subtitle-1">Outstanding Earnings <v-chip v-if="earnings.length > 0" class="ml-2 primary lighten-2">{{ earnings.length }}</v-chip></v-card-title>
            <v-card-text class="mt-4">
                <template v-if="earnings.length > 0">
                    <table class="table table-condensed table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sub ID</th>
                                <th>Amount(&#8358;)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="earn in earnings" :key="earn.id">
                                <td>{{ earn.created_at | moment('MM/DD/YY') }}</td>
                                <td v-if="earn.subscription" class="primary--text" @click="goToSub(earn)">{{ earn.subscription.sub_id }}</td>
                                <td>{{ earn.exp_amount / 100 | price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" class="mt-5">
                        There are no outstanding earning for this expert.
                    </v-alert>
                    <!-- <div class="body_text"></div> -->
                </template>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['expert'],
    data() {
        return {
            isLoading: false,
            earnings: []
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
        },
    },
    methods: {
        getOutstandingEarnings(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_exp_outstanding_earnings/${this.expert}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.earnings = res.data
            })
        },
        goToSub(earn){
            this.$router.push({name: 'AdminEarningDetail', params: {id: earn.id}})
        }
    },
    created() {
        this.getOutstandingEarnings()
    },
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }

</style>
