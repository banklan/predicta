<template>
    <div>
        <v-card light raised elevation="8" min-height="100">
            <v-card-title class="subtitle-1 primary white--text justify-center">Open Events for 3 odds</v-card-title>
            <v-card-text>
                <template v-if="opened3.length > 0">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>Event ID</th>
                                <th>No of Games</th>
                                <th>Odd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tip in opened3" :key="tip.id">
                                <td>{{ tip.forecast_id }}</td>
                                <td>{{ tip.event_count }}</td>
                                <td>{{ tip.total_odds | formatOdds }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="pa-3 mt-5">
                        There are currently no opened events.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-5 mt-n2" v-if="opened3.length > 0">
                <v-btn v-if="userIsLoggedIn" text color="primary darken-2" @click="detailView(3, expert.expert_id)">View</v-btn>
                <v-btn v-else text color="primary darken-2" @click="goToLogin(3, expert.expert_id)">Login to access</v-btn>
            </v-card-actions>
        </v-card>
        <v-card light raised elevation="8" min-height="100" class="mt-5">
            <v-card-title class="subtitle-1 primary white--text justify-center">Open Events for 5 odds</v-card-title>
                <v-card-text>
                <template v-if="opened5.length > 0">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>Event ID</th>
                                <th>No of Games</th>
                                <th>Odd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tip in opened5" :key="tip.id">
                                <td>{{ tip.forecast_id }}</td>
                                <td>{{ tip.event_count }}</td>
                                <td>{{ tip.total_odds | formatOdds }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="pa-3 mt-5">
                        There are currently no opened events.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-5 mt-n2" v-if="opened5.length > 0">
                <v-btn v-if="userIsLoggedIn" text color="primary darken-2" @click="detailView(5, expert.expert_id)">View</v-btn>
                <v-btn v-else text color="primary darken-2" @click="goToLogin(5, expert.expert_id)">Login to access</v-btn>
            </v-card-actions>
        </v-card>
        <v-card light raised elevation="8" min-height="100" class="mt-5">
            <v-card-title class="subtitle-1 primary white--text justify-center">Open Events for 10 odds</v-card-title>
                <v-card-text>
                <template v-if="opened10.length > 0">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>Event ID</th>
                                <th>No of Games</th>
                                <th>Odd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tip in opened10" :key="tip.id">
                                <td>{{ tip.forecast_id }}</td>
                                <td>{{ tip.event_count }}</td>
                                <td>{{ tip.total_odds | formatOdds }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="pa-3 mt-5">
                        There are currently no opened events.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-5 mt-n2" v-if="opened10.length > 0">
                <v-btn v-if="userIsLoggedIn" text color="primary darken-2" @click="detailView(10, expert.expert_id)">View</v-btn>
                <v-btn v-else text color="primary darken-2" @click="goToLogin(10, expert.expert_id)">Login to access</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['opened3', 'opened5', 'opened10', 'expert'],
    data(){
        return{
            // isLoading: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
    },
    methods:{
        detailView(odd, expert){
            this.$router.push({name: 'TipOddView', params: {odd: odd, expert: expert}})
        },
        goToLogin(odd, expert){
            this.$store.commit('redirectOnLogin', this.$router.currentRoute.path)
            this.$router.push('/login')
        }
    }
}
</script>
