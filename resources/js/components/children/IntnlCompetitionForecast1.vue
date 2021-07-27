<template>
    <div>
        <v-row>
            <v-col cols="12" v-if="!compListed">
                <v-select label="Competitions" :items="competitions" item-text="competition" return-object v-model="pickedComp" required v-validate="'required'" :error-messages="errors.collect('competition')" name="competition"></v-select>
                <v-btn text small color="primary darken-4" @click="compListed = true">Not Listed</v-btn>
                <!-- <v-checkbox v-model="compListed" label="Click to enter manually if Competition Not Listed"></v-checkbox> -->
            </v-col>
            <v-col cols="12" v-if="compListed">
                 <v-text-field label="Type Competition Name" placeholder="e.g Women U-17 WC" v-model="pick.league" required></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-select label="Home Team" :items="competitions" item-text="competition" return-object v-model="pickedComp" required v-validate="'required'" :error-messages="errors.collect('competition')" name="competition"></v-select>
                <v-text-field label="Home Team" placeholder="Home Team" v-model="pick.teamA" required></v-text-field>

            </v-col>
            <v-col cols="12" md="6">
                <v-text-field label="Away Team" placeholder="Away Team" v-model="pick.teamB" required></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-select dense label="Prediction(Tip)" :items="tips" item-text="abbrv" item-value="abbrv" v-model="pick.tip"></v-select>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field dense label="Other(Not listed)" placeholder="Enter Code eg HWEH" v-model="pick.otherTip"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-text-field label="Odd" v-model="pick.odd" placeholder="Type event odd here"></v-text-field>
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
            <v-btn color="primary darken-2" large width="50%" :loading="isSaving">Save</v-btn>
        </v-card-actions>
    </div>
</template>

<script>
export default {
    props: ['forecastOdd'],
    data() {
        return {
            pick:{
                teamA: null,
                teamB:null,
                tip: null,
                otherTip: null,
                odd: null,
                date: new Date().toISOString().substr(0, 10),
                time: null
            },
            pickedComp: null,
            compListed: false,
            tips: [],
            date: new Date().toISOString().substr(0, 10),
            datePicker: false,
            timeModal: false,
            isSaving: false,
            competitions: [],
            teams: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
        clearPrediction(){

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
        }
    },
    created() {
        this.getAllIntnlTeams()
        this.getAllCompetitions()
    },
}
</script>
