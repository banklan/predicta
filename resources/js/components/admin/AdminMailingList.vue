 <template>
    <v-container>
        <admin-top-panel title="Mailing List" />
        <v-row class="mt-4" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="4" md="4" offset-md=8>
                <v-btn dark color="primary" @click="newMailingListDial = true"><v-icon left>add</v-icon>Add To Mailing List</v-btn>
            </v-col>
        </v-row>
        <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else class="mt-4 ml-n10" justify="start" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-card light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Mailing List <span class="ml-2"><v-chip color="primary lighten-2" v-if="mailList.length > 0">{{ mailList.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="mailList.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>FullName</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in mailList" :key="user.id">
                                        <td>{{ user.id }}</td>
                                        <td>{{ user.fullname }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.created_at | moment('DD/MM/YY') }}</td>
                                        <td>{{ user.user_status }}  <toggle-mail-user-status :user="user" />&nbsp; <v-btn small icon color="red darken-2" @click="openConfirmDelDial(user, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-7">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getList(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getList(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getList(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getList(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="newMailingListDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Add To Mailing List</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1 px-5">
                    <v-text-field label="First Name" required v-model="mail.f_name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                    <v-text-field label="Last Name" required v-model="mail.l_name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                    <v-text-field label="Email" required v-model="mail.email" v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="newMailingListDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="submit" width="40%">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Are you sure you want to delete this user from the list?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    If you proceed to delete, the user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="deleteUser" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="submitSuccess" :timeout="4000" top color="green darken-1 white--text">
            User has been joined to mailing list successfully.
            <v-btn text color="white--text" @click="submitSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="submitFail" :timeout="4000" top color="red darken-1 white--text">
            There was an error while trying to add the user to mailing list. Please ensure you are not using an email that is already existing on our mailing list before trying again.
            <v-btn text color="white--text" @click="submitFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="userDeleted" :timeout="4000" top color="green darken-1 white--text">
            User has been deleted successfully.
            <v-btn text color="white--text" @click="userDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            newMailingListDial: false,
            mailList: [],
            total: null,
            pagination: {},
            isLoading: false,
            isBusy: false,
            mail: {
                f_name: '',
                l_name: '',
                email: '',
            },
            submitSuccess: false,
            submitFail: false,
            confirmDelDial: false,
            userToDel: null,
            userIndexToDel: null,
            userDeleted: false,
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
        getList(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_admin_mailing_list`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.mailList = res.data.data
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
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isBusy = true
                    axios.post(this.api + '/auth-admin/admin_add_to_mailing_list', {
                        mail: this.mail
                    }, this.adminHeaders).then((res) => {
                        this.isBusy = false
                        this.submitSuccess = true
                        this.mailList.unshift(res.data)
                        this.mail = {}
                        this.newMailingListDial = false
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                        console.log(res.data)
                    }).catch(() => {
                        this.isBusy = false
                        this.submitFail = true
                    })
                }
            })
        },
        openConfirmDelDial(user, index){
            this.confirmDelDial = true
            this.userToDel = user.id
            this.userIndexToDel = index
        },
        deleteUser(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_user_from_mailing_list/${this.userToDel}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.mailList.splice(this.userIndexToDel, 1)
                this.confirmDelDial = false
                console.log(res.data)
                this.userDeleted = true
            }).catch(() => {
                this.isDeleting = false
                this.userDelFail = true
            })
        }
    },
    created(){
        this.getList()
    }
}
</script>

<style lang="css" scoped>
    .v-card .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td{
        white-space: nowrap !important;
    }
</style>
