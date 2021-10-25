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
                <div class="subtitle-1 mb-5 text-center" v-if="subscriptions.length > 0">Search for <strong> {{ q }}</strong> returns {{ subscriptions.length | pluralize('subscription')}}</div>
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="subscriptions.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Subscriptions <v-chip color="primary lighten-2" v-if="subscriptions.length > 0">{{ subscriptions.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Subscription ID</th>
                                        <th>User</th>
                                        <th>Cat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(sub) in subscriptions" :key="sub.id" @click="showSubscription(sub)">
                                        <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                        <td class="primary--text font-weight-bold">{{ sub.sub_id }}</td>
                                        <td class="primary--text font-weight-bold" v-if="sub.user">{{ sub.user.fullname }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td>{{ sub.expiry_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search returned no subscriptions.
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
            subscriptions: [],
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
            axios.post(this.api + '/auth-admin/admin_search_for_subscriptions', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.subscriptions = res.data
            })
        },
        showSubscription(subscr){
            this.$router.push({name: 'AdminSubscriptionShow', params:{sub: subscr.sub_id}})
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
