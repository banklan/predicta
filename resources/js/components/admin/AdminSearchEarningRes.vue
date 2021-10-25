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
                <div class="subtitle-1 mb-5 text-center" v-if="earnings.length > 0">Search for <strong> {{ q }}</strong> returns {{ earnings.length | pluralize('payment')}}</div>
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="earnings.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Earnings <v-chip color="primary lighten-2" v-if="earnings.length > 0">{{ earnings.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub.ID</th>
                                        <th>Amount(&#8358;)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="earn in earnings" :key="earn.id">
                                        <td>{{ earn.created_at | moment('DD/MM/YY - H:sa') }}</td>
                                        <td @click="showEarning(earn)" class="primary--text">{{ earn.subscription.sub_id }}</td>
                                        <td @click="showEarning(earn)">{{ earn.total / 100 | price }}</td>
                                        <td @click="showEarning(earn)">{{ earn.is_settled ? 'Settled' : 'Not Settled' }}</td>
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
            earnings: [],
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
            axios.post(this.api + '/auth-admin/admin_search_for_earnings', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.earnings = res.data
                console.log(res.data)
            })
        },
        showEarning(earn){
            this.$router.push({name: 'AdminEarningDetail', params:{id: earn.id}})
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
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td{
        white-space: nowrap;
    }
</style>
