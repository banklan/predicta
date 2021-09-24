<template>
    <v-container class="">
        <admin-top-panel title="Enquiries" />
        <v-row justify="start" class="mt-5 mr-10">
            <v-col cols="12">
                <v-card light raised outlined elevation="4" min-height="200" class="scroll">
                    <v-card-text class="mt-5">
                        <div class="my-3 px-2 subtitle-1" v-if="enquiries.length > 0">Enquiries ({{ total }}) &nbsp; &nbsp; Unread({{ unreadCount }})</div>
                        <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                        <table v-else class="table table-hover table-condensed table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Email</th>
                                    <th>Sender</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(enquiry, i) in enquiries" :key="enquiry.id">
                                    <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.sent }} </td>
                                    <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.email }} </td>
                                    <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.fullname }} </td>
                                    <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.subject | truncate(50) }} </td>
                                    <td><v-btn small text color="red darken-2" @click="delConfirm(enquiry, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Are you sure you want to delete this enquiry?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, the enquiry will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken-2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary darken-2" :loading="isDeleting" width="40%" @click="delEnquiry">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminDelEnquiry" :timeout="4000" top color="green darken-1 white--text">
            An enquiry has been deleted successfully.
            <v-btn text color="white--text" @click="adminDelEnquiry = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            enquiries: [],
            pagination: {},
            q: '',
            confirmDelDial: false,
            enquiryToDel: null,
            enquiryToDelIndex: null,
            isDeleting: false,
            isLoading: false,
            total: 0,
            unreadCount: 0
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
        adminDelEnquiry(){
            return this.$store.getters.adminDeleteEnquiry
        },
    },
    methods: {
        getEnquiries(pag){
            pag = pag || `${this.api}/auth-admin/all_enquiries`
            this.isLoading = true
            axios.get(pag, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.enquiries = res.data.data
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
        showEnquiry(enquiry){
            this.$router.push({name: 'AdminEnquiryShow', params:{id: enquiry.id}})
        },
        delConfirm(enq, i){
            this.confirmDelDial = true
            this.enquiryToDel = enq
            this.enquiryToDelIndex = i
        },
        delEnquiry(){
            this.isDeleting = true
            let enq = this.enquiryToDel
            let ind = this.enquiryToDelIndex
            axios.post(this.api + `/auth-admin/admin_del_enquiry/${enq.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.enquiries.splice(ind, 1)
                this.$store.commit('adminDeleteEnquiry')
            })
        },
        search(){
            if(this.q.trim() !== ''){
                console.log(this.q)
                this.$router.push({name: 'AdminSearchPortfolioResult', query:{q: this.q}})
            }
        },
        getUnreadCount(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_unread_enquiry_count`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.unreadCount = res.data
            })
        }
    },
    created(){
        this.getEnquiries()
        this.getUnreadCount()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
    .v-btn, a{
        text-transform: none !important;
    }
</style>
