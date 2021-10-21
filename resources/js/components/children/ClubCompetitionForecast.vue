<template>
    <div>
        <v-row wrap justify="center">
            <v-col cols="12">
                <div class="caption">For UCL, EUFA & other FIFA club competitions not listed, click
                    <span class="ml-5">
                        <v-btn dark color="primary" @click="listed = !listed">Others</v-btn>
                    </span>
                </div>
            </v-col>
        </v-row>
        <v-row v-if="listed">
            <v-col cols="12" md="6">
                <v-select label="Country" :items="countries" item-text="country" return-object v-model="pickedCountry" @change="getLeagues" required v-validate="'required'" :error-messages="errors.collect('country')" name="country"></v-select>
            </v-col>
            <v-col cols="12" md="6">
                <v-select label="League" :items="leagues" item-text="league" return-object v-model="pickedLeague" @change="getTeams" required v-validate="'required'" :error-messages="errors.collect('league')" name="league"></v-select>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12" md="6">
                <v-text-field label="Country" placeholder="Type Europe for UCL & EUROPA" v-model="pick.country" required v-validate="'required|min:3|max:7'" :error-messages="errors.collect('country')" name="country"></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field label="Competition" placeholder="Type UCL or EUROPA" v-model="pick.league" required v-validate="'required|min:3|max:6'" :error-messages="errors.collect('competition')" name="competition"></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="listed">
            <v-col cols="12" md="6">
                <v-select label="Home" :items="teams" item-text="team" item-value="abbrv" v-model="pick.teamA" required v-validate="'required'" :error-messages="errors.collect('homeTeam')" name="homeTeam" data-vv-as="home team"></v-select>
            </v-col>
            <v-col cols="12" md="6">
                <v-select label="Away" :items="teams" item-text="team" item-value="abbrv" v-model="pick.teamB" required v-validate="'required'" :error-messages="errors.collect('awayTeam')" name="awayTeam" data-vv-as="away team"></v-select>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12" md="6">
                <v-text-field label="Home Team" placeholder="Home Team" v-model="pick.teamA" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('homeTeam')" name="homeTeam" data-vv-as="home team"></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field label="Away Team" placeholder="Away Team" v-model="pick.teamB" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('awayTeam')" name="awayTeam" data-vv-as="away team"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-select dense label="Prediction(Tip)" :items="tips" item-text="tip" item-value="abbrv" v-model="pick.tip" required v-validate="'required'" :error-messages="errors.collect('prediction')" name="prediction"></v-select>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field dense label="Other(Not listed)" placeholder="Enter Code eg HWEH" v-model="pick.otherTip" v-validate="'min:3|max:15'" :error-messages="errors.collect('prediction')" name="prediction" data-vv-as="other prediction"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-text-field label="Odd" v-model="pick.odd" placeholder="Type event odd here" required v-validate="'required|decimal:3'" :error-messages="errors.collect('odd')" name="odd"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-menu ref="datePicker" v-model="datePicker" :close-on-content-click="false" :return-value.sync="pick.date" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="pick.date" label="Event Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="pick.date" color="primary" elevation="4" scrollable :allowed-dates="disablePastDates" >
                        <div class="flex-grow-1"></div>
                        <v-btn text color="red darken-2" @click="datePicker = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.datePicker.save(pick.date)">Ok</v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="6">
                <v-dialog ref="timeDial" v-model="timeModal" :return-value.sync="pick.time" persistent width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="pick.time" label="Event Time" prepend-icon="mdi-clock-time-four-outline" only v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-time-picker v-if="timeModal" v-model="pick.time" full-width>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="timeModal = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.timeDial.save(pick.time)">OK </v-btn>
                    </v-time-picker>
                </v-dialog>
            </v-col>
        </v-row>
        <v-card-actions class="justify-space-around mt-5 pb-8">
            <v-btn text color="red darken-2" large width="25%" @click="clearPrediction">Cancel</v-btn>
            <v-btn color="primary darken-2" large width="50%" :loading="isSaving" @click="savePredictions">Save</v-btn>
        </v-card-actions>
        <v-snackbar v-model="saveError" :timeout="6000" top color="red darken-1 white--text">
            You have exceeded the number of games allowed in the accumulator. Please delete some games.
            <v-btn text color="white--text" @click="saveError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="saveSuccess" :timeout="4000" top color="green darken-1 white--text">
            A game has been added to your accumulator.
            <v-btn text color="white--text" @click="saveSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="oddError" :timeout="6000" top color="red darken-1 white--text">
            Game odds must be greater than 1.
            <v-btn text color="white--text" @click="oddError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="dateTimeError" :timeout="6000" top color="red darken-1 white--text">
            Game date and time cannot be empty.
            <v-btn text color="white--text" @click="dateTimeError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="setForecastOdd" :timeout="6000" top color="red darken-1 white--text">
            You must choose an odd category first to be able to forecast.
            <v-btn text color="white--text" @click="setForecastOdd = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    // props: ['forecastOdd'],
    data() {
        return {
            listed: true,
            countries: [],
            leagues: [],
            teams: [],
            tips: [],
            pickedCountry: null,
            pickedLeague: null,
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
            predictions: [],
            saveError: false,
            saveSuccess: false,
            oddError: false,
            dateTimeError: false,
            setForecastOdd: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        forecasts(){
            return this.$store.getters.getForecast
        },
        foreCastOdd(){
            return this.$store.getters.foreCastOdd
        },
    },
    methods: {
        clearPrediction(){
            this.pick = {}
        },
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
        getCountries(){
            axios.get(this.api + '/countries')
            .then((res)=> {
                this.countries = res.data
            })
        },
        getLeagues(){
            let country = this.pickedCountry
            axios.get(this.api + `/leagues_by_country/${country.id}`)
            .then((res) => {
                this.leagues = res.data
            })
        },
        getTeams(){
            let league = this.pickedLeague
            axios.get(this.api + `/teams_by_league/${league.id}`)
            .then((res) => {
                this.teams = res.data
            })
        },
        getTips(){
            axios.get(this.api + `/get_all_tips`)
            .then((res) => {
                this.tips = res.data
            })
        },
        savePredictions(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(this.foreCastOdd){
                        if(this.pick.odd > 1){
                            if(this.pick.date !== null && this.pick.time !== null){
                                if(this.listed){
                                    this.pick.country = this.pickedCountry.abbrv
                                    this.pick.league = this.pickedLeague.abbrv
                                }
                                this.pick.odd = parseFloat(this.pick.odd)
                                if(this.foreCastOdd == 3 && this.forecasts.length < 6 || this.foreCastOdd == 5 && this.forecasts.length < 7 || this.foreCastOdd == 10 && this.forecasts.length < 8){
                                    this.$store.commit('addTipsToForecast', this.pick)
                                    this.clearPrediction()
                                    this.saveSuccess = true
                                    this.$validator.pause()
                                    this.$validator.fields.items.forEach(field => field.reset())
                                    this.$validator.errors.clear()
                                }else{
                                    this.saveError = true
                                }
                            }else{
                                this.dateTimeError = true
                            }
                        }else{
                            this.oddError = true
                        }
                    }else{
                        this.setForecastOdd = true
                    }
                }
            })
        }
    },
    created() {
        this.getCountries()
        this.getTips()
    },
}
</script>
