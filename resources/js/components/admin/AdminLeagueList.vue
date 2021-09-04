<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="4" md="4" offset-md=8>
                <v-btn dark color="primary" @click="newLgDial = true"><v-icon left>add</v-icon>New League</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="sub_title primary white--text justify-center">Leagues <span class="ml-2"><v-chip color="primary lighten-2" v-if="total > 0">{{ total }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="total > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Country</th>
                                        <th>League</th>
                                        <th>Abbrv</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(lg, i) in leagues" :key="lg.id">
                                        <td>{{ lg.id }}</td>
                                        <td v-if="lg.country">{{ lg.country.country }}</td>
                                        <td>{{ lg.league }}</td>
                                        <td>{{ lg.abbrv }}</td>
                                        <td><v-btn small text color="primary" @click="openUpdate(lg)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="confirmDel(lg, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-8">
                                There are currently no leagues in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="total > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getLeagues(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getLeagues(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getLeagues(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getLeagues(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
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
                <v-card-title class="sub_title primary white--text justify-center">Update League</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="League" v-model="edit.league" required v-validate="'required|min:3|max:15'" :error-messages="errors.collect('update.league')" data-vv-scope="update" name="league"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="edit.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('update.abbreviation')" data-vv-scope="update" name="abbreviation"></v-text-field>
                    <v-select label="Select Country" v-model="edit.country" :items="countries" item-text="country" item-value="id" required v-validate="'required'" :error-messages="errors.collect('update.country')" data-vv-scope="update" name="country"></v-select>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateLeague" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center">Delete this league?</v-card-title>
                <v-card-text class="text-center mt-5">
                   Are you sure you want to delete this league from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="delLeague" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="newLgDial" max-width="480">
            <v-card min-height="250">
                <v-card-title class="sub_title primary white--text justify-center">Create New League</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="League" v-model="newLg.league" required v-validate="'required|min:3|max:15'" :error-messages="errors.collect('new.league')" data-vv-scope="new" name="league"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="newLg.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('new.abbreviation')" data-vv-scope="new" name="abbreviation"></v-text-field>
                    <v-select label="Select Country" v-model="newLg.country" :items="countries" item-text="country" item-value="id" required v-validate="'required'" :error-messages="errors.collect('new.country')" data-vv-scope="new" name="country"></v-select>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="newLgDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="createLeague" width="50%">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="newLgAdded" :timeout="4000" top dark color="green darken-2">
            New league has been created.
            <v-btn text color="white--text" @click="newLgAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createLgFailed" :timeout="6000" top dark color="red darken-2">
            There was an error while trying to create a new league. Please ensure you fill all the fields before trying again.
            <v-btn text color="white--text" @click="createLgFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top dark color="green darken-2">
            Country was updated successfully.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while updating. Please fill all the fields and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="lgDeleted" :timeout="4000" top dark color="green darken-2">
            League has been deleted.
            <v-btn text color="white--text" @click="lgDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="lgDelFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while deleting. Please try again.
            <v-btn text color="white--text" @click="lgDelFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            leagues: [],
            pagination: {},
            total: null,
            countries: [],
            isLoading: false,
            confirmDelDial: false,
            lgTodel: null,
            lgTodelIndex: null,
            isDeleting: false,
            lgDeleted: false,
            lgDelFailed: false,
            updateDial: false,
            lgToUpdt: null,
            edit: {
                league: '',
                abbrv: '',
                country: null,
            },
            isUpdating: false,
            updateSuccess: false,
            updateFailed: false,
            newLgDial: false,
            newLg: {
                league: '',
                abbrv: '',
                country: null,
            },
            newLgAdded: false,
            createLgFailed: false
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
        getLeagues(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_leagues`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                console.log(res.data)
                this.leagues = res.data.data
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
        getCountries(){
            axios.get(this.api + '/auth-admin/get_all_countries', this.adminHeaders).then((res)=>{
                this.countries = res.data
            })
        },
        confirmDel(lg, i){
            this.lgTodel = lg
            this.lgTodelIndex = i
            this.confirmDelDial = true
        },
        delLeague(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_league/${this.lgTodel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isDeleting = false
                this.confirmDelDial = false
                this.leagues.splice(this.lgTodelIndex, 1)
                this.lgDeleted = true
            }).catch(()=>{
                this.isDeleting = false
                this.lgDelFailed = true
            })
        },
        openUpdate(lg){
            this.updateDial = true
            this.lgToUpdt =  lg
            this.edit.league = lg.league
            this.edit.abbrv = lg.abbrv
            this.edit.country = lg.country.id
        },
        updateLeague(){
            this.$validator.validateAll('update').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_league/${this.lgToUpdt.id}`, {
                        league: this.edit,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.updateDial = false
                        let rez = res.data
                        let lg = this.leagues.find((item) => item.id === this.lgToUpdt.id)
                        lg.country.country = rez.country.country
                        lg.league = rez.league
                        lg.abbrv = rez.abbrv
                        this.updateSuccess = true
                    }).catch((err)=>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        createLeague(){
            this.$validator.validateAll('new').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth-admin/create_new_league', {
                        league: this.newLg
                    }, this.adminHeaders).then((res)=>{
                        console.log(res.data)
                        this.isUpdating = false
                        this.leagues.unshift(res.data)
                        this.newLgAdded = true
                        this.newLgDial = false
                    }).catch(()=>{
                        this.isUpdating = false
                        this.createLgFailed = true
                    })
                }
            })
        }
    },
    created(){
        this.getLeagues()
        this.getCountries()
    }
}
</script>

<style lang="css" scoped>
    table .table_list tr td{
        cursor: pointer;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
