<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-card elevation="12" raised min-height="200" v-if="!requestSent">
                    <v-card-title class="justify-center primary darken-2 white--text subtitle-1">Promote your services</v-card-title>
                    <v-card-text class="mt-4">
                        <div class="subtitle-1 px-5">Please fill the form below to send us a request to advertise your products and services on our platform.</div>
                        <div class="subtitle-1 px-5">We will get back to you shortly on our rates and procedure.</div>
                        <div class="my-5 px-5">
                            <v-text-field label="Name" v-model="ad.name"></v-text-field>
                            <v-text-field label="Company Name" v-model="ad.company"></v-text-field>
                            <v-text-field label="Phone Number" v-model="ad.phone"></v-text-field>
                            <v-text-field label="Alternate Phone Number" v-model="ad.phone2"></v-text-field>
                            <v-text-field label="Email Address" v-model="ad.email"></v-text-field>
                            <v-text-field label="Website" v-model="ad.website"></v-text-field>
                            <v-textarea rows="2" auto-grow label="Briefly Describe the products and/or services that you render" v-model="ad.details"></v-textarea>
                        </div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn large dark color="primary darken-2" width="50%" :loading="isBusy" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
                <template v-else>
                    <v-alert type="success" border="left" class="mt-8">
                        Your request has been sent. We will get back to you shortly with the rates, requirements and procedure of placing your adverts on our platform.
                    </v-alert>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            ad: {
                name: '',
                company: '',
                phone: '',
                phone2: '',
                email: '',
                website: '',
                details: '',
            },
            isBusy: false,
            requestSent: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        submit(){
            this.isBusy = true
            axios.post(this.api + '/send_advert_request', {
                ad: this.ad
            }).then((res) => {
                this.isBusy = false
                console.log(res.data)
                this.requestSent = true
            }).catch(() => {
                this.isBusy = false
            })
        }
    }
}
</script>
