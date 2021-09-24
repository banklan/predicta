<template>
    <v-container>
        <v-row justify="center" class="mt-6">
            <v-col cols="12" md="5">
                <v-card light raised elevation="12" min-height="320" width="90%" class="mx-auto">
                    <v-card-title class="primary white--text justify-center title font-weight-bold">User Login</v-card-title>
                    <v-card-text class="mt-4">
                        <v-text-field label="Email" type="text" v-model="cred.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                        <v-text-field label="Password" type="password" v-model="cred.password" required v-validate="'required|min:3'" :error-messages="errors.collect('password')" name="password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center px-5">
                        <v-btn color="primary" large block @click.prevent="login" :loading="isLoading">Login</v-btn>
                    </v-card-actions>
                    <v-card-actions class="justify-center mt-2 pb-6">
                        Not a member? <router-link to="/register">&nbsp; Register</router-link>
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
        redirectOnLogin(){
            return this.$store.getters.redirectOnLogin
        }
    },
    methods: {
        login(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + '/login', {
                        email: this.cred.email,
                        password: this.cred.password,
                    }).then((res) => {
                        this.isLoading = false
                        this.authError = false
                        this.$store.commit('userLoginSuccess', res.data)
                        // check if there is redirect on login
                        if(this.redirectOnLogin){
                            this.$router.push(this.redirectOnLogin)
                            localStorage.removeItem('redirOnLogin')
                        }else{
                            this.$router.push({name: 'UserDashboard'})
                        }
                    }).catch((err) => {
                        this.isLoading = false
                        this.authError = true
                        if(err.response.status === 441){
                            this.errorMsg = 'Error! Invalid credentials.'
                        }else if(err.response.status === 551){
                            this.errorMsg = 'You cannot login into your account now. Kindly contact the admin.'
                        }else{
                            this.errorMsg = 'Error! Invalid credentials.'
                        }
                    })
                }
            })
        }
    }
}
</script>
