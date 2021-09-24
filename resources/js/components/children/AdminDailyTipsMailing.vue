<template>
    <div>
        <v-card light raised elevation="8" min-height="150" class="mt-8">
            <v-card-title class="justify-center subtitle-1 white--text primary">Daily Tip Mailing</v-card-title>
            <template v-if="dailyTipMail > 0">
                <v-card-text class="mt-5 info--text body_text darken-3">
                    This daily tip has been mailed. </strong>
                </v-card-text>
                <v-card-actions class="justify-center pb-6">
                    <v-btn large dark color="primary" @click="confirmDial = true">Mail DailyTips Again</v-btn>
                </v-card-actions>
            </template>
            <template v-else>
                <v-card-text class="mt-5 body_text warning--text darken-2">
                    This Daily Tip has not been mailed.
                </v-card-text>
                <v-card-actions class="justify-center pb-6">
                    <v-btn large dark color="primary" @click="confirmDial = true">Mail DailyTips</v-btn>
                </v-card-actions>
            </template>
        </v-card>
        <v-dialog v-model="confirmDial" max-width="500">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Are you sure you want to mail this daily tips?</v-card-title>
               <v-card-actions class="pb-8 mt-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="mailDailyTips" width="40%">Yes, send mail</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="mailSuccess" :timeout="4000" top color="green darken-1 white--text">
            The daily tip has been mailed to the subscribers.
            <v-btn text color="white--text" @click="mailSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="mailFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to mail this daily tips to the subscribers. Please ensure you are connected to the internet before trying again.
            <v-btn text color="white--text" @click="mailFailed = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    props: ['summary'],
    data() {
        return {
            isLoading: false,
            dailyTipMail: null,
            confirmDial: false,
            isBusy: false,
            mailSuccess: false,
            mailFailed: false
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
        getMailing(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_check_daily_tips_mailing/${this.summary.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.dailyTipMail = res.data
                // console.log(res.data)
            })
        },
        mailDailyTips(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/mail_daily_tips/${this.summary.id}`, {},this.adminHeaders)
            .then((res) => {
                this.isBusy = false
                this.confirmDial = false
                this.mailSuccess = true
            }).catch(()=> {
                this.isBusy = false
                this.mailFailed = true
            })
        }
    },
    created() {
        this.getMailing()
    },
}
</script>
