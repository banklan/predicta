<template>
    <v-container>
        <admin-top-panel title="Subscriptions" />
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="sub_title primary white--text justify-center">Subscriptions</v-card-title>
                    <v-card-text class="list">
                        <template v-if="subscriptions.length > 0">
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
                                <tbody class="sub_list">
                                    <tr v-for="(sub) in subscriptions" :key="sub.id" @click="showSubscription(sub)">
                                        <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                        <td class="primary--text font-weight-bold">{{ sub.sub_id }}</td>
                                        <td class="primary--text font-weight-bold" v-if="sub.user">{{ sub.user.fullname }}</td>
                                        <td>{{ sub.odd_cat }}</td>
                                        <td>{{ sub.expiry_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no subscriptions in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5 justify-space-around">
                        <span class="pl-4 pb-2">
                            <v-btn color="primary" @click.prevent="getSubscriptions(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getSubscriptions(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getSubscriptions(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getSubscriptions(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="subscriptionDeleted" :timeout="6000" top color="green darken-1 white--text">
            A subscription has been deleted successfully.
            <v-btn text color="white--text" @click="subscriptionDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            subscriptions: [],
            pagination: {},
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
        subscriptionDeleted(){
            return this.$store.getters.subscriptionDeleted
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getSubscriptions(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/admin_get_paginated_subscriptions`
            this.isLoading = true
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.subscriptions = res.data.data
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
        showSubscription(sub){
            // console.log(sub)
            // this.$router.push('/')
            this.$router.push({name: 'AdminSubscriptionShow', params: {sub: sub.sub_id}})
            // this.$router.push({name: 'SubscriptionTipView', params:{sub_id: this.$route.params.sub_id, pred_id: event.forecast_id}})
        },
    },
    created() {
        this.getSubscriptions()
    },
}
</script>

<style lang="css" scoped>
    .list table tbody tr{
        cursor: pointer;
    }
    .scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
