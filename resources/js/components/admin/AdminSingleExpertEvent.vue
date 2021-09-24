<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel :title="`Expert Event ${id}`" />
            </v-col>
        </v-row>
        <v-row class="justify-start mt-5 ml-n10">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text">Expert Forecast - Event </v-card-title>
                    <v-card-text>
                        <template v-if="event">
                            <table class="table table-condensed table-hover table-striped">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ event.id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Forecast ID</th>
                                        <td>{{ event.prediction_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert</th>
                                        <td>{{ event.expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event</th>
                                        <td>{{ event.home }} Vs {{ event.away }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prediction</th>
                                        <td>{{ event.tip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd</th>
                                        <td>{{ event.odd }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ event.date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td>{{ event.time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ event.status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The event you are trying to view does not exist.
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
            id: this.$route.params.id,
            isLoading: false,
            event: null
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
        getEvent(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_expert_event/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.event = res.data
            })
        }
    },
    created(){
        this.getEvent()
    }
}
</script>
