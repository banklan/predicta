<template>
    <v-container>
        <v-row justify="center" class="text-center">
            <v-col cols="10" md="10">
                <v-card v-if="!enquiryMailSent" elevation="12" light min-height="400" class="mx-auto pt-3">
                    <v-card-title class="headline justify-center mt-3 pb-5">Drop us a line</v-card-title>
                    <v-card-text class="mt-3">
                        <v-row justify="space-around">
                            <v-col cols="12" md="6">
                                <v-text-field label="Full Name" v-model="enquiry.fullname" placeholder="Fullname (Last name and First Name)" solo dense name="fullname" required v-validate="'required|min:3|max:100'" :error-messages="errors.collect('fullname')" data-vv-as="fullname"></v-text-field>
                                <v-text-field label="Organization" v-model="enquiry.organization" placeholder="Your Organization"  solo dense name="organization" v-validate="'required|min:3|max:50'" :error-messages="errors.collect('organization')" data-vv-as="organization"></v-text-field>
                                <v-text-field label="Position" v-model="enquiry.position" placeholder="Your position"  solo dense name="position" v-validate="'min:2|max:30'" :error-messages="errors.collect('position')" data-vv-as="position"></v-text-field>
                                <v-row wrap>
                                    <v-col cols="6">
                                        <v-text-field label="Email Address" v-model="enquiry.email" solo dense name="email" required v-validate="'required|email'" :error-messages="errors.collect('email')"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field label="Phone No" v-model="enquiry.phone" solo dense name="phone" required v-validate="'required|numeric|max:14'" :error-messages="errors.collect('phone')"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-text-field label="Subject" v-model="enquiry.subject" solo dense name="subject" required v-validate="'required|min:3|max:100'" :error-messages="errors.collect('subject')"></v-text-field>
                                <v-textarea label="Message" v-model="enquiry.message" rows="4" auto-grow solo dense name="message" required v-validate="'required|min:5|max:300'" :error-messages="errors.collect('message')"></v-textarea>
                                <v-btn block dark large color="primary darken-2" class="my-3" @click="sendEnquiry" :loading="loading">Send Enquiry</v-btn>
                            </v-col>
                            <v-col cols="12" md="5">
                                <div class="subtitle-1 font-weight-bold mt-5">You can reach us on any of the following means:</div>
                                <v-list>
                                    <v-list-item-group>
                                        <v-list-item v-for="(contact, i) in contacts" :key="i">
                                            <v-list-item-icon>
                                                <v-icon v-text="contact.icon" color="primary"></v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title v-text="contact.text"></v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                                <div class="socials mt-8 pa-2">
                                    <div class="subtitle-1 mb-4 font-weight-bold">Follow us on our social media accounts.</div>
                                    <div class="d-flex justify-space-around">
                                        <v-btn v-for="item in socials" :key="item.icon" icon :href="`${item.link}`" target="_blank" link>
                                            <v-icon :color="item.color" v-text="item.icon"></v-icon>
                                        </v-btn>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
                <template v-else>
                    <v-alert type="success" border="left" class="mt-10 mb-10">
                        Thank you for sending enquiry to lista.com. We shall reply you via your email address within the next 24 hours.
                    </v-alert>
                </template>
            </v-col>
        </v-row>
        <v-snackbar v-model="sendFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to send the enquiry. Please ensure all fields are validly filled and your device is connected to the internet before trying again.
            <v-btn text color="white--text" @click="sendFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data(){
        return{
            contacts: [
                {icon: 'email', text: 'info@tipexpats.com'},
                {icon: 'phone', text: '08023456789'},
                {icon: 'place', text: 'Some Address Here'},
            ],
            socials: [
                {icon: 'mdi-facebook', color: '#1877f2', link: 'https://www.facebook.com/tipexpats'},
                {icon: 'mdi-twitter', color: '#1da1f2', link: 'https://www.twitter.com/tipexpats'},
                {icon: 'mdi-instagram', color: '#c32aa3', link: 'https://www.instagram.com/tipexpats'},
            ],
            loading: false,
            enquiry: {
                fullname: '',
                organization: '',
                position: '',
                email: '',
                phone: '',
                subject: '',
                message: ''
            },
            enquiryMailSent: false,
            sendFail: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        }
    },
    methods:{
        sendEnquiry(){
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    this.loading = true
                    axios.post(this.api + '/send_enquiry', {
                        enquiry: this.enquiry
                    }).then(() => {
                        this.enquiryMailSent = true
                        this.clearEnquiryForm()
                        this.loading = false
                        console.log(res.data)
                    }).catch(() =>{
                        this.loading = false
                        this.sendFail = true
                    })
                }
            })
        },
        clearEnquiryForm(){
            this.$validator.pause()
            this.$validator.errors.clear()
            this.enquiry.fullname = ''
            this.enquiry.organization = ''
            this.enquiry.position = ''
            this.enquiry.email = ''
            this.enquiry.phone = ''
            this.enquiry.subject = ''
            this.enquiry.message = ''
        }
    }
}
</script>

<style scoped lang="scss">
    .contact{
        width: 100vw;
        background: rgba(238, 238, 238, .39);

        .banner{
            height: 18rem;
            width: 100%;
            background-image: linear-gradient(to bottom right, rgba(0, 59, 99, 0.89), rgba(255, 23, 69, 0.623)), url('/images/backgrounds/contact.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .v-card{
            margin-top: -7rem;
            z-index: 1000;
            margin-bottom: 8rem;

            .v-list{
                .v-list-item__content{
                    flex: none !important;
                }
            }
        }
    }
</style>

