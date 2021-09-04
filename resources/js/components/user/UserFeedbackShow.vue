<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="Feedback Inbox" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="3" :size="20" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light elevation="8" raised class="mt-5">
                    <v-card-text>
                        <table class="table table-condensed">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th width="15%" style="border-top: none">Date:</th>
                                    <td style="border-top: none">{{ feedback.created_at | moment('Do MMM, YYYY - h:mm a') }}</td>
                                </tr>
                                <tr>
                                    <th>From: </th>
                                    <td>Admin</td>
                                </tr>
                                <tr>
                                    <th>Subject:</th>
                                    <td>{{ feedback.subject }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="newest body_text mx-4 mb-8">{{ feedback.body }}</div>
                        <v-divider v-if="fbThreads.length > 0"></v-divider>
                        <div class="threads" v-if="fbThreads.length > 0">
                            <div v-for="(fb, index) in fbThreads" :key="fb.id">
                                <table class="table table-condensed">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="15%" style="border-top: none">Date:</th>
                                            <td style="border-top: none">{{ fb.created_at | moment('Do MMM, YYYY - h:mm a') }}</td>
                                        </tr>
                                        <tr>
                                            <th>From: </th>
                                            <td v-if="fb.user_id === 9999999">Admin</td>
                                            <td v-if="fb.user">{{ fb.user.fullname }}</td>
                                        </tr>
                                        <tr>
                                            <th>Subject:</th>
                                            <td>{{ fb.subject }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="body_text mx-4 mb-8">{{ fb.body }}</div>
                                <v-divider v-if="index < fbThreads.length - 1"></v-divider>
                            </div>
                        </div>
                        <v-divider v-if="parent"></v-divider>
                        <div class="parent mt-5" v-if="parent">
                            <table class="table table-condensed">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="15%" style="border-top: none">Date:</th>
                                        <td style="border-top: none">{{ parent.created_at | moment('Do MMM, YYYY - h:mm a') }}</td>
                                    </tr>
                                    <tr>
                                        <th>From: </th>
                                        <td v-if="parent.user">{{ parent.user.fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject:</th>
                                        <td>{{ parent.subject }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="body_text mx-4 mb-8">{{ parent.body }}</div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
    export default {
    data() {
        return {
            feedback: null,
            msgs: [],
            isLoading: false,
            parent: null,
            fbThreads: []
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
    },
    methods: {
        getFeedback(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_user_feedback/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.feedback = res.data
                this.updateIsRead()
                this.getParent(res.data.parent_id)
                this.getFeedbackThread(this.$route.params.id)
            })
        },
        getParent(parent){
            axios.get(this.api + `/auth/get_feedback_parent/${parent}`, this.authHeaders)
            .then((res) => {
                this.parent = res.data
            })
        },
        getFeedbackThread(fb){
            axios.get(this.api + `/auth/get_feedback_thread/${fb}`, this.authHeaders)
            .then((res) => {
                this.fbThreads = res.data
                // console.log(res.data)
            })
        },
        updateIsRead(){
            axios.post(this.api + `/auth/update_feedback_is_read/${this.$route.params.id}`, {}, this.authHeaders)
            .then((res) => {
                // console.log(res.data)
            })
        }
    },
    created() {
        this.getFeedback()
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>

