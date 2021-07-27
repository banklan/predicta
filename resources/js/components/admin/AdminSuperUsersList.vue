<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="8" md="8">
                <div class="title justify-center">Super Users</div>
            </v-col>
            <v-col cols="4" md="4">
                <v-btn dark color="primary" :to="{name: 'AdminCreateSuperUser'}"><v-icon left>add</v-icon>New User</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Super Users</v-card-title>
                    <v-card-text>
                        <v-simple-table light fixed-header height="400">
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Fullname</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="admin_list">
                                    <tr v-for="(user, i) in admins" :key="user.id">
                                        <td @click="showUser(user)">{{ user.id }}</td>
                                        <td @click="showUser(user)">{{ user.fullname }}</td>
                                        <td @click="showUser(user)">{{ user.email }}</td>
                                        <td>{{ user.status ? 'Enabled' : 'Disabled'}} &nbsp; <v-btn dark small class="primary" @click.prevent="toggleUserStatus(user)">{{ user.status ? 'Disable' : 'Enable'}}</v-btn> </td>
                                        <td><v-btn small text color="primary" :to="{name: 'AdminUpdateSuperUser', params:{id: user.id}}"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn v-if="user.id !== authAdmin.id" small text color="red darken-2" @click="confirmDel(user, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-8 mb-4 font-weight-bold">Delete this user?</v-card-title>
                <v-card-text class="justify-center mt-5 body-2">
                    If you proceed to delete, the admin user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteAdmin">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUserDeleted" :timeout="4000" top dark color="green darken-2">
            Admin user has been deleted successfully.
            <v-btn text color="white--text" @click="adminUserDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="adminDeleteFailed" :timeout="4000" top dark color="red darken-2">
            Admin user delete failed. Please try again.
            <v-btn text color="white--text" @click="adminDeleteFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedSuperUser" :timeout="4000" top dark color="green darken-2">
            Admin update successful.
            <v-btn text color="white--text" @click="adminUpdatedSuperUser = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminCreated" :timeout="4000" top dark color="green darken-2">
            A new admin has been created successful.
            <v-btn text color="white--text" @click="adminCreated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            admins: [],
            isLoading: false,
            confirmDelDial: false,
            adminTodel: null,
            adminTodelIndex: null,
            isDeleting: false,
            adminDeleted: false,
            adminDeleteFailed: false,
            isToggling: false
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
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
        adminUpdatedSuperUser(){
            return this.$store.getters.adminUpdatedSuperUser
        },
        adminUserDeleted(){
            return this.$store.getters.adminUserDeleted
        },
        adminCreated(){
            return this.$store.getters.adminCreated
        }
    },
    methods: {
        getAdmins(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/all_superusers`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.admins = res.data
                console.log("admins", res.data)
            }).catch(() => {
                this.isLoading = false
            })
        },
        showUser(user){
            this.$router.push({name: 'AdminSuperUserDetail', params:{id: user.id}})
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
            this.adminTodel = user
            this.adminTodelIndex = i
            this.confirmDelDial = true
        },
        deleteAdmin(){
            console.log(this.adminTodel)
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/super_user/${this.adminTodel.id}/delete`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.admins.splice(this.adminTodelIndex, 1)
                this.$store.commit('adminUserDeleted')
            }).catch(() => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.adminDeleteFailed = true
            })
        }
    },
    created(){
        this.getAdmins()
    }
}
</script>

<style lang="css" scoped>
    table .admin_list tr td{
        cursor: pointer;
    }
</style>
