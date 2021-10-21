<template>
    <div>
        <v-row>
            <v-col cols="12">
                <v-select label="Competitions" :items="competitions" item-text="competition" item-value="abbrv" v-model="pick.league" required v-validate="'min:3'" :error-messages="errors.collect('competition')" name="competition"></v-select>
                <v-checkbox class="mt-n3" v-model="compNotListed" label="Click to enter manually if Competition Not Listed"></v-checkbox>
            </v-col>
            <v-col cols="12" v-if="compNotListed">
                 <v-text-field label="Type Competition Name" placeholder="e.g Women U-17 WC" v-model="otherLeague" required v-validate="'min:3|max:8'" :error-messages="errors.collect('otherCompetition')" name="otherCompetition" data-vv-as="other competition"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-select label="Home Team" :items="teams" item-text="country" item-value="abbrv" v-model="pick.teamA" required v-validate="'min:3'" :error-messages="errors.collect('home')" name="home" data-vv-as="home team"></v-select>
                <v-checkbox class="mt-n3" v-model="homeNotListed" label="Click to enter Home team manually"></v-checkbox>
            </v-col>
            <v-col cols="12" v-if="homeNotListed">
                <v-text-field label="Home Team" placeholder="Type Home Team" v-model="otherTeamA" required v-validate="'min:3|max:15'" :error-messages="errors.collect('otherHome')" name="otherHome" data-vv-as="home team"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-select label="Away Team" :items="teams" item-text="country" item-value="abbrv" v-model="pick.teamB" required v-validate="'min:3'" :error-messages="errors.collect('away')" name="away" data-vv-as="away team"></v-select>
                <v-checkbox class="mt-n3" v-model="awayNotListed" label="Click to enter Away team manually"></v-checkbox>
            </v-col>
            <v-col cols="12" v-if="awayNotListed">
                <v-text-field label="Away Team" placeholder="Type Away Team" v-model="otherTeamB" required v-validate="'min:3|max:15'" :error-messages="errors.collect('otherAway')" name="otherAway" data-vv-as="away team"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="9">
                <v-select dense label="Prediction(Tip)" :items="tips" item-text="abbrv" item-value="abbrv" v-model="pick.tip" v-validate="'min:1'" :error-messages="errors.collect('tip')" name="tip">></v-select>
                <v-checkbox class="mt-n3" v-model="tipNotListed" label="Click to enter manually"></v-checkbox>
            </v-col>
            <v-col cols="12" md="9" v-if="tipNotListed">
                <v-text-field dense label="Other(Not listed)" placeholder="Enter Code eg HWEH" v-model="pick.tip" v-validate="'min:3|max:15'" :error-messages="errors.collect('otherTip')" name="otherTip" data-vv-as="other tip"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-text-field label="Odd" v-model="pick.odd" placeholder="Type event odd here(Numbers only)" required v-validate="'required|decimal:3'" :error-messages="errors.collect('odd')" name="odd"></v-text-field>
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
    </div>
</template>

<script>
export default {
    props: ['forecastOdd'],
    data() {
        return {
            pick:{
                league: '',
                teamA: null,
                teamB:null,
                tip: null,
                odd: null,
                date: new Date().toISOString().substr(0, 10),
                time: null,
                country: ''
            },
            otherLeague: '',
            otherTeamA: null,
            otherTeamB: null,
            otherTip: null,
            pickedComp: null,
            compNotListed: false,
            homeNotListed: false,
            awayNotListed: false,
            tipNotListed: false,
            tips: [],
            date: new Date().toISOString().substr(0, 10),
            datePicker: false,
            timeModal: false,
            isSaving: false,
            competitions: [],
            teams: [],
            saveError: false,
            saveSuccess: false,
            oddError: false,
            dateTimeError: false
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
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
        clearPrediction(){
            this.pick = {}
            this.otherLeague = null
            this.otherTeamA = null
            this.otherTeamB = null
            this.otherTip = null
            this.compNotListed = false
            this.homeNotListed = false
            this.awayNotListed = false
            this.tipNotListed = false
        },
        getTips(){
            axios.get(this.api + `/get_all_tips`)
            .then((res) => {
                this.tips = res.data
            })
        },
        getAllCompetitions(){
            axios.get(this.api + '/get_all_intnl_competitions')
            .then((res) => {
                this.competitions = res.data
            })
        },
        getAllIntnlTeams(){
            axios.get(this.api + '/get_all_national_teams')
            .then((res) => {
                this.teams = res.data
            })
        },
        savePredictions(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(parseFloat(this.pick.odd) > 1){
                        if(this.pick.date !== null && this.pick.time !== null){
                           this.pick.odd = parseFloat(this.pick.odd)
                           this.pick.country = 'Intnl'
                            if(this.foreCastOdd == 3 && this.forecasts.length < 6 || this.foreCastOdd == 5 && this.forecasts.length < 7 || this.foreCastOdd == 10 && this.forecasts.length < 7){
                                if(this.otherTip !== null){
                                    this.pick.tip = this.otherTip
                                }
                                if(this.compNotListed){
                                    this.pick.league = this.otherLeague
                                }
                                if(this.homeNotListed){
                                    this.pick.teamA = this.otherTeamA
                                }
                                if(this.awayNotListed){
                                    this.pick.teamB = this.otherTeamB
                                }
                                console.log(this.pick)
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
                }
            })
        }
    },
    created() {
        this.getAllIntnlTeams()
        this.getAllCompetitions()
        this.getTips()
    },
}
</script>
