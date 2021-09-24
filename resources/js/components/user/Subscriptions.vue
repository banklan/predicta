<template>
     <v-container>
       <v-row class="mt-2">
            <v-col cols="12" md="6" offset-md="6">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="My Subscriptions" />
            </v-col>
        </v-row>
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row justify="center" v-else>
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="100" class="mt-5 scroll">
                    <v-card-title class="primary darken-2 white--text subtitle-1 justify-center">Subscriptions</v-card-title>
                    <v-card-text class="">
                        <template v-if="subscriptions.length > 0">
                            <table class="table table-condensed table-hover sub_table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Subcription ID</th>
                                        <th>Tip Expert</th>
                                        <th>Odd Cat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sub in subscriptions" :key="sub.id" @click="viewSubscription(sub)">
                                        <td>{{ sub.created_at | moment('DD/MM/YY')}}</td>
                                        <td class="primary--text">{{ sub.sub_id }}</td>
                                        <td>{{ sub.expert && sub.expert.username }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td><span :class="sub.expiry_status == 'Active' ? 'active' : 'expired'">{{ sub.expiry_status }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                You have no subscriptions to view is invalid.
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
            isLoading: false,
            subscriptions: null,
            forecast: null
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getSubscriptions(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_my_subscriptions`, this.authHeaders)
            .then((res)=> {
                this.isLoading = false
                this.subscriptions = res.data
            })
        },
        viewSubscription(sub){
            this.$router.push({name: 'SubscriptionView', params: {sub_id: sub.sub_id}})
        }
    },
    created() {
        this.getSubscriptions()
    },
}
</script>

<style lang="css" scoped>
    table.sub_table tr{
        cursor: pointer;
    }
    table tr .active{
        font-weight: bold;
        color: #00a800;
    }
    table tr .expired{
        font-weight: bold;
        color: rgb(238, 23, 23);
    }
    .v-card.scroll{
        overflow-x: scroll !important;
    }
</style>
