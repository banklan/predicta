<template>
    <v-container>
        <admin-top-panel title="Earnings" />
        <v-row justify="end">
            <v-col cols="12" md="6">
                <admin-search model="Earning" searchFor="earnings"/>
            </v-col>
        </v-row>
        <v-row class="mt-n4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Earnings</v-card-title>
                    <v-card-text>
                        <template v-if="earnings.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub.ID</th>
                                        <th>Amount(&#8358;)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr v-for="earn in earnings" :key="earn.id">
                                        <td>{{ earn.created_at | moment('DD/MM/YY - H:sa') }}</td>
                                        <td @click="showEarning(earn)" class="primary--text">{{ earn.subscription.sub_id }}</td>
                                        <td @click="showEarning(earn)">{{ earn.total / 100 | price }}</td>
                                        <td @click="showEarning(earn)">{{ earn.is_settled ? 'Settled' : 'Not Settled' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left">
                                There are currently no payments.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getEarnings(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEarnings(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEarnings(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEarnings(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
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
            earnings: [],
            pagination: {},
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
    methods: {
        getEarnings(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_earnings`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.earnings = res.data.data
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
        showEarning(earn){
            this.$router.push({name: 'AdminEarningDetail', params:{id: earn.id}})
        },
    },
    created(){
        this.getEarnings()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
</style>
