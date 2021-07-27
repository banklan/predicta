<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left :to="{name: 'AdminSuperUsersList'}"><v-icon left>arrow_left</v-icon> Admin List</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5 mr-5">
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="400">
                    <v-img v-if="user.picture" :src="`/images/profiles/admins/${user.picture}`" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                    <v-img v-else src="/images/shared/user6.jpg" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                    <v-card-text>
                        <div class="sub_title my-3 text-center">Admin</div>
                        <v-simple-table light>
                            <template v-slot:default>
                                <thead>

                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">First Name:</th>
                                        <td>{{ user.first_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Name:</th>
                                        <td>{{ user.last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ user.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td>{{ user.admin_role }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ user.admin_status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone No:</th>
                                        <td>{{ user.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ user.created }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td>{{ user.updated_at }}</td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                        <!-- <table class="table table-condensed table-striped table-hover">
                            <tr>
                                <th width="40%">First Name:</th>
                                <td>{{ user.first_name }}</td>
                            </tr>
                            <tr>
                                <th>Last Name:</th>
                                <td>{{ user.last_name }}</td>
                            </tr>
                            <tr>
                                <th>Email Address:</th>
                                <td>{{ user.email }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td>{{ user.admin_role }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>{{ user.admin_status }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td>{{ user.phone }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td> {{ user.created }}</td>
                            </tr>
                            <tr>
                                <th>Updated:</th>
                                <td> {{ user.updated_at }}</td>
                            </tr>
                        </table> -->
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-8">
                        <v-btn dark small text color="blue darken-2" @click="statusDialog = true">{{ user.status == 1 ? 'Disable' : 'Enable' }}</v-btn>
                        <v-btn dark small text color="primary" :to="{name: 'AdminUpdateSuperUser', params: {id: user.id}}"><v-icon>edit</v-icon></v-btn>
                        <v-btn dark small text color="red darken-2" @click="confirmDelDialog = true"><v-icon>delete</v-icon></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="150">
                    <v-card-title class="primary white--text justify-center">Reset Password</v-card-title>
                    <v-card-text class="mt-4">
                        <v-btn text dark color="primary darken--2" @click="resetPassword = !resetPassword">Reset Password</v-btn>
                        <template v-if="resetPassword">
                            <v-text-field label="New Password" type="password" v-model="password.password" required ref="new_pswd" v-validate="'required'" :error-messages="errors.collect('new')" name="new" data-vv-as="new password"></v-text-field>
                            <v-text-field label="Confirm Password" type="password" v-model="password.password_confirmation" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('confirm')" name="confirm" data-vv-as="confirm password"></v-text-field>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="resetPassword">
                        <v-btn text dark color="red darken-2" @click="resetPassword = false">Cancel</v-btn>
                        <v-btn dark class="primary darken-2" :loading="isUpdating" @click="update">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center pt-8 mb-4">Do you really want to delete this admin user?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-2">
                    If you proceed to delete, the admin user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDialog = false">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteUser">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="statusDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center pt-8 mb-4">Do you want to {{ user && user.status == 0 ? 'enable' : 'disable'}} this super-user?</v-card-title>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="statusDialog = false">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="toggleUserStatus">Yes, {{ user && user.status == 0 ? 'enable' : 'disable' }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUpdatedSuperUser" :timeout="4000" top dark color="green darken-1">
            Super-user successfully updated.
            <v-btn text color="white--text" @click="adminUpdatedSuperUser = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="statusChanged" :timeout="4000" top color="green darken-1 white--text">
            User was {{ user && user.status == 0 ? 'disabled' : 'enabled' }} successfully.
            <v-btn text color="white--text" @click="statusChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetSuccessfull" :timeout="4000" top color="green darken-1 white--text">
            Password was reset successfully.
            <v-btn text color="white--text" @click="passwordResetSuccessfull = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetFailed" :timeout="6000" top color="red darken-1 white--text">
            Password reset failed. Please ensure that the new password is between 5 & 20 characters.
            <v-btn text color="white--text" @click="passwordResetFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            user: null,
            isLoading: false,
            id: this.$route.params.id,
            confirmDelDialog: false,
            statusDialog: false,
            statusChanged: false,
            resetPassword: false,
            password: {
                password: '',
                password_confirmation: ''
            },
            isUpdating: false,
            passwordResetSuccessfull: false,
            passwordResetFailed: false
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminUpdatedSuperUser(){
            return this.$store.getters.adminUpdatedSuperUser
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_super_user/${this.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
                console.log(res.data)
            })
        },
        deleteUser(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/super_user/${this.id}/delete`, {}, this.adminHeaders).then((res) => {
                this.confirmDelDialog = false
                this.$store.commit('userDeleted')
                this.$router.push({name: 'AdminSuperUsersList'})
            })
        },
        toggleUserStatus(){
            this.isToggling = true
            axios.post(this.api + `/auth-admin/change_superuser_status/${this.user.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isToggling = false
                this.$store.commit('adminUpdatedSuperUser')
                this.user.status = res.data
                this.statusDialog = false
            })
        },
        update(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/change_superuser_password/${this.id}`, {
                        password: this.password
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.passwordResetSuccessfull = true
                        this.resetPassword = false
                    }).catch(()=>{
                        this.isUpdating = false
                        this.passwordResetFailed = true
                    })
                }
            })
        }
    },
    created() {
        this.getUser()
    },
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
</style>
