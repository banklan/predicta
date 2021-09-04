<template>
    <v-container>
        <expert-top-panel title="Subscriptions"/>
        <v-row justify="space-around" class="mt-5">
            <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="8">
                <v-card raised elevation="10" min-height="150">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Subscriptions to my forecasts </v-card-title>
                    <template v-if="subscriptions.length > 0">
                        <table class="table table-condensed table-hover table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Sub ID</th>
                                    <th>User</th>
                                    <th>Odd</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr align="center" v-for="sub in subscriptions" :key="sub.id">
                                    <td>{{ sub.created_at | moment('DD/MM/YY') }}</td>
                                    <td class="primary--text" @click="goToSub(sub)">{{ sub.sub_id }}</td>
                                    <td>{{ sub.user && sub.user.fullname }}</td>
                                    <td>{{ sub.odd_cat }}</td>
                                    <td>{{ sub.expiry | moment('DD/MM/YY') }}</td>
                                    <td>{{ sub.expiry_status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template v-else>
                        <v-alert type="info" border="left" class="mt-5 mx-2">
                            There are no subscriptions to your tips at the moment.
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
            subscriptions: [],
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
        getSubscriptions(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_subscriptions', this.expertHeader).then((res) => {
                this.isLoading = false
                this.subscriptions = res.data
            })
        },
        goToSub(sub){
            console.log(sub)
            this.$router.push({name: 'ExpertSubscriptionView', params: {id: sub.sub_id}})
        }
    },
    created(){
        this.getSubscriptions()
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
    table tbody tr{
        cursor: pointer;
    }
</style>
