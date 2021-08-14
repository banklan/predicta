<template>
    <div>
        <v-row justify="space-around" v-if="!leagueNotListed" class="py-1">
            <v-col cols="12" class="text-center">
                <v-select label="Select Country" v-model="pickedCountry" :items="countries" item-text="country" return-object @change="getLeagues" required v-validate="'required'" :error-messages="errors.collect('country')" name="country"></v-select>
                <!-- <v-btn text color="primary darken-2" @click="countryNotListed = true">Country Not Listed</v-btn> -->
            </v-col>
            <v-col cols="12">
                <v-select label="Select League" v-model="pickedLeague" :items="leagues" item-text="league" return-object @change="getTeams" required v-validate="'required'" :error-messages="errors.collect('league')" name="league"></v-select>
                <!-- <v-btn text color="primary darken-2" @click="competitionNotListed = true">Competition Not Listed</v-btn> -->
            </v-col>
            <v-col cols="12">
                <v-select label="Home Team" v-model="select.home" :items="teams" item-text="team" item-value="abbrv" required v-validate="'required'" :error-messages="errors.collect('home')" name="home" data-vv-as="home team"></v-select>
            </v-col>
            <v-col cols="12">
                <v-select label="Away Team" v-model="select.away" :items="teams" item-text="team" item-value="abbrv" required v-validate="'required'" :error-messages="errors.collect('away')" name="away" data-vv-as="away team"></v-select>
            </v-col>
            <v-col cols="12" class="text-center">
                <v-btn text color="primary darken-2" class="mt-n5" @click="leagueNotListed = true">League Not Listed</v-btn>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12">
                <v-text-field label="Country" v-model="select.country" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('country')" name="country"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Competition/League" v-model="select.league" required v-validate="'required|min:3|max:10'" :error-messages="errors.collect('league')" name="league"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Home Teams" v-model="select.home" v-validate="'required|min:3|max:10'" :error-messages="errors.collect('home')" name="home" data-vv-as="home team"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Away Teams" v-model="select.away" v-validate="'required|min:3|max:10'" :error-messages="errors.collect('away')" name="away" data-vv-as="away team"></v-text-field>
            </v-col>
        </v-row>
        <v-row class="mt-n5" v-if="!tipNotListed">
            <v-col cols="12" class="text-center">
                <v-select label="Tip" v-model="select.tip" :items="tips" item-text="tip" item-value="abbrv" v-validate="'required'" :error-messages="errors.collect('tip')" name="tip"></v-select>
                <v-btn text color="primary darken-2" @click="tipNotListed = true">Market Not Listed</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-n5" v-else>
            <v-col cols="12">
                <v-text-field label="Tip" placeholder="For markets not listed, enter short code eg HWEH" v-model="select.tip" v-validate="'required|min:3|max:8'" :error-messages="errors.collect('tip')" name="tip"></v-text-field>
            </v-col>
        </v-row>
        <v-row class="mt-n5">
            <v-col cols="12">
                <v-text-field label="Event Odd" v-model="select.odd" v-validate="'required|decimal:3'" :error-messages="errors.collect('odd')" name="odd"></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-menu ref="datePicker" v-model="datePicker" :close-on-content-click="false" :return-value.sync="select.date" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="select.date" label="Event Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="select.date" color="primary" elevation="4" scrollable :allowed-dates="disablePastDates" >
                        <div class="flex-grow-1"></div>
                        <v-btn text color="red darken-2" @click="datePicker = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.datePicker.save(select.date)">Ok</v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12">
                <v-dialog ref="timeDial" v-model="timeModal" :return-value.sync="select.time" persistent width="290px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="select.time" label="Event Time" prepend-icon="mdi-clock-time-four-outline" only v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-time-picker v-if="timeModal" v-model="select.time" full-width>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="timeModal = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.timeDial.save(select.time)">OK </v-btn>
                    </v-time-picker>
                </v-dialog>
            </v-col>
        </v-row>
        <v-card-actions class="justify-space-around my-4">
            <v-btn large text color="red darken-2" width="50%" @click="clearSelects">Cancel</v-btn>
            <v-btn large color="primary darken-2" width="50%" @click="submit" :loading="isBusy">Submit</v-btn>
        </v-card-actions>
        <v-snackbar v-model="addedToTips" :timeout="4000" top color="green darken-1 white--text">
            Event added to the forecast.
            <v-btn text color="white--text" @click="createFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="sameTeamError" :timeout="6000" top color="red darken-1 white--text">
            Error! You are not allowed to pitch the same team as home and away teams.
            <v-btn text color="white--text" @click="sameTeamError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="eventDateTimeError" :timeout="6000" top color="red darken-1 white--text">
            Error! Date and time fields cannot be empty.
            <v-btn text color="white--text" @click="eventDateTimeError = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            leagues: [],
            countries: [],
            pickedLeague: null,
            pickedCountry: null,
            select: {
                country: null,
                league: null,
                home: '',
                away: '',
                tip: '',
                odd: '',
                date: new Date().toISOString().substr(0, 10),
                time: null,
            },
            teams: [],
            tips: [],
            leagueNotListed: false,
            tipNotListed: false,
            date: new Date().toISOString().substr(0, 10),
            datePicker: false,
            timeModal: false,
            isBusy: false,
            addedToTips: false,
            sameTeamError: false,
            eventDateTimeError: false
        }
    },
    computed:{
         authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        dailyTips(){
            return this.$store.getters.getDailyTips
        }
    },
    methods: {
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
        getCountries(){
            axios.get(this.api + `/auth-admin/get_all_countries`, this.adminHeaders)
            .then((res)=>{
                this.countries = res.data
            })
        },
        getLeagues(){
            axios.get(this.api + `/auth-admin/get_leagues_for_a_country/${this.pickedCountry.id}`, this.adminHeaders)
            .then((res)=>{
                this.leagues = res.data
            })
        },
        getTeams(){
            axios.get(this.api + `/auth-admin/get_teams_for_a_league/${this.pickedLeague.id}`, this.adminHeaders)
            .then((res)=>{
                console.log(res.data)
                this.teams = res.data
            })
        },
        getTips(){
            axios.get(this.api + '/auth-admin/get_all_markets', this.adminHeaders)
            .then((res)=>{
                this.tips = res.data
            })
        },
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(this.select.home !== this.select.away){
                        if(this.select.date !== null && this.select.time !== null){
                            if(!this.leagueNotListed){
                                this.select.country = this.pickedCountry.abbrv
                                this.select.league = this.pickedLeague.abbrv
                            }
                            this.select.odd = parseFloat(this.select.odd)
                            this.$store.commit('addDailyTips', this.select)
                            this.addedToTips = true
                            this.isBusy = false
                            this.clearSelects()
                        }else{
                            this.eventDateTimeError = true
                        }
                    }else{
                        this.sameTeamError = true
                    }
                }
            })
        },
        clearSelects(){
            this.select = {}
            this.$validator.pause()
            this.$validator.fields.items.forEach(field => field.reset())
            this.$validator.errors.clear()
        }
    },
    created(){
        this.getCountries()
        this.getTips()
    }
}
</script>
