<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Expert" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5 ml-n10">
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="400">
                    <template v-if="user">
                        <v-img v-if="user.picture" :src="`/images/profiles/experts/${user.picture}`" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                        <v-img v-else src="/images/profiles/experts/avatar.jpg" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                        <v-card-text>
                            <div class="subtitle-1 my-3 text-center">Expert Details</div>
                            <v-simple-table light>
                                <template v-slot:default>
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="35%">Expert ID:</th>
                                            <td>{{ user.expert_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>First Name:</th>
                                            <td>{{ user.first_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Name:</th>
                                            <td>{{ user.last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Username:</th>
                                            <td>{{ user.username }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ user.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone No:</th>
                                            <td>{{ user.phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>{{ user.status == 0 ? 'Disabled' : 'Enabled' }}</td>
                                        </tr>
                                        <tr v-if="user.bank">
                                            <th>Bank</th>
                                            <td>{{ user.bank.name }}</td>
                                        </tr>
                                        <tr v-if="user.account_name">
                                            <th>Account Name</th>
                                            <td>{{ user.account_name }}</td>
                                        </tr>
                                        <tr v-if="user.account_type">
                                            <th>Account Type</th>
                                            <td>{{ user.account_type }}</td>
                                        </tr>
                                        <tr v-if="user.account_no">
                                            <th>Account No</th>
                                            <td>{{ user.account_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Joined:</th>
                                            <td>{{ user.created }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ user.updated_at | moment('DD/MM/YY, H:ma') }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions class="justify-space-around pb-8">
                            <v-btn dark small text color="blue darken-2" @click="statusDialog = true">{{ user.status == 1 ? 'Disable' : 'Enable' }}</v-btn>
                            <v-btn dark small text color="primary" :to="{name: 'AdminExpertUpdate', params: {id: user.id}}"><v-icon>edit</v-icon></v-btn>
                            <v-btn dark small text color="red darken-2" @click="confirmDelDialog = true"><v-icon>delete</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-alert type="info" border="left" class="mt-5">
                            The expert user you are trying to view is invalid.
                        </v-alert>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card light raised outlined elevation="4" min-height="150">
                    <v-card-title class="primary white--text justify-center subtitle-1">Reset Password</v-card-title>
                    <v-card-text class="mt-4">
                        <v-btn text dark color="primary darken--2" @click="resetPassword = !resetPassword">Reset Password</v-btn>
                        <template v-if="resetPassword">
                            <v-text-field label="New Password" type="password" v-model="password.password" required ref="new_pswd" v-validate="'required'" :error-messages="errors.collect('new')" name="new" data-vv-as="new password"></v-text-field>
                            <v-text-field label="Confirm Password" type="password" v-model="password.password_confirmation" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('confirm')" name="confirm" data-vv-as="confirm password"></v-text-field>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="resetPassword">
                        <v-btn text dark color="red darken-2" @click="resetPassword = false">Cancel</v-btn>
                        <v-btn dark class="primary darken-2" :loading="isUpdating" @click="updatePswd">Submit</v-btn>
                    </v-card-actions>
                </v-card>
                <expert-brief-performance :expert="id" :active="activeSub" :count="subCount" />
                <expert-outstanding-earnings :expert="id" />
                <!-- <expert- -->
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialog" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-8 mb-4">Do you really want to delete this expert user?</v-card-title>
                <v-card-text class="text-center mt-4 subtitle-1">
                    If you proceed to delete, the expert user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDialog = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteUser" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="statusDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-8 mb-4">Do you want to {{ user && user.status == 0 ? 'enable' : 'disable'}} this expert user?</v-card-title>
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
        <v-snackbar :value="adminUpdatedExpert" :timeout="4000" top color="green darken-1 white--text">
            The expert user has been updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedExpert = false">Close</v-btn>
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
            passwordResetFailed: false,
            subCount: 0,
            activeSub: [],
            subscriptions: []
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
        },
        adminUpdatedExpert(){
            return this.$store.getters.adminUpdatedExpert
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert/${this.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
                // console.log(res.data)
            })
        },
        deleteUser(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/admin_del_expert/${this.id}`, {}, this.adminHeaders).then((res) => {
                this.confirmDelDialog = false
                this.$store.commit('expertUserDeleted')
                this.$router.push({name: 'AdminExpertList'})
            })
        },
        toggleUserStatus(){
            this.isToggling = true
            axios.post(this.api + `/auth-admin/toggle_expert_status/${this.user.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isToggling = false
                this.$store.commit('adminUpdatedExpert')
                this.user.status = res.data
                this.statusDialog = false
            })
        },
        updatePswd(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/change_expert_password/${this.id}`, {
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
        },
        getSubscriptions(){
            axios.get(this.api + `/auth-admin/admin_get_expert_subs/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                let rez = res.data
                this.subscriptions = rez
                this.subCount = rez.length
                let active = rez.filter((sub) => sub.active_status == 'Active')
                this.activeSub = active
                // let outstnd = rez.filter((sub) =>sub.)
            })
        }
    },
    created() {
        this.getUser()
        this.getSubscriptions()
    },
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
</style>
