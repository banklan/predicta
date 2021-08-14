<template>
    <v-container>
        <v-row align="center" class="justify-center">
            <template v-if="createFail">
                <v-col cols="12" md="10">
                    <v-alert type="error" border="left" class="mt-5 mb-1 pa-2">
                        <p class="font-italic mb-2 text-center">Error in registering user! </p>
                        <p>{{ createError }}</p>
                    </v-alert>
                </v-col>
            </template>
            <template v-if="!userCreated">
                <v-col cols="12" md="5">
                    <v-card light raised elevation="12" min-height="350" class="mx-auto my-10">
                        <v-card-title class="primary white--text justify-center headline font-weight-regular">Register User</v-card-title>
                        <v-card-text class="body-1 mt-4 px-7">
                            <v-text-field label="First Name" v-model="user.first_name" required placeholder="First Name/Given name" v-validate="'required|min:3|max:30'" :error-messages="errors.collect('first_name')" name="first_name"></v-text-field>
                            <v-text-field label="Last Name" v-model="user.last_name" required placeholder="Surname/Family Name" v-validate="'required|min:3|max:30'" :error-messages="errors.collect('last_name')" name="last_name"></v-text-field>
                            <v-text-field label="Email" type="text" v-model="user.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            <v-text-field label="Phone No" type="text" v-model="user.phone" required v-validate="'required|numeric|max:16'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                            <!-- <v-divider></v-divider> -->
                            <div class="my-3 subtitle-2 justify-center info lighten-3 pa-3 font-weight-bold">Create a password of between 5 & 20 alphanumeric characters.</div>
                            <v-text-field type="password" label="Password" v-model="user.password" required ref="pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('password')" name="password"></v-text-field>
                            <v-text-field type="password" label="Confirm Password" v-model="user.password_confirmation" required v-validate="'required|confirmed:pswd'" :error-messages="errors.collect('password_confirmation')" name="password_confirmation" data-vv-as="password confirmation"></v-text-field>
                        </v-card-text>
                        <v-card-actions class="justify-center">
                            <v-btn color="primary" width="50%" class="mb-5" large @click="register" :loading="isLoading">Register</v-btn>
                        </v-card-actions>
                        <v-card-actions class="justify-center mt-n4 pb-5 login_link">
                            Already a member? <router-link to="/login">&nbsp; Login</router-link>
                        </v-card-actions>
                        <v-card-actions class="justify-center mt-n4 pb-5 login_link">
                            Register as a tip expert? <router-link to="/expert-register">&nbsp; Register</router-link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </template>
            <template v-else>
                <v-col cols="12" md="8">
                    <v-alert type="success" border="left" class="mt-10 pa-4">
                        Thank you for registering on surepredict.com. We have sent a mail to your email address <strong>{{ user.email }}</strong> for email verification.
                    </v-alert>
                </v-col>
            </template>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            user: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                phone: ''
            },
            isLoading: false,
            createFail: false,
            createError: null,
            userCreated: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        register(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + `/register`, {
                        user: this.user
                    }).then((res) => {
                        this.isLoading = false
                        console.log(res.data)
                        // this.createError = ''
                        this.userCreated = true
                    }).catch((err) =>{
                        this.isLoading = false
                        if(err.response.status === 422){
                            this.createFail = true
                            // this.createError = Object.values(err.response.data.errors).flat()
                            this.createError = "The email you are trying to register with is already taken. please try another email."
                        }else{
                            this.createError = 'There was an error while trying to register. Please ensure you are connected to the internet and try again.'
                        }
                    })
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .login_link a{
        text-decoration: none;
    }
</style>
