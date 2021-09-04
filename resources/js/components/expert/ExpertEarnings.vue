<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="2">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <expert-top-panel title="Earnings"/>
            </v-col>
        </v-row>
        <v-row justify="space-around" class="mt-5">
            <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="8">
                <v-card raised elevation="10" min-height="150">
                    <v-card-title class="justify-center subtitle-1 primary white--text mb-5">All Earnings </v-card-title>
                    <template v-if="earnings.length > 0">
                        <table class="table table-condensed table-hover table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Sub ID</th>
                                    <th>Amount(&#8358;)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="earn in earnings" :key="earn.id" style="text-align:center">
                                    <td>{{ earn.created_at | moment('DD/MM/YY') }}</td>
                                    <td @click="goToSub(earn)" class="primary--text">{{ earn.subscription_id }}</td>
                                    <td>{{ earn.exp_amount / 100 | price }}</td>
                                    <td>{{ earn.is_settled ? 'Settled' : 'Not Settled' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template v-else>
                        <v-alert type="info" border="left" class="mt-5 mx-2">
                            There are no earnings at the moment.
                        </v-alert>
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
            earnings: [],
            isLoading: false
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
        getEarnings(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_earnings', this.expertHeader).then((res) => {
                this.isLoading = false
                this.earnings = res.data
            })
        },
        goToSub(sub){
            // this.$router.push({name: ''})
            console.log(sub)
        }
    },
    created() {
        this.getEarnings()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
