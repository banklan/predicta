<template>
    <v-container>
        <v-row class="">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Earning" />
            </v-col>
        </v-row>
        <v-row class="mt-4">
            <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Earning ID <span v-if="earning" class="ml-2">{{ earning.id }}</span></v-card-title>
                    <v-card-text>
                        <template v-if="earning">
                            <table class="table table-hover table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th>Date:</th>
                                        <td>{{ earning.created_at | moment('DD/MM/YY - H:ma') }}</td>
                                    </tr>
                                    <tr v-if="earning.subscription">
                                        <th>Subscription ID:</th>
                                        <td v-if="earning.subscription" @click="goToSub(earning.subscription)" class="primary--text font-weight-bold">{{ earning.subscription.sub_id }}</td>
                                    </tr>
                                    <tr v-if="earning.subscription">
                                        <th>Subscriber:</th>
                                        <td @click="goToUser(earning.subscription.user)" class="primary--text font-weight-bold">{{ earning.subscription.user.fullname }}</td>
                                    </tr>
                                    <tr v-if="earning.subscription">
                                        <th>Tip Expert:</th>
                                        <td @click="goToExpert(earning.subscription.expert)" class="primary--text font-weight-bold">{{ earning.subscription.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscription Amount(&#8358;):</th>
                                        <td>{{ earning.total / 100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert Amount(&#8358;):</th>
                                        <td>{{ earning.exp_amount / 100 | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Admin Amount(&#8358;):</th>
                                        <td>{{ earning.admin_amount / 100 | price }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ earning.is_settled ? 'Settled' : 'Not Settled' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left">
                                The earning details you are tryig to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn large dark width="50%" color="primary darken-2" @click="confirmUpdate = true">{{ earning.is_settled ? 'Un-Settle' : 'Settle' }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmUpdate" max-width="480">
            <v-card min-height="180">
                <v-card-title class="subtitle-1 primary white--text justify-center">Change Status To <span v-if="earning" class="ml-2 font-weight-bold">{{ earning.is_Settled ? 'Unsettled' : 'Settled' }}</span></v-card-title>
                <v-card-actions class="justify-center mt-8" v-if="earning">
                    <v-btn text color="red darken--2" @click="confirmUpdate = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="changeStatus" width="40%">Yes, Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="statusChanged" :timeout="4000" top color="green darken-1 white--text">
            The status of this earning has been changed.
            <v-btn text color="white--text" @click="statusChanged = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            isLoading: false,
            earning: null,
            confirmUpdate: false,
            isBusy: false,
            statusChanged: false
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
        getEarning(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_earning_details/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.earning = res.data
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
        changeStatus(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/change_earning_is_settled_status/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) =>{
                this.isBusy = false
                this.confirmUpdate = false
                this.earning.is_settled = res.data
                this.statusChanged = true
            })
        }
    },
    created(){
        this.getEarning()
    }
}
</script>

<style lang="css" scoped>
    .table tbody tr{
        cursor: pointer;
    }
</style>
