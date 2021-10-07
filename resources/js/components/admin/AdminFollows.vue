<template>
    <v-container>
        <admin-top-panel title="Follows" />
        <v-row class="mt-n2" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="4" offset-md="6">
                <v-select label="Filter Experts" :items="experts" item-text="username" item-value="id" v-model="filteredFollows" @change="filterExperts"></v-select>
            </v-col>
        </v-row>
        <v-row class="mt-2 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Follows <v-chip v-if="follows.length > 0" class="ml-1 primary lighten-2">{{ follows.length }}</v-chip></v-card-title>
                    <v-card-text>
                        <template v-if="follows.length > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Expert</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr v-for="(follow, index) in follows" :key="follow.id">
                                        <td @click="showFollow(follow)">{{ follow.created_at | moment('DD/MM/YY') }}</td>
                                        <td @click="showFollow(follow)">{{ follow.user.fullname }}</td>
                                        <td @click="showFollow(follow)">{{ follow.expert.username }}</td>
                                        <td><v-btn icon color="red darken-2" @click="openDelDial(follow, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no follows.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getFollows(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getFollows(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getFollows(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getFollows(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                      </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="followDial" max-width="480">
            <v-card min-height="200">
                <v-card-title class="subtitle-1 primary white--text justify-center">Follow Detail</v-card-title>
                <v-card-text class="">
                    <table class="table table-hover">
                        <thead></thead>
                        <tbody v-if="followDial">
                            <tr>
                                <th width="30%">User:</th>
                                <td>{{ followDetail.user.fullname }}</td>
                            </tr>
                            <tr>
                                <th>Expert:</th>
                                <td>{{ followDetail.expert.username }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <template v-if="gotFollows">
                        <div class="subtitle-2 py-1"><strong>{{ followDetail.user.fullname }}</strong> has followed {{ userFollows | pluralize('expert') }}</div>
                        <div class="subtitle-2"><strong>{{ followDetail.expert.username }}</strong> is been followed by {{ expertFollows | pluralize('user') }}</div>
                    </template>
                </v-card-text>
                <v-card-actions class="pb-6 justify-center">
                    <v-btn text color="primary darken--2" @click="dissmissFollowDial" width="60%">Got it!</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="delConfirmDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Do you want to delete this follow?</v-card-title>
                <v-card-actions class="justify-center mt-8 pb-5">
                    <v-btn text width="40%" color="red darken-2" @click="delConfirmDial = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary darken-2" @click="delFollow" :loading="isDeleting">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="followDeleted" :timeout="4000" top color="green darken-1 white--text">
            Follow was deleted successfully.
            <v-btn text color="white--text" @click="followDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            follows: [],
            isLoading: false,
            pagination: {},
            total: null,
            followDial: false,
            gotFollows: false,
            userFollows: null,
            expertFollows: null,
            followDetail: null,
            followToDel: null,
            followTodelIndex: null,
            delConfirmDial: false,
            isDeleting: false,
            followDeleted: false,
            experts: [],
            filteredFollows: null
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
        getFollows(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_follows`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.follows = res.data.data
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
        showFollow(follow){
            this.followDial = true
            this.followDetail = follow
            this.getUserFollows(follow)
            this.getExpertFollows(follow)
        },
        dissmissFollowDial(){
            this.followDial = false
            this.gotFollows = false
        },
        getUserFollows(follow){
            axios.get(this.api + `/auth-admin/admin_get_users_follows/${follow.user_id}`, this.adminHeaders)
            .then((res) => {
                this.gotFollows = true
                this.userFollows = res.data
                console.log(res.data)
            })
        },
        getExpertFollows(follow){
            axios.get(this.api + `/auth-admin/admin_get_expert_follows/${follow.expert_id}`, this.adminHeaders)
            .then((res) => {
                this.gotFollows = true
                this.expertFollows = res.data
                console.log(res.data)
            })
        },
        openDelDial(follow, index){
            this.delConfirmDial = true
            this.followToDel = follow
            this.followTodelIndex = index
        },
        delFollow(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_delete_follow/${this.followToDel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.delConfirmDial = false
                this.followDeleted = true
                this.follows.splice(this.followTodelIndex, 1)
            })
        },
        getAllExperts(){
            axios.get(this.api + '/auth-admin/admin_get_all_experts', this.adminHeaders)
            .then((res) => {
                this.experts = res.data
            })
        },
        filterExperts(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/filter_follows_by_experts/${this.filteredFollows}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.follows = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        }
    },
    created(){
        this.getFollows()
        this.getAllExperts()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    table tbody tr td{
        white-space: nowrap !important;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
