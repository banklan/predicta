<template>
    <v-container>
        <expert-top-panel title="New Forecast"/>
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="6">
                <v-card raised flat elevation="6" light min-height="150">
                    <v-card-title class="justify-center primary white--text sub_title">New Forecast</v-card-title>
                    <v-card-text class="mt-5">
                        <v-select :items="odds" item-text="odd" item-value="odd" label="Odd Category" v-model="forecastOdd" persistent-hint required @change="setOdd"></v-select>
                        <v-alert type="info">
                            Kindly note that the acca for 3 odds must not exceed 5 games, while 5 odds and 10 odds must not exceed 6 games.
                        </v-alert>
                        <table class="table table-condensed table-borderless" v-if="foreCastOdd">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Odd Type</th>
                                    <td>{{ foreCastOdd }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-text v-if="forecasts.length > 0">
                        <table class="table table-condensed table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date / Time</th>
                                    <th>League</th>
                                    <th>Home</th>
                                    <th>Away</th>
                                    <th>Pred</th>
                                    <th>Odd</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(tip, index) in forecasts" :key="index">
                                    <td>{{ tip.date | moment('D/MM/YY')}}- {{ tip.time }}</td>
                                    <td>{{ tip.league }}</td>
                                    <td>{{ tip.teamA }}</td>
                                    <td>{{ tip.teamB }}</td>
                                    <td>{{ tip.otherTip ? tip.otherTip : tip.tip }}</td>
                                    <td>{{ tip.odd }}</td>
                                    <td><v-btn text small color="red darken-2" @click="del(index)">X</v-btn></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><strong>Total Odds:</strong></td>
                                    <td><strong>{{ totalOdds }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <template v-if="!showBookmakers">
                            <v-btn text color="secondary darken-3" @click="showBookmakers = true">Add Bookmaker's Codes</v-btn>
                        </template>
                        <template v-else>
                            <div class="pt-5 mb-3">
                                <div class="sub_title text-center">Bookmaker's codes</div>
                                <v-text-field label="Bet9ja" v-model="bm_codes.bet9ja"></v-text-field>
                                <v-text-field label="BetKing" v-model="bm_codes.betking"></v-text-field>
                                <v-text-field label="Merrybet" v-model="bm_codes.merrybet"></v-text-field>
                            </div>
                        </template>
                    </v-card-text>
                    <v-card-actions v-if="forecasts.length > 0" class="justify-center pb-8">
                        <v-btn large width="60%" dark color="primary darken-2" elevation="12" :loading="isSaving" @click="submitPrediction">Submit Prediction</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card raised flat elevation="6" light min-height="200">
                    <v-card-title class="justify-center primary white--text sub_title">New Forecast</v-card-title>
                    <v-card-text class="mt-7 px-10">
                        <v-row>
                            <v-col cols="12" md="8">
                                <v-select :items="eventTypes" item-text="type" return-object label="Select Game Type" v-model="event" required></v-select>
                            </v-col>
                        </v-row>
                        <club-competition-forecast v-if="event.id == 1"/>
                        <intnl-competition-forecast v-else :forecastOdd="forecastOdd" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="saveSuccess" :timeout="4000" top color="green darken-1 white--text">
            Your prediction was successfull submitted.
            <v-btn text color="white--text" @click="saveSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="saveFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to submit your predictions. Please check and try again.
            <v-btn text color="white--text" @click="saveFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="cantSubmitForecast" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to submit your predictions. Your accumulator odds must be equal or greater than the odd category you choose.
            <v-btn text color="white--text" @click="cantSubmitForecast = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            odds: [],
            forecastOdd: null,
            eventTypes: [
                {id:1, type: 'Club Competition'},
                {id:2, type: 'International'},
            ],
            event: {
                id: 1,
                type: 'Club Competition'
            },
            isSaving: false,
            saveSuccess: false,
            saveFail: false,
            cantSubmitForecast: false,
            showBookmakers: false,
            bookmakers: [],
            bm_codes: {
                bet9ja: '',
                betking: '',
                merrybet: ''
            }
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        foreCastOdd(){
            return this.$store.getters.foreCastOdd
        },
        forecasts(){
            return this.$store.getters.getForecast
        },
        totalOdds(){
            return this.$store.getters.totalOdds
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
        getOddTypes(){
            axios.get(this.api + `/get_odd_types`).then((res) => {
                this.odds = res.data
            })
        },
        setOdd(){
            // console.log(parseInt(this.forecastOdd))
            this.$store.commit('setForecastOdd', parseInt(this.forecastOdd))
        },
        del(index){
            this.$store.commit('removeTip', index)
        },
        submitPrediction(){
            if(this.totalOdds >= this.foreCastOdd){
                this.isSaving = true
                let predictions = JSON.parse(localStorage.getItem('forecast'))
                console.log(this.bm_codes)
                axios.post(this.api + '/auth-expert/submit_expert_prediction', {
                    predictions: predictions,
                    forecastOdd: parseFloat(this.foreCastOdd),
                    totalOdds: parseFloat(this.totalOdds),
                    codes: this.bm_codes
                }, this.expertHeader)
                .then((res) =>{
                    this.isSaving = false
                    console.log(res.data)
                    this.$store.commit('newForecastCreated')
                    this.$store.commit('clearForecast')
                    this.$router.push({name: 'MyForecasts'})
                }).catch((err) =>{
                    this.isSaving = false
                    if(err.response.status === 401){
                        this.$router.push('/')
                    }
                })
            }else{
                this.cantSubmitForecast = true
            }
        },
        getBookmakers(){
            axios.get(this.api + '/get_all_bookmakers')
            .then((res) => {
                this.bookmakers = res.data
            })
        }
    },
    created(){
        this.getOddTypes()
        this.getBookmakers()
    }
}
</script>
