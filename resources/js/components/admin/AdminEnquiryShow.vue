<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <admin-top-panel title="Enquiry" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-3 ml-n10">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <v-col cols="12" md="9">
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-text class="body-1 mt-5 px-7 py-10">
                            <template v-if="enquiry">
                                <table class="table table-borderless table-responsive-sm table-condensed table-striped">
                                    <tr>
                                        <th width="20%">Sent:</th>
                                        <td>{{ enquiry.sent }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ enquiry.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fullname:</th>
                                        <td>{{ enquiry.fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Organization:</th>
                                        <td>{{ enquiry.organization }}</td>
                                    </tr>
                                    <tr>
                                        <th>Position:</th>
                                        <td>{{ enquiry.position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone No:</th>
                                        <td>{{ enquiry.phone }}</td>
                                    </tr>
                                </table>
                                <div class="subtitle-1 pa-2 mt-5 font-weight-bold">{{ enquiry.subject }}</div>
                                <div class="body_text pa-2 mt-n1">{{ enquiry.message }}</div>
                                <div class="text-center mt-3">
                                    <v-btn text color="red darken-2" @click="confirmDelDial = true">
                                        <v-icon>delete_forever</v-icon>
                                    </v-btn>
                                </div>
                            </template>
                            <template v-else>
                                <v-alert type="warning" border="left" class="mt-5">
                                    The enquiry you are trying to view does not exist.
                                </v-alert>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-col>
            </template>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary darken-2 white--text justify-center">Are you sure you want to delete this enquiry?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, the enquiry will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken-2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" width="40%" @click="delEnquiry">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            enquiry: null,
            isLoading: false,
            confirmDelDial: false,
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getEnquiry(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_enquiry/${this.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.enquiry = res.data
                this.enquiryHasBeenread()
            }).catch(() =>{
                this.isLoading = false
            })
        },
        delEnquiry(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_del_enquiry/${this.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.$router.push({name: 'AdminEnquiries'})
                this.$store.commit('adminDeleteEnquiry')
            })
        },
        enquiryHasBeenread(){
            axios.post(this.api + `/auth-admin/enquiry_was_read/${this.id}`, {}, this.adminHeaders)
            .then((res) => {
                console.log(res.data)
            })
        }
    },
    created(){
        this.getEnquiry()
    }
}
</script>

<style lang="css" scoped>
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
