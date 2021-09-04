<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="8" md="8">
                <div class="title justify-center">Experts (Forecasters)</div>
            </v-col>
            <v-col cols="4" md="4">
                <v-btn dark color="primary" :to="{name: 'AdminCreateExpert'}"><v-icon left>add</v-icon>New Expert</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Expert Users</v-card-title>
                    <v-card-text>
                        <template v-if="experts.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Expert ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="expert_list">
                                    <tr v-for="user in experts" :key="user.id">
                                        <td @click="showExpert(user)">{{ user.id }}</td>
                                        <td @click="showExpert(user)">{{ user.expert_id }}</td>
                                        <td @click="showExpert(user)">{{ user.username }}</td>
                                        <td @click="showExpert(user)">{{ user.email }}</td>
                                        <td><v-btn small text color="primary" :to="{name: 'AdminExpertUpdate', params:{id: user.id}}"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="confirmDel(user, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left">
                                There are currently no experts users.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getExperts(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getExperts(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text darken-2 justify-center">Do you really want to delete this expert user?</v-card-title>
                <v-card-text class="text-center mt-4 subtitle-1">
                    If you proceed to delete, the expert user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteUser" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="expertUserDeleted" :timeout="4000" top dark color="green darken-2">
            Expert user has been deleted successfully.
            <v-btn text color="white--text" @click="expertUserDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="expertDeleteFailed" :timeout="4000" top dark color="red darken-2">
            An error occur while trying to delete the expert user. Please try again.
            <v-btn text color="white--text" @click="expertDeleteFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedExpert" :timeout="4000" top dark color="green darken-2">
            Expert user was updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedExpert = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="newExpertCreated" :timeout="4000" top dark color="green darken-2">
            A new expert has been created successfully.
            <v-btn text color="white--text" @click="newExpertCreated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            experts: [],
            pagination: {},
            isLoading: false,
            confirmDelDial: false,
            expertTodel: null,
            expertTodelIndex: null,
            isDeleting: false,
            expertDeleted: false,
            expertDeleteFailed: false,
            isToggling: false
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
        newExpertCreated(){
            return this.$store.getters.newExpertCreated
        },
        adminUpdatedExpert(){
            return this.$store.getters.adminUpdatedExpert
        },
        expertUserDeleted(){
            return this.$store.getters.expertUserDeleted
        },
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getExperts(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_experts`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.experts = res.data.data
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
        showExpert(user){
            this.$router.push({name: 'AdminExpertDetail', params:{id: user.id}})
        },
        toggleUserStatus(user){
            this.isToggling = true
            axios.post(this.api + `/auth-admin/change_superuser_status/${user.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isToggling = false
                this.$store.commit('adminUpdatedSuperUser')
                user.status = res.data
            })
        },
        confirmDel(user, i){
            this.expertTodel = user
            this.expertTodelIndex = i
            this.confirmDelDial = true
        },
        deleteUser(){
            console.log(this.adminTodel)
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_del_expert/${this.expertTodel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.experts.splice(this.expertTodelIndex, 1)
                this.$store.commit('expertUserDeleted')
            }).catch(() => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.expertDeleteFailed = true
            })
        }
    },
    created(){
        this.getExperts()
    }
}
</script>

<style lang="css" scoped>
    table .expert_list tr td{
        cursor: pointer;
    }
    .v-card .v-card__text{
        overflow: scroll !important;
    }
</style>
