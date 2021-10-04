<template lang="">
    <v-container>
        <tips-route />
        <v-row>
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="space-around">
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="300">
                    <v-card-title class="sub_title primary white--text justify-center">Tip Expert &nbsp;<span v-if="expert">{{ expert.username }}</span></v-card-title>
                    <v-img :src="`/images/profiles/experts/${ expert.picture ? expert.picture : 'avatar.jpg'}`" width="100%" height="300" transition="scale-transition"></v-img>
                    <v-card-text class="mt-5 pa-3">
                        <template v-if="expert">
                            <div class="sub_title text-center mb-3">Details</div>
                            <table class="table table-condensed table-hover">
                                <thead> </thead>
                                <tbody>
                                    <tr>
                                        <th style="border-top: none">Username:</th>
                                        <td style="border-top: none">{{ expert.username }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">Games Forecasted:</th>
                                        <td>{{ expert.event_count }}</td>
                                    </tr>
                                    <tr>
                                        <th>Winning Rate:</th>
                                        <td>{{ expert.winning_rate }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Open Forecast:</th>
                                        <td>{{ expert.open_events_count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                The tip expert you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6" v-if="authUser">
                        <v-btn v-if="!followedExpert" elevation="6" rounded color="primary darken-2" :loading="isAdding" @click="followExpert"><v-icon left>person_add</v-icon>Follow {{ expert.username }}</v-btn>
                        <v-btn v-else elevation="6" rounded disabled>Following {{ expert.username }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <experts-premium-tips :opened3="opened3" :opened5="opened5" :opened10="opened10" :expert="expert" />
                </template>
            </v-col>
        </v-row>
        <v-snackbar v-model="followSuccess" :timeout="4000" top color="green darken-1 white--text">
            You have successfully followed {{ expert ? expert.username : 'the expert' }}.
            <v-btn text color="white--text" @click="followSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="followFailed" :timeout="5000" top color="red darken-1 white--text">
            An error occured! Please try again later.
            <v-btn text color="white--text" @click="followFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            expert: null,
            isLoading: false,
            opened3: [],
            opened5: [],
            opened10: [],
            isAdding: false,
            followedExpert: false,
            followSuccess: false,
            followFailed: false
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
        getExperts(){
            this.isLoading = true
            axios.get(this.api + `/get_tip_expert/${this.$route.params.id}`).then((res) => {
                this.isLoading = false
                this.expert = res.data
                console.log(res.data)
                let predicts = this.expert.prediction_summary
                let open3 = predicts.filter(item => item.is_opened == true && item.forecast_odd == 3)
                this.opened3 = open3
                let open5 = predicts.filter(item => item.is_opened == true && item.forecast_odd == 5)
                this.opened5 = open5
                let open10 = predicts.filter(item => item.is_opened == true && item.forecast_odd == 10)
                this.opened10 = open10
            })
        },
        getOpenedEvents(){
            this.isLoading = true
            axios.get(this.api + `/get_open_events_for_expert/${this.$route.params.id}`).then((res) => {
                this.isLoading = false
                this.openEvents = res.data
            })
        },
        followExpert(){
            this.isAdding = true
            axios.post(this.api + `/auth/follow_expert/${this.$route.params.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isAdding = false
                this.followedExpert = true
                this.followSuccess = true
                console.log(res.data)
            }).catch(() => {
                this.isAdding = false
                this.followFailed = true
            })
        },
        checkIfFollowed(){
            if(this.authUser){
                axios.get(this.api + `/auth/check_if_followed/${this.$route.params.id}`, this.authHeaders)
                .then((res) => {
                    if(res.data.message == true){
                        this.followedExpert = true
                    }else{
                        this.followedExpert = false
                    }
                })
            }
        }
    },
    created(){
        this.getExperts()
        this.checkIfFollowed()
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
    .sub_title {
        font-size: 1.2rem;
        font-weight: 500;
    }
    .v-container{
        background-color: #000 !important;
    }
    table tr{
        cursor: pointer;
    }
</style>

