<template>
    <v-container>
        <v-row justify="start" class="mt-5">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="400" class="ml-n6">
                    <v-card-title class="justify-center sub_title primary white--text">Update User Details</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="First Name" v-model="user.first_name" required v-validate="'required'" :error-messages="errors.collect('first_name')" name="first_name"></v-text-field>
                        <v-text-field label="Last Name" v-model="user.last_name" required v-validate="'required'" :error-messages="errors.collect('last_name')" name="last_name"></v-text-field>
                        <v-text-field label="Phone Number" v-model="user.phone" v-validate="'required'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-4">
                        <v-btn width="40%" text large color="red darken-2" :to="{name: 'AdminUserShow', params:{id: user.id} }">Cancel</v-btn>
                        <v-btn width="40%" dark large color="primary" @click="updateUser" :loading="isUpdating">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="updateFailed" :timeout="4000" top dark color="red darken-1">
            Update of the admin failed. Please ensure all fields are validly filled.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                phone: '',
            },
            isLoading: false,
            isUpdating: false,
            id: this.$route.params.id,
            updateFailed: false
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
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_user/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        updateUser(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    console.log(this.edit)
                    axios.post(this.api + `/auth-admin/admin_update_user/${this.id}`, {
                        user: this.user,
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.$store.commit('adminUpdatedUser')
                        this.$router.push({name: 'AdminUserShow', params: {id: res.data.id}})
                    }).catch(() => {
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
    },
    created() {
        this.getUser()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow: scroll !important;
    }
</style>
