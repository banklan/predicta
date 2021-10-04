<template>
    <v-container>
        <v-row justify="center" class="mt-6">
            <v-col cols="12" md="5">
                <v-card light raised elevation="12" min-height="350" width="90%" class="mx-auto">
                    <v-card-title class="primary white--text justify-center title font-weight-bold">Admin Login</v-card-title>
                    <v-card-text class="mt-4">
                        <v-text-field label="Email" type="text" v-model="cred.email"></v-text-field>
                        <v-text-field label="Password" type="password" v-model="cred.password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center px-5">
                        <v-btn color="primary" large block @click.prevent="login" :loading="isLoading">Login</v-btn>
                    </v-card-actions>
                    <div v-if="authError" class="pb-5">
                        <div class="error white--text pa-4 mx-3">
                            {{ errorMsg }}
                        </div>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            cred: {
                email: '',
                password: ''
            },
            isLoading: false,
            authError: false,
            errorMsg: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authAdmin(){
            return this.$store.getters.authUser
        },
    },
    methods: {
        login(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/login', this.cred)
            .then((res) => {
                this.isLoading = false
                this.$store.commit('adminLoginSuccess', res.data)
                this.$router.push({name: 'AdminDashboard'})
            }).catch((err) => {
                this.isLoading = false
                this.authError = true
                if(err.response.status === 441){
                    this.errorMsg = 'Error! Invalid login credentials.'
                }else if(err.response.status === 501){
                    this.errorMsg = 'You cannot login into your account now. Kindly contact the admin.'
                }else{
                    this.errorMsg = 'Error! Invalid login credentials.'
                }
            })
        }
    }
}
</script>
