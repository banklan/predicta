<template>
    <v-container>
        <v-row class="mb-n3 mt-1" justify="space-around">
            <v-col cols="3">
                <v-btn dark small rounded color="primary darken-2" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9"></v-col>
        </v-row>
        <v-row class="ml-n10 mt-5">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="teams.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"><span v-if="league">{{ league.league }}&nbsp;</span> Teams <span class="ml-2"><v-chip color="primary lighten-2" v-if="teams.length > 0">{{ teams.length }}</v-chip></span></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>League</th>
                                        <th>Team</th>
                                        <th>Abbrv</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(team, i) in teams" :key="team.id">
                                        <td>{{ team.id }}</td>
                                        <td v-if="team.league">{{ team.league.league }}</td>
                                        <td>{{ team.team }}</td>
                                        <td>{{ team.abbrv }}</td>
                                        <td><v-btn small text color="primary" @click="openUpdate(team)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="confirmDel(team, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no teams for <span v-if="league">{{ league.league }}</span> in the database.
                            </v-alert>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Team</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Team" v-model="edit.team" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('update.team')" data-vv-scope="team" name="team"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="edit.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('update.abbreviation')" data-vv-scope="update" name="abbreviation"></v-text-field>
                    <v-select label="Select League" v-model="edit.league" :items="leagues" item-text="league" item-value="id" required v-validate="'required'" :error-messages="errors.collect('update.league')" data-vv-scope="update" name="league"></v-select>
                </v-card-text>
                <v-card-actions class="pb-8 space-around">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateTeam" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center">Delete this team?</v-card-title>
                <v-card-text class="text-center mt-5">
                   Are you sure you want to delete this team from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="delTeam" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top dark color="green darken-2">
            Team was updated successfully.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while updating. Please fill all the fields and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="teamDeleted" :timeout="4000" top dark color="green darken-2">
            The team has been deleted.
            <v-btn text color="white--text" @click="teamDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="teamDelFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while deleting. Please try again.
            <v-btn text color="white--text" @click="teamDelFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            q: this.$route.query.league,
            teams: [],
            league: null,
            leagues: [],
            confirmDelDial: false,
            teamTodel: null,
            teamTodelIndex: null,
            isDeleting: false,
            teamDeleted: false,
            teamDelFailed: false,
            updateDial: false,
            teamToUpdt: null,
            edit: {
                team: '',
                abbrv: '',
                league: null,
            },
            isUpdating: false,
            updateSuccess: false,
            updateFailed: false,
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
        getTeams(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_filter_teams_by_league/${this.$route.query.league}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.teams = res.data
            })
        },
        getLeague(){
            axios.get(this.api + `/auth-admin/admin_get_league/${this.$route.query.league}`, this.adminHeaders).then((res)=>{
                this.league = res.data
            })
        },
        getLeagues(){
            axios.get(this.api + '/auth-admin/get_all_leagues', this.adminHeaders).then((res)=>{
                this.leagues = res.data
            })
        },
        confirmDel(team, i){
            this.teamTodel = team
            this.teamTodelIndex = i
            this.confirmDelDial = true
        },
        delTeam(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_team/${this.teamTodel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isDeleting = false
                this.confirmDelDial = false
                this.teams.splice(this.teamTodelIndex, 1)
                this.teamDeleted = true
            }).catch(()=>{
                this.isDeleting = false
                this.teamDelFailed = true
            })
        },
        openUpdate(team){
            this.getLeagues()
            this.updateDial = true
            this.teamToUpdt =  team
            this.edit.team = team.team
            this.edit.abbrv = team.abbrv
            this.edit.league = team.league.id
        },
        updateTeam(){
            this.$validator.validateAll('update').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_team/${this.teamToUpdt.id}`, {
                        team: this.edit,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.updateDial = false
                        let rez = res.data
                        let team = this.teams.find((item) => item.id === this.teamToUpdt.id)
                        team.league.league = rez.league.league
                        team.team = rez.team
                        team.abbrv = rez.abbrv
                        this.updateSuccess = true
                    }).catch((err)=>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
    },
    created(){
        this.getTeams()
        this.getLeague()
    }
}
</script>
