<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="2">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <expert-top-panel title="Followers"/>
            </v-col>
        </v-row>
        <v-row justify="space-around" class="mt-5">
            <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
            <v-col v-else cols="12" md="6">
                <v-card raised elevation="10" min-height="150">
                    <v-card-title class="justify-center subtitle-1 primary white--text mb-5">All Followers <v-chip v-if="followers.length > 0" class="ml-2 primary lighten-2">{{ followers.length }}</v-chip></v-card-title>
                    <template v-if="followers.length > 0">
                        <v-card-text>
                        <table class="table table-condensed table-hover table-borderless">
                            <thead></thead>
                            <tbody>
                                <tr v-for="follow in followers" :key="follow.id">
                                    <td>{{ follow.user.fullname }}</td>
                                </tr>
                            </tbody>
                        </table>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            followers: [],
            isLoading: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authExpert(){
            return this.$store.getters.authExpert
        },
        expertHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authExpert.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getFollowers(){
            this.isLoading = true
            axios.get(this.api + '/auth-expert/get_expert_followers', this.expertHeader)
            .then((res) => {
                this.isLoading = false
                this.followers = res.data
            })
        }
    },
    mounted(){
        this.getFollowers()
    }
}
</script>
