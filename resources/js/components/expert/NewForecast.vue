<template>
    <v-container>
        <expert-top-panel title="New Forecast"/>
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="6">
                <v-card raised flat elevation="6" light min-height="150">
                    <v-card-title class="justify-center primary white--text subtitle-1">New Forecast</v-card-title>
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
                                <v-select :items="bookmakers" item-text="name" return-object label="Select Bookmaker" v-model="bm.bkm" required></v-select>
                                <v-text-field label="Bookmaker's code" required v-model="bm.code"></v-text-field>
                                <v-card-actions class="justify-center" v-if="bm.code !== ''">
                                    <v-btn color="primary" dark @click="addBkmCode" :loading="isSaving">Add Code</v-btn>
                                </v-card-actions>
                            </div>
                        </template>
                        <div class="" v-if="bookmakersCode.length > 0">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Bookmaker</th>
                                        <th>Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(bk, index) in bookmakersCode" :key="index">
                                        <td>{{ bk.bkm.name }}</td>
                                        <td>{{ bk.code }}</td>
                                        <td><v-btn text color="red darken-2" @click="delBkm(index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </v-card-text>
                    <v-card-actions v-if="forecasts.length > 0 && bookmakersCode.length > 0" class="justify-center pb-8">
                        <v-btn large width="60%" dark color="primary darken-2" elevation="12" :loading="isBusy" @click="submitPrediction">Submit Prediction</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card raised flat elevation="6" light min-height="200">
                    <v-card-title class="justify-center primary white--text subtitle-1">New Forecast</v-card-title>
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
                <v-card raised flat elevation="6" light min-height="200" class="mt-5">
                    <v-card-title class="justify-center primary white--text subtitle-1">Important Notice</v-card-title>
                    <v-card-text class="">
                        <v-list>
                            <v-list-item-group class="">
                                <v-list-item>
                                    <v-list-item-content>Kindly note that the acca for 3 odds category must not exceed 5 games, while 5 odds and 10 odds must not exceed 6 and 7 games respectively.</v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>For every forecast created, experts must provide at least one bookmaker's betting code to ease the placing of the bets by users. </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>For tips on markets not provided, experts are expected to enter the markets personally. However, experts must use a descriptive acronymn to enable users understand.
                                        For a list of betting codes/markets and acronymns, check <span class="ext_links"><a :href="`https://www.goalballlive.com/betking-codes-and-meaning/`" target="_blank" link class="mr-2">here</a>
                                        or <a class="ml-2" :href="`https://www.goalballlive.com/bet9ja-betting-codes-meaning/`" target="_blank" link>here</a></span>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>
                                        Experts can publish as many forecasts as they like daily, however, they are expected to publish at least 5 forecasts in a 7-day(weekly) period.
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>
                                        For predictions to be eligible for ranking, they must be published on or before 10am on match days.
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>
                                        Experts earn 50% of total weekly subscriptions to their forecasts, irrespective of the results.
                                        Payments are made on Tuesdays and Wednesdays for the previous week's subscriptions.
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-content>
                                        While ensuring that they publish as many forecasts as they would like, experts must understand that they are rated according to their success rate.
                                        <span class="info--text my-1">The emphasis is on quality and not quantity, </span>so an expert who published just 5 forecasts in a week and won 4 of them (80% success rate) had performed better
                                        than one who published 20 forecasts and won 10(50% success rate) even though the latter won more forecasts.
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
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
        <v-snackbar v-model="mustIncludeABkCode" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to submit your predictions. You must include at least one bookmaker's betting code to submit your predictions.
            <v-btn text color="white--text" @click="mustIncludeABkCode = false">Close</v-btn>
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
            bm: {
                code: '',
                bkm: null
            },
            mustIncludeABkCode: false,
            isBusy: false
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
        bookmakersCode(){
            return this.$store.getters.bookmakersCode
        }
    },
    methods: {
        getOddTypes(){
        axios.get(this.api + `/get_odd_types`).then((res) => {
                this.odds = res.data
            })
        },
        setOdd(){
            this.$store.commit('setForecastOdd', parseInt(this.forecastOdd))
            let bm = []
            localStorage.setItem('bkmkCode', JSON.stringify(bm))
        },
        del(index){
            this.$store.commit('removeTip', index)
        },
        submitPrediction(){
            if(this.totalOdds >= this.foreCastOdd){
                if(this.bookmakersCode.length > 0){
                    this.isBusy = true
                    let predictions = JSON.parse(localStorage.getItem('forecast'))
                    axios.post(this.api + '/auth-expert/submit_expert_prediction', {
                        predictions: predictions,
                        forecastOdd: parseFloat(this.foreCastOdd),
                        totalOdds: parseFloat(this.totalOdds),
                        cds: this.bookmakersCode
                    }, this.expertHeader)
                    .then((res) =>{
                        this.isBusy = false
                        this.$store.commit('newForecastCreated')
                        this.$store.commit('clearForecast')
                        this.$router.push({name: 'MyForecasts'})
                        localStorage.removeItem('bkmkCode')
                    }).catch((err) =>{
                        this.isBusy = false
                        if(err.response.status === 401){
                            this.$router.push('/')
                        }
                    })
                }else{
                    this.mustIncludeABkCode = true
                }
            }else{
                this.cantSubmitForecast = true
            }
        },
        getBookmakers(){
            axios.get(this.api + '/get_all_bookmakers')
            .then((res) => {
                this.bookmakers = res.data
            })
        },
        addBkmCode(){
            this.isSaving = true
            let bcm = JSON.parse(localStorage.getItem('bkmkCode')) || []
            let itms = bcm.filter((item) => item.bkm.id !== this.bm.bkm.id)
            itms.unshift(this.bm)
            localStorage.setItem('bkmkCode', JSON.stringify(itms))
            this.$store.commit('addBookmakersCode')
            this.bm.code = ''
            this.bm.bkm = null
            this.isSaving = false
        },
        delBkm(index){
            let bcm = JSON.parse(localStorage.getItem('bkmkCode'))
            bcm.splice(index, 1)
            localStorage.setItem('bkmkCode', JSON.stringify(bcm))
            this.$store.commit('addBookmakersCode')
        }
    },
    created(){
        this.getOddTypes()
        this.getBookmakers()
    }
}
</script>

<style lang="css" scoped>
    .ext_links{
        display: flex;
    }
    .v-list-item__content{
        line-height: 1.8 !important;
    }
</style>
