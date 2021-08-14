<template>
     <v-container>
        <tips-route />
        <v-row class="mt-n7">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" :title="`Subscription ${sub_id}`" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="7">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-text>
                        <template v-if="subscription">

                        {{ subscription }}
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card light raised elevation="8" min-height="100" class="mt-5">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Other Subscriptions By </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="tipSubscribed" :timeout="5000" top color="green darken-1 white--text">
            Thank you for subscribing.
            <v-btn text color="white--text" @click="tipSubscribed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            sub_id: this.$route.params.sub_id,
            isLoading: false,
            subscription: null,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        tipSubscribed(){
            return this.$store.getters.tipSubscribed
        }
    },
    methods: {
        getSubscription(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_subscription/${this.$route.params.sub_id}`, this.authHeaders)
            .then((res)=> {
                this.isLoading = false
                this.subscription = res.data
            })
        }
    },
    created() {
        this.getSubscription()
    },
}
</script>
