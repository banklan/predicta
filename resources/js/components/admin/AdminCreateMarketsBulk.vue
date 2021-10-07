<template>
    <v-container>
        <v-row justify="space-between" class="mt-4">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="10">
                <admin-top-panel title="New Markets(Bulk)" />
            </v-col>
        </v-row>
        <v-row justify="start" class="" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="5">
                <v-card elevation="12" raised min-height="200">
                    <v-card-title class="primary darken-2 subtitle-1 white--text justify-center">Add Market (Bulk)</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="Market" v-model="market.tip" required v-validate="'required|min:3|max:30'" :error-messages="errors.collect('tip')" name="tip"></v-text-field>
                        <v-text-field label="Abbreviation" v-model="market.abbrv" required v-validate="'required|min:2|max:15'" :error-messages="errors.collect('abbreviation')" name="abbreviation"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-8">
                        <v-btn text color="red darken-2" width="20%" large @click="resetField">Reset</v-btn>
                        <v-btn dark color="primary darken-2" width="60%" large @click="addMarket">Add</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="7">
                <v-card elevation="12" raised min-height="200" class="scroll">
                    <v-card-title class="primary darken-2 subtitle-1 white--text justify-center">New Markets</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="markets.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Market</th>
                                        <th>Abbreviation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(mkt, index) in markets" :key="index">
                                        <td>{{ mkt.tip }}</td>
                                        <td>{{ mkt.abbrv }}</td>
                                        <td><v-btn small icon color="primary" @click="openEditDial(mkt, index)"><v-icon>edit</v-icon></v-btn>&nbsp; <v-btn small icon color="red darken-2" @click="delMarket(index)"><v-icon small>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            You have not added any markets yet.
                        </template>
                    </v-card-text>
                    <v-card-actions v-if="markets.length > 0" class="justify-center pb-7">
                        <v-btn text color="red darken-2" width="40%" large @click="clearMarkets">Clear</v-btn>
                        <v-btn dark color="primary darken-2" width="40%" large :loading="isBusy" @click="submitMarkets">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="editDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 primary white--text justify-center">Update Market</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Market" v-model="marketToEdit.tip" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('updt.tip')" name="tip" data-vv-scope="updt"></v-text-field>
                    <v-text-field label="Abbreviation" v-model="marketToEdit.abbrv" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('updt.abbreviation')" name="abbreviation" data-vv-scope="updt"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="editDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateMarket" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="createFail" :timeout="8000" top color="red darken-1 white--text">
            Error! Looks like you are adding a tip market that already exist in the database. Ensure you are adding a unique tip market and abbreviation.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createSuccess" :timeout="4000" top color="green darken-1 white--text">
            Tip markets created successfully!
            <v-btn text color="white--text" @click="createSuccess = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            market: {
                tip: '',
                abbrv: ''
            },
            markets: [],
            marketToEdit: {
                tip: '',
                abbrv: ''
            },
            marketToEditIndex: null,
            editDial: false,
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
        addMarket(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.markets.unshift(this.market)
                    let markets = JSON.parse(localStorage.getItem('newMarkets'))
                    if(markets){
                        markets.unshift(this.market)
                        localStorage.setItem('newMarkets', JSON.stringify(markets))
                    }else{
                        localStorage.setItem('newMarkets', JSON.stringify(this.markets))
                    }
                    this.market = {}
                    this.resetField()
                }
            })
        },
        resetField(){
            this.market = {}
            this.$validator.reset()
        },
        delMarket(index){
            this.markets.splice(index, 1)
            localStorage.setItem('newMarkets', JSON.stringify(this.markets))
        },
        openEditDial(mkt, ind){
            this.editDial = true
            this.marketToEdit.tip = mkt.tip
            this.marketToEdit.abbrv = mkt.abbrv
            this.marketToEditIndex = ind
        },
        updateMarket(){
            this.isUpdating = true
            this.markets.splice(this.marketToEditIndex, 1)
            this.markets.unshift(this.marketToEdit)
            this.editDial = false
            this.marketToEdit = {}
            this.marketToEditIndex = null
            localStorage.setItem('newMarkets', JSON.stringify(this.markets))
            this.isUpdating = false
        },
        submitMarkets(){
            this.isBusy = true
            axios.post(this.api + '/auth-admin/admin_create_bulk_markets', {
                markets: this.markets
            }, this.adminHeaders).then((res) => {
                // console.log(res.data)
                this.isBusy = false
                this.createSuccess = true
                this.clearMarkets()
                localStorage.removeItem('newMarkets')
            }).catch(()=>{
                this.isBusy = false
                this.createFail = true
            })
        },
        clearMarkets(){
            this.markets = []
            localStorage.removeItem('newMarkets')
        },
        getMarketsCreated(){
            let markets = JSON.parse(localStorage.getItem('newMarkets'))
            if(markets){
                this.markets = markets
            }
        }
    },
    created() {
        this.getMarketsCreated()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr{
        text-transform:capitalize;
    }
    table tbody tr td{
        white-space: nowrap !important;
    }
</style>
