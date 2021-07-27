<template>
    <v-container>
        <expert-top-panel />
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="5">
                <v-card raised flat elevation="6" light min-height="150">
                    <v-card-title class="justify-center primary white--text sub_title">New Forecast</v-card-title>
                    <v-card-text class="mt-5">
                        <v-select :items="odds" item-text="type" return-object label="Odd Category" v-model="forecast.oddType" persistent-hint required></v-select>
                        <!-- <v-select :items="eventTypes" item-text="type" return-object label="Select Game Type" v-model="event" required></v-select> -->
                        <table class="table table-condensed table-borderless" v-if="forecast.oddType">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Odd Type</th>
                                    <td>{{ forecast.oddType.type }}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Event Type</th>
                                    <td>{{ event.type }}</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </v-card-text>
                    <!-- <v-card-actions class="justify-center pb-6 px-2">
                        <v-btn large dark class="primary darken-2" width="60%">Start Forecast</v-btn>
                    </v-card-actions> -->
                </v-card>
            </v-col>
            <v-col cols="12" md="7">
                <v-card raised flat elevation="6" light min-height="200">
                    <v-card-title class="justify-center primary white--text sub_title">New Forecast</v-card-title>
                    <v-card-text class="mt-7 px-10">
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-select :items="eventTypes" item-text="type" return-object label="Select Game Type" v-model="event" required></v-select>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Odd" v-model="pick.odd" placeholder="Type event odd here"></v-text-field>
                            </v-col>
                        </v-row>
                        <!-- <template v-if="event.id == 1"> -->
                            <!-- <h1>this shows club competiton</h1> -->
                            <club-competition-forecast v-if="event.id == 1" :countries="countries" :leagues="leagues" :teams="teams"/>
                        <!-- </template> -->
                        <template v-else>
                            <h1>this shows international competiton</h1>
                            <!-- <intnl-competition-forecast /> -->
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-8">
                        <v-btn text color="red darken-2" large width="35%" @click="clearPrediction">Cancel</v-btn>
                        <v-btn color="primary darken-2" large width="35%" :loading="isSaving">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            odds: [
                {id: 1, type: 3},
                {id: 2, type: 5},
                {id: 3, type: 10},
            ],
            forecast:{
                oddType: null
            },
            countries: [],
            leagues: [],
            teams: [],
            tips: [],
            eventTypes: [
                {id:1, type: 'Club Competition'},
                {id:2, type: 'International'},
            ],
            event: {
                id: null,
                type: ''
            },
            pick:{
                country: null,
                league: null,
                teamA: null,
                teamB:null,
                tip: null,
                otherTip: null,
                odd: null,
                date: new Date().toISOString().substr(0, 10),
                time: null
            },
            date: new Date().toISOString().substr(0, 10),
            datePicker: false,
            timeModal: false,
            isSaving: false,
            predictions: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        savedate(){
            console.log(this.pick.date)
        },
        clearPrediction(){
            this.pick = null
        },
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
        getCountries(){
            axios.get(this.api + '/countries')
            .then((res)=> {
                this.countries = res.data
                // console.log(res.data)
            })
        },
        getLeagues(){
            let country = this.pick.country
            console.log(country)
            axios.get(this.api + `/leagues_by_country/${country}`)
            .then((res) => {
                this.leagues = res.data
                // console.log(this.leagues)
            })
        },
        getTeams(){
            let league = this.pick.league
            axios.get(this.api + `/teams_by_league/${league}`)
            .then((res) => {
                this.teams = res.data
                console.log(res.data)
            })
        },
        getTips(){
            axios.get(this.api + `/get_all_tips`)
            .then((res) => {
                this.tips = res.data
            })
        }
    },
    created() {
        this.getCountries()
        this.getTips()
    },
}
</script>
