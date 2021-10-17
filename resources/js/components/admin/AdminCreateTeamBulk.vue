<template>
    <v-container>
        <v-row justify="space-between" class="mt-4">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <admin-top-panel title="New Teams(Bulk)" />
            </v-col>
        </v-row>
        <v-row justify="start" class="ml-n10">
            <v-col cols="12" md="5">
                <v-card elevation="12" raised min-height="200">
                    <v-card-title class="primary darken-2 subtitle-1 white--text justify-center">Add Team (Bulk)</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="Team" v-model="newTeam.team" required v-validate="'required|min:3|max:25'" :error-messages="errors.collect('team')" name="team"></v-text-field>
                        <v-text-field label="Abbreviation" v-model="newTeam.abbrv" required v-validate="'required|min:3|max:12'" :error-messages="errors.collect('abbreviation')" name="abbreviation"></v-text-field>
                        <v-select label="Select League" v-model="newTeam.league" :items="leagues" item-text="league" return-object required v-validate="'required'" :error-messages="errors.collect('league')" name="league"></v-select>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-8">
                        <v-btn text color="red darken-2" width="30%" large @click="resetField">Reset</v-btn>
                        <v-btn dark color="primary darken-2" width="50%" large @click="addTeam">Add</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="7">
                <v-card elevation="12" raised min-height="200" class="scroll">
                    <v-card-title class="primary darken-2 subtitle-1 white--text justify-center">New Teams</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="teams.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>League</th>
                                        <th>Team</th>
                                        <th>Abbrv</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(team, i) in teams" :key="i">
                                        <td>{{ team.league.league }}</td>
                                        <td>{{ team.team }}</td>
                                        <td>{{ team.abbrv }}</td>
                                        <td><v-btn small text color="primary" @click="openUpdate(team, i)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="delTeam(i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            You have not added any teams yet.
                        </template>
                    </v-card-text>
                    <v-card-actions v-if="teams.length > 0" class="justify-space-around pb-7">
                        <v-btn text color="red darken-2" width="30%" large @click="clearTeams">Clear</v-btn>
                        <v-btn dark color="primary darken-2" width="60%" large :loading="isBusy" @click="submitTeams">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Team</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Team" v-model="edit.team" required v-validate="'required|min:3|max:25'" :error-messages="errors.collect('update.team')" data-vv-scope="team" name="team"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="edit.abbrv" required v-validate="'required|min:3|max:12'" :error-messages="errors.collect('update.abbreviation')" data-vv-scope="update" name="abbreviation"></v-text-field>
                    <v-select label="Select League" v-model="edit.league" :items="leagues" item-text="league" item-value="id" required v-validate="'required'" :error-messages="errors.collect('update.league')" data-vv-scope="update" name="league"></v-select>
                </v-card-text>
                <v-card-actions class="pb-8 justify-space-around">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateTeam" width="40%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="createFail" :timeout="8000" top color="red darken-1 white--text">
            Error! Looks like you are adding a team/teams that already exist in the database. Ensure you are adding a unique team.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createSuccess" :timeout="4000" top color="green darken-1 white--text">
            Teams created successfully!
            <v-btn text color="white--text" @click="createSuccess = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            newTeam: {
                team: '',
                abbrv: '',
                league: null
            },
            teams: [],
            leagues: [],
            updateDial: false,
            edit: {
                team: '',
                abbrv: '',
                league: null,
            },
            teamToUpdt: '',
            teamToUpdtIndex: null,
            isUpdating: false,
            isBusy: false,
            createFail: false,
            createSuccess: false
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
    },
    methods: {
        addTeam(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.teams.unshift(this.newTeam)
                    let teams = JSON.parse(localStorage.getItem('newTeams'))
                    if(teams){
                        teams.unshift(this.newTeam)
                        localStorage.setItem('newTeams', JSON.stringify(teams))
                    }else{
                        localStorage.setItem('newTeams', JSON.stringify(this.teams))
                    }
                    this.resetField()
                    console.log(this.teams)
                }
            })
        },
        getLeagues(){
            axios.get(this.api + '/auth-admin/get_all_leagues', this.adminHeaders).then((res)=>{
                this.leagues = res.data
            })
        },
        resetField(){
            this.newTeam = {}
            this.$validator.reset()
        },
        delTeam(index){
            this.teams.splice(index, 1)
            localStorage.setItem('newTeams', JSON.stringify(this.teams))
        },
        openUpdate(team, ind){
            this.updateDial = true
            this.teamToUpdt =  team
            this.teamToUpdtIndex = ind
            this.edit = team
        },
        updateTeam(){
            this.isUpdating = true
            this.teams.splice(this.teamToUpdtIndex, 1)
            this.teams.unshift(this.teamToUpdt)
            this.updateDial = false
            this.teamToUpdt = {}
            this.teamToUpdtIndex = null
            localStorage.setItem('newTeams', JSON.stringify(this.teams))
            this.isUpdating = false
        },
        submitTeams(){
            this.isBusy = true
            axios.post(this.api + '/auth-admin/admin_create_bulk_teams', {
                teams: this.teams
            }, this.adminHeaders).then((res) => {
                console.log(res.data)
                this.isBusy = false
                this.createSuccess = true
                this.clearTeams()
                localStorage.removeItem('newTeams')
            }).catch(()=>{
                this.isBusy = false
                this.createFail = true
            })
        },
        clearTeams(){
            this.teams = []
            localStorage.removeItem('newTeams')
        },
        getTeamsCreated(){
            let teams = JSON.parse(localStorage.getItem('newTeams'))
            if(teams){
                this.teams = teams
            }
        }
    },
    created() {
        this.getLeagues()
        this.getTeamsCreated()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tbody tr{
        text-transform:capitalize;
    }
</style>
