<template lang="">
    <v-container>
        <tips-route />
        <v-row>
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="300" class="scroll">
                    <v-card-title class="sub_title primary white--text justify-center">All Tip Experts</v-card-title>
                    <v-card-text>
                        <template v-if="experts.length > 0">
                            <v-simple-table light fixed-header height="300">
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th>Expert</th>
                                            <th>Open Tips</th>
                                            <th>Win Rate(%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="exp in experts" :key="exp.id" @click="goToExpert(exp)">
                                            <td class="primary--text font-weight-bold">{{ exp.username }}</td>
                                            <td>{{ exp.open_events_count }}</td>
                                            <td>{{ exp.winning_rate }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are no tip experts to display at the moment.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="8">
                <v-card raised outlined min-height="200" color="#c0e3ff" class="mt-5 pb-5">
                    <v-card-title class="justify-center title my-5">
                        Our premium Service
                    </v-card-title>
                    <v-card-text class="info_text">
                        Our premium service is created for investors who would rather make consistent profits from low-odd accumulators.
                        Our odd categories range from about 3 to 10 odds.
                        We carefully select from a crop of talented tipsters who have a demonstrable and proven track of consistent success. They are rated
                        according to their success rates within a period of time.
                        This is done intentionally to help potential subscribers make informed decisions on which tipster to subscribe to.
                        <p>We have implemented a process where the tipsters are required and motivated to put out their best tips everyday.
                        We also have a process that seeks to ensure that our tipsters publish their tips early enough.</p>
                        <p>Users can report or give us feedback relating to any of our tip experts or any other issues through the feedback page in users login.</p>
                    </v-card-text>
                    <v-card-title class="justify-center title">How our premium service works</v-card-title>
                    <v-card-text class="info_text">
                        Users are required to <router-link to="/register"><strong>sign up</strong></router-link> first. In the dashboard, there is a link to the available tipsters.
                        A user then chooses a tipster, an odd category they wish to subscribe to, and then subscribe.
                        After subscription, the user is given access to the expert tips for the next 7 days, after which the subscription expires.
                        <div class="mt-2">
                            Kindly contact us via any of the means on our <router-link to="/contact-us"><strong>contact</strong> </router-link> page for more information.
                        </div>
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
            experts: [],
            isLoading: false,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getExperts(){
            this.isLoading = true
            axios.get(this.api + '/get_experts_by_winning_rate').then((res) => {
                this.isLoading = false
                this.experts = res.data
            })
        },
        goToExpert(exp){
            this.$router.push({name: 'TipExpertView', params:{id: exp.expert_id}})
        }
    },
    created(){
        this.getExperts()
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
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tbody tr{
        cursor: pointer;
    }
    .info_text{
        font-size: 1rem !important;
        line-height: 1.55rem;
        color: rgba(0,0,0,.90) !important;
    }
</style>

