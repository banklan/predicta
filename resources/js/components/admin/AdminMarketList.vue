<template>
    <v-container>
        <admin-top-panel title="Markets" />
        <v-row class="mt-4" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="6" md="3" offset-md="6">
                <v-btn dark color="primary" @click="addNewDial = true"><v-icon left>add</v-icon>New</v-btn>
            </v-col>
            <v-col cols="6" md="3">
                <v-btn dark color="primary" :to="{name: 'AdminCreateMarketsBulk'}"><v-icon left>add</v-icon>New (Bulk)</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll" width="100%">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Markets <v-chip color="primary lighten-2" v-if="total > 0" class="ml-1">{{ total }}</v-chip></v-card-title>
                    <v-card-text>
                        <template v-if="total > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Market</th>
                                        <th>Abbreviation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(mkt, i) in markets" :key="mkt.id">
                                        <td>{{ mkt.id }}</td>
                                        <td>{{ mkt.tip }}</td>
                                        <td>{{ mkt.abbrv }}</td>
                                        <td><v-btn small icon color="primary" @click="openUpdate(mkt)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(mkt, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no markets data in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="total > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getMarkets(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMarkets(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMarkets(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getMarkets(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="addNewDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Add Market</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Market" v-model="add.tip" required v-validate="'required|min:1|max:30'" :error-messages="errors.collect('add.tip')" name="tip" data-vv-scope="add"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="add.abbrv" required v-validate="'required|min:1|max:15'" :error-messages="errors.collect('add.abbrv')" name="abbrv" data-vv-scope="add"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="addNewDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="submit" width="50%">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Market</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Market" v-model="edit.tip" required v-validate="'required|min:1|max:30'" :error-messages="errors.collect('updt.tip')" name="tip" data-vv-scope="updt"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="edit.abbrv" required v-validate="'required|min:1|max:15'" :error-messages="errors.collect('updt.abbrv')" name="abbrv" data-vv-scope="updt"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateMkt" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete this market?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                   Are you sure you want to delete this market from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="delMkt" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="addNewSuccess" :timeout="4000" top color="green darken-1 white--text">
            You have added a new market successfully .
            <v-btn text color="white--text" @click="addNewSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="addNewFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to add new market. Kindly fill the fields validly and ensure the market doesn't already exist.
            <v-btn text color="white--text" @click="addNewFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top color="green darken-1 white--text">
            Updated successfully!
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while updating! Please ensure you fill the fields validly and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="delSuccess" :timeout="4000" top color="green darken-1 white--text">
            Deleted successfully!
            <v-btn text color="white--text" @click="delSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="delFailed" :timeout="6000" top color="red darken-1 white--text">
            Error! There was an error while deleting market. Please try again.
            <v-btn text color="white--text" @click="delFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            markets: [],
            pagination: {},
            total: null,
            isLoading: false,
            updateDial: false,
            mktToUpdt: null,
            mktToUpdtIndex: null,
            edit: {
                tip: '',
                abbrv: ''
            },
            isUpdating: false,
            addNewDial: false,
            add: {
                tip: '',
                abbrv: ''
            },
            isBusy: false,
            addNewSuccess: false,
            addNewFailed: false,
            updateSuccess: false,
            updateFailed: false,
            confirmDelDial: false,
            delSuccess: false,
            delFailed: false
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
        getMarkets(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_markets`
            axios.get(pag, this.adminHeaders)
            .then((res)=>{
                this.isLoading = false
                this.markets = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        openUpdate(mkt, i){
            this.updateDial = true
            this.mktToUpdt =  mkt
            this.mktToUpdtIndex =  i
            this.edit.tip = mkt.tip
            this.edit.abbrv = mkt.abbrv
        },
        updateMkt(){
            this.$validator.validateAll('updt').then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_market/${this.mktToUpdt.id}`, {
                        mkt: this.edit
                    }, this.adminHeaders)
                    .then((res)=>{
                        this.isUpdating = false
                        this.updateSuccess = true
                        this.updateDial = false
                        let mkt = this.markets.find((item) => item.id === this.mktToUpdt.id)
                        mkt.tip = res.data.tip
                        mkt.abbrv = res.data.abbrv
                    }).catch((err)=>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        submit(){
            this.$validator.validateAll('add').then((isValid) => {
                if (isValid) {
                    this.isBusy = true

                    axios.post(this.api + '/auth-admin/create_new_market', {
                        mkt: this.add
                    }, this.adminHeaders)
                    .then((res)=> {
                        this.isBusy = false
                        this.addNewDial = false
                        this.addNewSuccess = true
                        this.markets.unshift(res.data)
                        this.add.tip = ''
                        this.add.abbrv = ''
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                        this.total++
                    }).catch((err)=>{
                        this.isBusy = false
                        this.addNewFailed = true
                    })
                }
            })

        },
        confirmDel(mkt, i){
            this.mktTodel = mkt
            this.mktTodelIndex = i
            this.confirmDelDial = true
        },
        delMkt(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/delete_market/${this.mktTodel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isBusy = false
                this.confirmDelDial = false
                this.delSuccess = true
                this.markets.splice(this.mktTodelIndex, 1)
                this.total--
            }).catch((err)=>{
                this.isBusy = false
                this.delFailed = true
            })
        }
    },
     created(){
         this.getMarkets()
     }
}
</script>

<style lang="css" scoped>
    table tbody tr td{
        white-space:nowrap;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
