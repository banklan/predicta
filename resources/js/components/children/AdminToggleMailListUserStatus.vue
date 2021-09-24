<template>
    <span>
        <v-btn small dark color="primary darken-2" @click="changeUserStatus(user)" :loading="isBusy">{{ user.status ? 'Disable' : 'Enable' }}</v-btn>&nbsp;
        <v-snackbar v-model="changeUserStatusFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to change the user status. Please try again later.
            <v-btn text color="white--text" @click="changeUserStatusFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="userStatusChanged" :timeout="4000" top color="green darken-1 white--text">
            User status has been changed successfully.
            <v-btn text color="white--text" @click="userStatusChanged = false">Close</v-btn>
        </v-snackbar>
    </span>
</template>

<script>
export default {
    props: ['user', 'index'],
    data() {
        return {
            isBusy: false,
            changeUserStatusFail: false,
            userStatusChanged: false,
            isUpdating: false,
            userDeleted: false,
            userDelFail: false
        }
    },
    computed: {
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
        }
    },
    methods: {
        changeUserStatus(user){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/toggle_mailing_list_status/${user.id}`, {}, this.adminHeaders)
            .then((res) =>{
                this.isBusy = false
                this.userStatusChanged = true
                user.status = res.data.status
            }).catch(() => {
                this.isBusy = false
                this.changeUserStatusFail = true
            })
        },
    }
}
</script>

