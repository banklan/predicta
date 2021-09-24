<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="6" offset-md="6">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="Feedback" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light elevation="8" raised class="mt-5">
                    <v-tabs v-model="tab" grow background-color="primary--text" light>
                        <v-tab>Inbox <span v-if="unread > 0" class="ml-2"><v-chip color="primary lighten-2">{{ unread }}</v-chip></span></v-tab>
                        <v-tab>Outbox</v-tab>
                        <v-tab>New Feedback</v-tab>
                    </v-tabs>
                    <v-tabs-items v-model="tab">
                        <v-tab-item>
                            <v-card flat class="mt-8" light>
                                <v-card-text class="mx-3">
                                    <template v-if="inbox.length > 0">
                                        <v-row>
                                            <table class="table table-condensed table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Date</th>
                                                        <th>Subject</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(msg, index) in inbox" :key="msg.id" @click="showInbox(msg)" :class="msg.is_read == false ? 'font-weight-bold' : ''">
                                                        <td>{{ msg.created_at | moment('DD/MM/YY - H:ma')}}</td>
                                                        <td >{{ msg.subject | truncate(60) }}</td>
                                                        <td><v-btn  class="mt-n3" small color="red" text @click="confirmDelInbox(msg, index)"><v-icon color="red darken-2">delete_forever</v-icon></v-btn></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </v-row>
                                    </template>
                                    <template v-else>
                                        <v-alert type="info" class="mt-5">
                                            There are no inbox feedbacks to show.
                                        </v-alert>
                                    </template>
                                </v-card-text>
                                <v-card-actions class="my-5" v-if="inboxTotal > 0">
                                    <span class="pl-4">
                                        <v-btn color="primary" @click.prevent="getInboxMsgs(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getInboxMsgs(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getInboxMsgs(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getInboxMsgs(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                                    </span>
                                    <span class="pl-8">
                                        Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                    </span>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text class="mx-3 mt-3">
                                    <template v-if="outbox.length > 0">
                                        <table class="table table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Date</th>
                                                    <th>Subject</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(msg, index) in outbox" :key="msg.id" @click="showOutbox(msg)">
                                                    <td>{{ msg.created_at | moment('DD/MM/YY - H:ma')}}</td>
                                                    <td>{{ msg.subject | truncate(60) }}</td>
                                                    <td><v-btn color="red" text @click="confirmDelOutbox(msg, index)"><v-icon color="red darken-2">delete_forever</v-icon></v-btn></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </template>
                                    <template v-else>
                                        <v-alert type="info" class="mt-5">You currently have no messages in your outbox.</v-alert>
                                    </template>
                                </v-card-text>
                                <v-card-actions class="my-5" v-if="outbox.length > 0">
                                    <span class="pl-4">
                                        <v-btn color="primary" @click.prevent="getOutboxMsgs(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getOutboxMsgs(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getOutboxMsgs(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getOutboxMsgs(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                                    </span>
                                    <span class="pl-8">
                                        Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                    </span>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item>
                            <v-card flat>
                                <newuser-feedback />
                            </v-card>
                        </v-tab-item>
                    </v-tabs-items>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelInboxDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-4 primary white--text">Do you want to delete this message?</v-card-title>
                <v-card-actions class="mt-8 pb-8 justify-space-around">
                    <v-btn text color="red darken--2" @click="confirmDelInboxDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delInboxMsg" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelOutboxDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-4 primary white--text">Do you want to delete this message?</v-card-title>
                <v-card-actions class="mt-8 pb-8 justify-space-around">
                    <v-btn text color="red darken--2" @click="confirmDelOutboxDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delOutboxMsg" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="messageDeleted" :timeout="4000" top color="green darken-1 white--text">
            A feedback message has been deleted.
            <v-btn text color="white--text" @click="messageDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="messageDeleteFailed" :timeout="6000" top color="red darken-1 white--text">
            Error occured while trying to delete a message. Please try again.
            <v-btn text color="white--text" @click="messageDeleteFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            pagination: {},
            isLoading: false,
            tab: null,
            inbox: [],
            unread: 0,
            inboxTotal: null,
            outbox: [],
            isUpdating: false,
            outboxToDel: null,
            outboxIndexToDel: null,
            confirmDelOutboxDial: false,
            inboxToDel: null,
            inboxIndexToDel: null,
            confirmDelInboxDial: false,
            messageDeleted: false,
            messageDeleteFailed: false
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
        getOutboxMsgs(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth/get_users_outbox_messages`
            axios.get(pag, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.outbox = res.data.data
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        confirmDelOutbox(msg, ind){
            this.outboxToDel = msg
            this.outboxIndexToDel = ind
            this.confirmDelOutboxDial = true
        },
        delOutboxMsg(){
            this.isUpdating = true
            axios.post(this.api + `/auth/user_delete_outbox_msg/${this.outboxToDel.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isUpdating = false
                this.outbox.splice(this.outboxIndexToDel, 1)
                this.confirmDelOutboxDial = false
                this.messageDeleted = true
            }).catch(()=>{
                this.isUpdating = false
                this.confirmDelOutboxDial = false
                this.messageDeleteFailed = true
            })
        },
        getInboxMsgs(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth/get_users_inbox_messages`
            axios.get(pag, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.inbox = res.data.data
                this.inboxTotal = res.data.total
                let unread = res.data.data.filter((item) => item.is_read == false)
                this.unread = unread.length
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        confirmDelInbox(msg, ind){
            this.inboxToDel = msg
            this.inboxIndexToDel = ind
            this.confirmDelInboxDial = true
        },
        delInboxMsg(){
            this.isUpdating = true
            axios.post(this.api + `/auth/user_delete_inbox_msg/${this.inboxToDel.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isUpdating = false
                this.inbox.splice(this.inboxIndexToDel, 1)
                this.confirmDelInboxDial = false
                this.messageDeleted = true
            }).catch(()=>{
                this.isUpdating = false
                this.confirmDelInboxDial = false
                this.messageDeleteFailed = true
            })
        },
        showInbox(msg){
            this.$router.push({name: 'UserFeedbackShow', params:{id: msg.id}})
        },
        showOutbox(msg){
            this.$router.push({name: 'UserFeedbackOutboxShow', params:{id: msg.id}})
        }
    },
    created(){
        this.getOutboxMsgs()
        this.getInboxMsgs()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
</style>

