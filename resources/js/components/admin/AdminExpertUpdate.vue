<template>
    <v-container>
        <v-row justify="start" class="mt-5">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="400" class="ml-n6">
                    <v-card-title class="justify-center sub_title primary white--text">Update Expert-User Details</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="First Name" v-model="user.first_name" required v-validate="'required'" :error-messages="errors.collect('first_name')" name="first_name"></v-text-field>
                        <v-text-field label="Last Name" v-model="user.last_name" required v-validate="'required'" :error-messages="errors.collect('last_name')" name="last_name"></v-text-field>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Phone Number" v-model="user.phone" v-validate="'required'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Email Address" v-model="user.email" v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-select label="Select Bank" :items="banks" item-text="name" item-value="id" v-model="user.bank_id"></v-select>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select label="Account Type" :items="accountTypes" item-text="type" item-value="type" v-model="user.account_type"></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Account No" v-model="user.account_no" v-validate="'numeric|max:10'" :error-messages="errors.collect('account_no')" name="account_no"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Account Name" v-model="user.account_name" v-validate="'min:5|max:60'" :error-messages="errors.collect('account_name')" name="account_name"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-4">
                        <v-btn width="40%" text large color="red darken-2" @click.prevent="$router.go(-1)">Cancel</v-btn>
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
                email: '',
                bank_id: null,
                account_type: '',
                account_no: '',
                account_name: ''
            },
            bankDetails:{
                bank_id: null,
                account_type: '',
                account_no: '',
                account_name: ''
            },
            banks: [],
            accountTypes: [
                {id: 1, type: 'Current'},
                {id: 2, type: 'Savings'},
                {id: 3, type: 'Others'},
            ],
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
            axios.get(this.api + `/auth-admin/admin_get_expert/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
                console.log(this.user)
            })
        },
        updateUser(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    console.log(this.edit)
                    axios.post(this.api + `/auth-admin/admin_update_expert/${this.id}`, {
                        user: this.user,
                        // bank_details: this.bankDetails
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.$store.commit('adminUpdatedExpert')
                        this.$router.push({name: 'AdminExpertDetail', params: {id: res.data.id}})
                        // console.log(res.data)
                    }).catch(() => {
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        getBanks(){
            axios.get(this.api + '/auth-admin/get_all_banks', this.adminHeaders)
            .then((res) => {
                this.banks = res.data
            })
        }
    },
    created() {
        this.getUser()
        this.getBanks()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow: scroll !important;
    }
</style>
