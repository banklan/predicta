<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="4" md="4" offset-md=8>
                <v-btn dark color="primary" @click="newBankDial = true"><v-icon left>add</v-icon>New Bank</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Banks <span class="ml-2"><v-chip color="primary lighten-2" v-if="banks.length > 0">{{ banks.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="banks.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Banks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(bank, i) in banks" :key="bank.id">
                                        <td>{{ bank.id }}</td>
                                        <td>{{ bank.name }}</td>
                                        <td><v-btn small text color="primary" @click="openUpdate(bank)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="confirmDel(bank, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left">
                                There are currently no banks in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Bank</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Bank" v-model="edit.bank" required v-validate="'required|min:3|max:30'" :error-messages="errors.collect('bank')" name="bank"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateBank" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center">Delete this bank?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                   Are you sure you want to delete this bank from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delBank" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="newBankDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Add New Bank</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Name" v-model="newBank" required v-validate="'required|min:3|max:30'" :error-messages="errors.collect('new.name')" data-vv-scope='new' name="name"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="newBankDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="createBank" width="50%">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="newBankAdded" :timeout="4000" top dark color="green darken-2">
            New bank has been created.
            <v-btn text color="white--text" @click="newBankAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top dark color="green darken-2">
            Country was updated successfully.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while updating. Please fill all the fields and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDeleted" :timeout="4000" top dark color="green darken-2">
            Bank has been deleted.
            <v-btn text color="white--text" @click="bankDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDeleteFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while deleting. Please try again.
            <v-btn text color="white--text" @click="bankDeleteFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            banks: [],
            isLoading: false,
            confirmDelDial: false,
            bankTodel: null,
            bankTodelIndex: null,
            isDeleting: false,
            bankDeleted: false,
            bankDeleteFailed: false,
            updateDial: false,
            bankToUpdt: null,
            edit: {
                bank: '',
            },
            isUpdating: false,
            updateSuccess: false,
            updateFailed: false,
            newBankDial: false,
            newBank: '',
            newBankAdded: false
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
        getBanks(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_all_banks', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.banks = res.data
            }).catch(() => {
                this.isLoading = false
            })
        },
        confirmDel(bank, i){
            this.bankTodel = bank
            this.bankTodelIndex = i
            this.confirmDelDial = true
        },
        delBank(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/delete_bank/${this.bankTodel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isUpdating = false
                this.confirmDelDial = false
                this.banks.splice(this.bankTodelIndex, 1)
                this.bankDeleted = true
            }).catch(()=>{
                this.isUpdating = false
                this.bankDeleteFailed = true
            })
        },
        openUpdate(bank){
            this.updateDial = true
            this.bankToUpdt =  bank
            this.edit.bank = bank.name
        },
        updateBank(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_bank/${this.bankToUpdt.id}`, {
                        bank: this.edit,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.updateDial = false
                        let bank = this.banks.find((item) => item.id === this.bankToUpdt.id)
                        bank.name = res.data.name
                        this.updateSuccess = true
                    }).catch((err)=>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        createBank(){
            this.$validator.validateAll('new').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth-admin/create_new_bank', {
                        bank: this.newBank
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.banks.unshift(res.data)
                        this.newBankAdded = true
                        this.newBank = ''
                        this.newBankDial = false
                    }).catch(()=>{
                        this.isUpdating = false
                        this.createBankFailed = true
                    })
                }
            })
        }
    },
    created(){
        this.getBanks()
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
