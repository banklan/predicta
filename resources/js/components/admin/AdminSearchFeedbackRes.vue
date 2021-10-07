<template>
    <v-container>
        <admin-top-panel title="Daily Tips" />
        <v-row class="mt-n2">
            <v-col cols="12" md="5" offset-md="6">
                <admin-search model="Feedback" searchFor="feedbacks" />
            </v-col>
        </v-row>
        <v-row class="ml-n10 mt-n6">
            <v-col cols="12" md="10">
                <div class="subtitle-1">Search for <strong>{{ q }}</strong> returned {{ total | pluralize('result') }}. </div>
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light elevation="8" raised class="mt-5">
                    <v-card-text class="mx-3">
                        <template v-if="feedbacks.length > 0">
                            <v-row>
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th width="20%">Date</th>
                                            <th>Sender</th>
                                            <th>Folder</th>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="msg in feedbacks" :key="msg.id" :class="msg.is_read == false ? 'font-weight-bold' : ''">
                                            <td @click="viewMsg(msg)">{{ msg.created_at | moment('DD/MM/YY - H:ma')}}</td>
                                            <td @click="viewMsg(msg)">{{ msg.user ? msg.user.fullname : 'Admin' }}</td>
                                            <td @click="viewMsg(msg)">{{ msg.user_id === 9999999 ? 'Outbox' : 'Inbox' }}</td>
                                            <td @click="viewMsg(msg)">{{ msg.subject | truncate(60) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-row>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are no results to show for <strong>{{ q }}</strong>.
                            </v-alert>
                        </template>
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
            q: this.$route.query.q,
            feedbacks: [],
            total: null,
            isLoading: false,
            isUpdating: false,
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
    watch: {
        '$route.query.q': {
            handler(newVal){
                this.q = newVal
                this.isLoading = true
                axios.post(this.api + '/auth-admin/get_feedback_search_result', {
                    q: this.$route.query.q
                }, this.adminHeaders).then((res) => {
                    this.isLoading = false
                    this.feedbacks = res.data
                    this.total = res.data.length
                })
            },
            immediate: true
        }
    },
    methods: {
        getFeedbacks(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/get_feedback_search_result`, {
                q: this.$route.query.q
            }, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.feedbacks = res.data
                this.total = res.data.length
            }).catch(() => {
                this.isLoading = false
            })
        },
        viewMsg(msg){
            console.log(msg)
            if(msg.user_id === 9999999){
                this.$router.push({name: 'AdminFeedbackOutboxShow', params:{id: msg.id}})
            }else{
                this.$router.push({name: 'AdminFeedbackInboxShow', params:{id: msg.id}})
            }
        },
    },
    created(){
        this.getFeedbacks()
    }
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    .v-card{
        overflow-x: scroll !important;
    }
</style>
