<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="2">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <admin-top-panel title="User feedback" />
            </v-col>
        </v-row>
        <v-row v-if="showReply">
            <v-col cols="12" md="10" class="ml-n10">
                <v-card elevation="8" raised class="mt-5">
                    <v-card-text>
                        <div class="mb-4">
                            <table class="table table-condensed table-borderless">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="10%">Date:</th>
                                        <td>{{ newReply.created_at | moment('Do MMM, YYYY - H:mma') }}</td>
                                    </tr>
                                    <tr>
                                        <th>To:</th>
                                        <td>{{ replyReceiver }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject:</th>
                                        <td>{{ newReply.subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>Body:</th>
                                        <td>{{ newReply.body }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="10" class="ml-n10">
                <v-progress-circular indeterminate color="primary" :width="3" :size="20" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light elevation="8" raised class="mt-5">
                    <v-card-text v-if="openReplyField" class="px-5">
                        <div class="mb-3 px-5">
                            <table class="table table-condensed table-borderless">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th style="border-top: none" width="15%">Target:</th>
                                        <td style="border-top: none">{{ reply.target }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reply To:</th>
                                        <td v-if="feedback.user">{{ feedback.user.fullname }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-9 mt-5">
                            <v-text-field label="Subject" v-model="reply.subject" required v-validate="'required|min:3|max:60'" :error-messages="errors.collect('subject')" name="subject"></v-text-field>
                            <v-textarea rows="1" auto-grow label="Reply" v-model="reply.body" required v-validate="'required|min:5|max:600'" :error-messages="errors.collect('body')" name="body"></v-textarea>
                            <v-card-actions class="justify-center mt-4 pb-5">
                                <v-btn large text width="40%" color="red darken-2" @click="openReplyField = false">Cancel</v-btn>
                                <v-btn large dark width="40%" color="primary darken-2" :loading="isBusy" @click="sendReply">Send</v-btn>
                            </v-card-actions>
                        </div>
                    </v-card-text>
                    <v-card-text>
                        <div class="text-right my-3" v-if="!openReplyField">
                            <v-btn color="primary darken-2" dark @click="replyFeedback">Reply</v-btn>
                        </div>
                        <div class="px-5">
                            <table class="table table-condensed table-borderless">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="15%" style="border-top: none">Date:</th>
                                        <td style="border-top: none">{{ feedback.created_at | moment('Do MMM, YYYY - h:mm a') }}</td>
                                    </tr>
                                    <tr>
                                        <th>From: </th>
                                        <td>{{ feedback.user ? feedback.user.fullname : 'Admin' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject:</th>
                                        <td>{{ feedback.subject }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="newest body_text px-7 mb-8">{{ feedback.body }}</div>
                        <v-divider v-if="fbThreads.length > 0"></v-divider>
                        <div class="threads px-5" v-if="fbThreads.length > 0">
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
                        <div class="parent mt-5 px-5" v-if="parent">
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
        <v-snackbar v-model="feedbackSent" :timeout="4000" top color="green darken-1 white--text">
            Your reply has been sent.
            <v-btn text color="white--text" @click="feedbackSent = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="sendFeedbackFailed" :timeout="5000" top color="red darken-1 white--text">
            There was an error while trying to send your reply. Please try again.
            <v-btn text color="white--text" @click="sendFeedbackFailed = false">Close</v-btn>
        </v-snackbar>
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
            fbThreads: [],
            openReplyField: false,
            reply: {
                target: '',
                parent_id: '',
                user_id_to: null,
                receiver: '',
                subject: '',
                body: ''
            },
            isBusy: false,
            feedbackSent: false,
            sendFeedbackFailed: false,
            showReply: false,
            newReply: null,
            replyReceiver: ''
        }
    },
    computed:{
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
        },
    },
    methods: {
        getFeedback(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_user_feedback/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.feedback = res.data
                if(res.data.is_parent == false){
                    this.getParent(res.data.parent_id)
                    this.getFeedbackThread(this.$route.params.id)
                }
                this.updateIsRead()
            })
        },
        getParent(parent){
            axios.get(this.api + `/auth-admin/admin_get_feedback_parent/${parent}`, this.adminHeaders)
            .then((res) => {
                this.parent = res.data
            })
        },
        getFeedbackThread(fb){
            axios.get(this.api + `/auth-admin/admin_get_inbox_feedback_thread/${fb}`, this.adminHeaders)
            .then((res) => {
                this.fbThreads = res.data
            })
        },
        updateIsRead(){
            axios.post(this.api + `/auth-admin/admin_update_feedback_is_read/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                // console.log(res.data)
            })
        },
        replyFeedback(){
            this.openReplyField = true
            this.reply.target = this.feedback.target
            if(this.feedback.parent_id == null){
                this.reply.parent_id = this.feedback.id
            }else{
                this.reply.parent_id = this.feedback.parent_id
            }

            this.reply.user_id_to = this.feedback.user.id
            this.reply.receiver = this.feedback.user.fullname
            this.reply.subject = 'Re - ' + this.feedback.subject
        },
        sendReply(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/admin_post_feedback_reply/${this.$route.params.id}`, {
                reply: this.reply
            }, this.adminHeaders).then((res) => {
                this.isBusy = false
                this.replySent(res.data.fb, res.data.to)
                this.openReplyField = false
            }).catch(() => {
                this.isBusy = false
                this.sendFeedbackFailed = true
            })
        },
        replySent(reply, receiver){
            this.feedbackSent = true
            this.showReply = true
            this.newReply = reply
            this.replyReceiver = receiver
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

