<template>
    <v-container>
        <admin-top-panel title="Teams" />
        <v-row class="mb-n3 mt-1" justify="space-around" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="6" md="2" offset-md="6">
                <v-btn dark color="primary" @click="newTeamDial = true" class="align-self-end"><v-icon left>add</v-icon>New</v-btn>
            </v-col>
            <v-col cols="6" md="3">
                <v-btn dark color="primary" :to="{name: 'AdminCreateTeamBulk'}" class="align-self-end"><v-icon left>add</v-icon>New (Bulk)</v-btn>
            </v-col>
        </v-row>
        <v-row :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="4">
                <admin-team-filter :leagues="leagues" />
            </v-col>
            <v-col cols="12" md="5" :class="$vuetify.breakpoint.smAndDown ? 'mt-n7':''">
                <admin-search model="Team" searchFor="teams" />
            </v-col>
        </v-row>
        <v-row class="ml-n10 mt-n5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Teams <span class="ml-1"><v-chip color="primary lighten-2" v-if="total > 0">{{ total }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="total > 0">
                            <table class="table table-striped table-hover">
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
                                        <td><v-btn small icon color="primary" @click="openUpdate(team)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(team, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no teams in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="total > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getTeams(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTeams(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTeams(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTeams(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
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
                <v-card-actions class="pb-8 justify-space-around">
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
        <v-dialog v-model="newTeamDial" max-width="480">
            <v-card min-height="250">
                <v-card-title class="sub_title primary white--text justify-center">Create New Team</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Team" v-model="newTeam.team" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('new.team')" data-vv-scope="new" name="team"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="newTeam.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('new.abbreviation')" data-vv-scope="new" name="abbreviation"></v-text-field>
                    <v-select label="Select League" v-model="newTeam.league" :items="leagues" item-text="league" item-value="id" required v-validate="'required'" :error-messages="errors.collect('new.league')" data-vv-scope="new" name="league"></v-select>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="newTeamDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="createTeam" width="50%">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="newTeamAdded" :timeout="4000" top dark color="green darken-2">
            A new team has been created.
            <v-btn text color="white--text" @click="newTeamAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createTeamFailed" :timeout="6000" top dark color="red darken-2">
            There was an error while trying to create a new team. Please ensure you fill all the fields and that the team doesn't already exist before trying again.
            <v-btn text color="white--text" @click="createTeamFailed = false">Close</v-btn>
        </v-snackbar>
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
            leagues: [],
            teams: [],
            pagination: {},
            total: null,
            isLoading: false,
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
            newTeamDial: false,
            newTeam: {
                team: '',
                abbrv: '',
                league: null,
            },
            newTeamAdded: false,
            createTeamFailed: false
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getTeams(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_teams`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                // console.log(res.data)
                this.teams = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            }).catch(() => {
                this.isLoading = false
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
        createTeam(){
            this.$validator.validateAll('new').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth-admin/create_new_team', {
                        team: this.newTeam
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.newTeam.team = ''
                        this.newTeam.abbrv = ''
                        this.$validator.pause()
                        this.$validator.errors.clear()
                        this.teams.unshift(res.data)
                        this.newTeamAdded = true
                        this.newTeamDial = false
                        this.total++
                    }).catch(()=>{
                        this.isUpdating = false
                        this.createTeamFailed = true
                    })
                }
            })
        }
    },
    created(){
        this.getTeams()
        this.getLeagues()
    }
}
</script>

<style lang="css" scoped>
    table .table_list tr td{
        cursor: pointer;
        white-space: nowrap !important;
    }
    .v-card.scroll .v-card__text{
        overflow: scroll !important;
    }
</style>
