<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="Feedback Outbox" />
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
                                    <td style="border-top: none">{{ msg.created_at | moment('Do MMM, YYYY - h:mm a') }}</td>
                                </tr>
                                <tr>
                                    <th>To: </th>
                                    <td>Admin</td>
                                </tr>
                                <tr>
                                    <th>Subject:</th>
                                    <td>{{ msg.subject }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="newest body_text mx-4 mb-8">{{ msg.body }}</div>
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
            msg: null,
            isLoading: false,
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
        getMsg(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_feedback_outbox/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.msg = res.data
            })
        },

    },
    created() {
        this.getMsg()
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>

