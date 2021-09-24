 <template>
    <v-container>
        <admin-top-panel title="Mailed Daily Tips" />
        <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else class="mt-4 ml-n10" justify="start">
            <v-col cols="12" md="10">
                <v-card light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Mailed tips <span class="ml-2"><v-chip color="primary lighten-2" v-if="mails.length > 0">{{ total }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="mails.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Daily Tip</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(mail, index) in mails" :key="mail.id">
                                        <td>{{ mail.id }}</td>
                                        <td v-if="mail.daily_tips_summary">{{ mail.daily_tips_summary.tip_code }}</td>
                                        <td>{{ mail.created_at | moment('DD/MM/YY') }}</td>
                                        <td><v-btn small text color="red darken-2" @click="openConfirmDelDial(mail, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-7">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getMails(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMails(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMails(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMails(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Are you sure you want to delete this mail from the list?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    If you proceed to delete, the mail will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="deleteMail" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="mailDelFail" :timeout="4000" top color="red darken-1 white--text">
            There was an error while trying to delete the mail. Please ensure you are not using an email that is already existing on our mailing list before trying again.
            <v-btn text color="white--text" @click="mailDelFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="mailDeleted" :timeout="4000" top color="green darken-1 white--text">
            Mail has been deleted successfully.
            <v-btn text color="white--text" @click="mailDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            mails: [],
            total: null,
            pagination: {},
            isLoading: false,
            isBusy: false,
            confirmDelDial: false,
            mailToDel: null,
            mailIndexToDel: null,
            mailDeleted: false,
            mailDelFail: false,
            isDeleting: false
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
        },
    },
    methods: {
        getMails(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_admin_mailed_tips`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.mails = res.data.data
                this.total = res.data.total
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
        openConfirmDelDial(mail, index){
            this.confirmDelDial = true
            this.mailToDel = mail.id
            this.mailIndexToDel = index
        },
        deleteMail(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_mailed_daily_tip/${this.mailToDel}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.mails.splice(this.mailIndexToDel, 1)
                this.confirmDelDial = false
                console.log(res.data)
                this.mailDeleted = true
                this.total--
            }).catch(() => {
                this.isDeleting = false
                this.mailDelFail = true
            })
        }
    },
    created(){
        this.getMails()
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
