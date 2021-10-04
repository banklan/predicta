<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card outlined flat min-height="300" class="mt-5 py-5">
                    <template v-if="!mailSubmitted">
                        <v-card-title class="justify-center subtitle-1 font-weight-regular">Join our Mailing List</v-card-title>
                        <v-card-text class="mt-5 caption_text px-5 mx-5">
                            Join a list of users who receives our daily sure bets in their mails every day for free.
                            <div class="mt-5 text-center pl-5 mr-10">
                                <v-text-field label="First Name" required v-model="mail.f_name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                                <v-text-field label="Last Name" required v-model="mail.l_name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                                <v-text-field label="Email" required v-model="mail.email" v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            </div>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6">
                            <v-btn large dark color="primary darken-2" width="50%" :loading="isSubmitting" @click="submit">Submit</v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-card-text class="mt-8 caption_text">
                            <v-alert type="success" border="left">
                                Thank you for joining our email list. You will receive daily sure tips from tipexpats.com henceforth.
                            </v-alert>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="submitFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to join our mailing list. Please fill all the fields validly and try again.
            <v-btn text color="white--text" @click="submitFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            mail: {
                f_name: '',
                l_name: '',
                email: ''
            },
            isSubmitting: false,
            submitFail: false,
            mailSubmitted: false,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isSubmitting = true
                    axios.post(this.api + '/join_mailing_list', {
                        mail: this.mail
                    }).then((res) => {
                        this.isSubmitting = false
                        this.mailSubmitted = true
                        console.log(res.data)
                    }).catch(()=> {
                        this.isSubmitting = false
                        this.submitFail = true
                    })
                }
            })
        }
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        background-image: linear-gradient(to bottom right, rgba(29, 91, 132, 0.59), rgba(25, 68, 97, 0.585)),url('/images/assets/email.png');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }
</style>
