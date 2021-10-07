<template>
    <v-container>
        <admin-top-panel title="Countries" />
        <v-row class="mt-4">
            <v-col cols="4" md="4" offset-md="8">
                <v-btn dark color="primary" :to="{name: 'AdminCreateCountry'}"><v-icon left>add</v-icon>New Country</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Countries <v-chip color="primary lighten-2" class="ml-1" v-if="countries.length > 0">{{ total }}</v-chip></v-card-title>
                    <v-card-text>
                        <template v-if="total > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Country</th>
                                        <th>Abbrev</th>
                                        <th>Flag</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(country, i) in countries" :key="country.id">
                                        <td>{{ country.id }}</td>
                                        <td>{{ country.country }}</td>
                                        <td>{{ country.abbrv }}</td>
                                        <td><v-img v-if="country.flag" :src="`/images/countries/${country.flag}`" width="15" transition="scale-transition" class="mt-2"></v-img></td>
                                        <td><v-btn small icon color="primary" @click="openUpdate(country)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(country, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no countries data in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getCountries(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getCountries(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getCountries(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getCountries(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
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
                <v-card-title class="sub_title primary white--text justify-center">Update Country</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Country" v-model="edit.country" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('country')" name="country"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="edit.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('abbreviation')" name="abbreviation"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateCountry" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center">Delete this country?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                   Are you sure you want to delete this country from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delCountry" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="newCountryAdded" :timeout="4000" top dark color="green darken-2">
            New country has been created.
            <v-btn text color="white--text" @click="newCountryAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top dark color="green darken-2">
            Country was updated successfully.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while updating. Please fill all the fields and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="countryDeleted" :timeout="4000" top dark color="green darken-2">
            Country has been deleted.
            <v-btn text color="white--text" @click="countryDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="countryDeleteFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while deleting. Please try again.
            <v-btn text color="white--text" @click="countryDeleteFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            countries: [],
            pagination: {},
            total: null,
            isLoading: false,
            confirmDelDial: false,
            cntryTodel: null,
            cntryTodelIndex: null,
            isDeleting: false,
            countryDeleted: false,
            countryDeleteFailed: false,
            updateDial: false,
            countryToUpdt: null,
            edit: {
                country: '',
                abbrv: ''
            },
            isUpdating: false,
            updateSuccess: false,
            updateFailed: false
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
        newCountryAdded(){
            return this.$store.getters.newCountryAdded
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getCountries(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_countries`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.countries = res.data.data
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
        confirmDel(country, i){
            this.cntryTodel = country
            this.cntryTodelIndex = i
            this.confirmDelDial = true
        },
        delCountry(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/delete_country/${this.cntryTodel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isUpdating = false
                this.confirmDelDial = false
                this.countries.splice(this.cntryTodelIndex, 1)
                this.countryDeleted = true
            }).catch(()=>{
                this.isUpdating = false
                this.countryDeleteFailed = true
            })
        },
        openUpdate(country){
            this.updateDial = true
            this.countryToUpdt =  country
            this.edit.country = country.country
            this.edit.abbrv = country.abbrv
        },
        updateCountry(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_country/${this.countryToUpdt.id}`, {
                        country: this.edit,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.updateDial = false
                        let country = this.countries.find((item) => item.id === this.countryToUpdt.id)
                        country.country = res.data.country
                        country.abbrv = res.data.abbrv
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
        this.getCountries()
    }
}
</script>

<style lang="css" scoped>
    table .table_list tr td{
        cursor: pointer;
        white-space: nowrap;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
