<template>
    <v-container>
        <v-row justify="center" class="mt-6 pb-8">
            <v-col cols="12" md="5">
                <v-card light raised elevation="12" min-height="350">
                    <v-card-title class="primary white--text justify-center title font-weight-bold">Expert Login</v-card-title>
                    <v-card-text class="mt-4">
                        <v-text-field label="Email" type="text" v-model="cred.email"></v-text-field>
                        <v-text-field label="Password" type="password" v-model="cred.password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center px-5">
                        <v-btn color="primary" large block @click.prevent="login" :loading="isLoading">Login</v-btn>
                    </v-card-actions>
                    <v-card-actions class="justify-center pb-5">
                        Register as a tip expert? <v-btn text color="primary" :to="{name: 'ExpertRegister'}">Register</v-btn>
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
        authExpert(){
            return this.$store.getters.authExpert
        },
    },
    methods: {
        login(){
            this.isLoading = true
            axios.post(this.api + '/auth-expert/login', this.cred)
            .then((res) => {
                this.isLoading = false
                this.$store.commit('expertLoginSuccess', res.data)
                this.$router.push({name: 'ExpertDashboard'})
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
    }
}
</script>
