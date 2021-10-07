<template>
    <v-container>
        <admin-top-panel title="Bookmakers" />
        <v-row class="mt-4" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="4" md="4" offset-md=8>
                <v-btn dark color="primary" @click="addNewDial = true"><v-icon left>add</v-icon>New Bookmaker</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Bookmakers <v-chip color="primary lighten-2" v-if="bookmakers.length > 0" class="ml-1">{{ bookmakers.length }}</v-chip></v-card-title>
                    <v-card-text>
                        <template v-if="bookmakers.length > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(bkm, i) in bookmakers" :key="bkm.id">
                                        <td>{{ bkm.id }}</td>
                                        <td>{{ bkm.name }}</td>
                                        <td><v-img v-if="bkm.logo" :src="`/images/bookmakers/${bkm.logo}`" width="60" height="40" contain transition="scale-transition" class=""></v-img></td>
                                        <td><v-btn small icon color="primary" @click="openUpdate(bkm)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(bkm, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no bookmakers data in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="addNewDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Add Bookmaker</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Bookmaker" v-model="add.name" required v-validate="'required|min:3|max:12'" :error-messages="errors.collect('add.name')" name="name" data-vv-scope="add"></v-text-field>
                </v-card-text>
                <v-card-actions class="mt-n3 justify-start">
                    <template v-if="!prevLogo">
                        <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Choose Logo</v-btn>
                        <input type="file" ref="image" style="display:none" @change.prevent="pickLogo" accept="image/*">
                    </template>
                    <template v-else class="d-flex">
                        <div class="pl-2">{{ pickedLogo }} <span class="ml-3"><v-btn text small color="red darken-2" @click="cancelUpload"><v-icon small>delete_forever</v-icon></v-btn></span></div>
                    </template>
                </v-card-actions>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="addNewDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="submit" width="50%">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Bookmaker</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Bookmaker" v-model="edit.name" required v-validate="'required|min:3|max:12'" :error-messages="errors.collect('updt.bookmaker')" name="bookmaker" data-vv-scope="updt"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updateBkm" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete this bookmaker?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                   Are you sure you want to delete this bookmaker from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isBusy" @click="delBkm" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="uploadError" :timeout="4000" top color="red darken-1 white--text">
            Error! Kindly upload a valid logo for the bookmaker.
            <v-btn text color="white--text" @click="uploadError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="addNewSuccess" :timeout="4000" top color="green darken-1 white--text">
            You have added a new bookmaker successfully .
            <v-btn text color="white--text" @click="addNewSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="addNewFailed" :timeout="7000" top color="red darken-1 white--text">
            There was an error while trying to add new bookmaker. If you are uploading a logo, kindly try another one and ensure the bookmaker doesnt already exist.
            <v-btn text color="white--text" @click="addNewFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top color="green darken-1 white--text">
            Updated successfully!
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="delSuccess" :timeout="4000" top color="green darken-1 white--text">
            Deleted successfully!
            <v-btn text color="white--text" @click="delSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="delFailed" :timeout="6000" top color="red darken-1 white--text">
            Error! There was an error while deleting bookmaker. Please try again.
            <v-btn text color="white--text" @click="delFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            bookmakers: [],
            isLoading: false,
            updateDial: false,
            bkmToUpdt: null,
            bkmToUpdtIndex: null,
            edit: {
                name: ''
            },
            isUpdating: false,
            addNewDial: false,
            add: {
                name: '',
                logo: ''
            },
            prevLogo: false,
            pickedLogo: '',
            isBusy: false,
            uploadError: false,
            addNewSuccess: false,
            addNewFailed: false,
            updateSuccess: false,
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
        getBookmakers(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_all_bookmakers', this.adminHeaders)
            .then((res)=>{
                this.isLoading = false
                this.bookmakers = res.data
            })
        },
        openUpdate(bkm, i){
            this.updateDial = true
            this.bkmToUpdt =  bkm
            this.bkmToUpdtIndex =  i
            this.edit.name = bkm.name
        },
        updateBkm(){
            this.$validator.validateAll('updt').then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_bookmaker/${this.bkmToUpdt.id}`, {
                        bkm: this.edit
                    }, this.adminHeaders)
                    .then((res)=>{
                        this.isUpdating = false
                        this.updateSuccess = true
                        this.updateDial = false
                        let bkm = this.bookmakers.find((item) => item.id === this.bkmToUpdt.id)
                        bkm.name = res.data.name
                        console.log(res.data)
                    })
                }
            })
            console.log(this.edit)
        },
        openUpload(){
            this.$refs.image.click()
        },
        pickLogo(e){
            const file = e.target.files[0]
            this.add.logo = file
            this.prevLogo = true
            this.pickedLogo = file.name
            console.log(file.name)
        },
        cancelUpload(){
            this.add.logo = ''
            this.prevLogo = false
            this.pickedLogo = ''
        },
        submit(){
            if(this.add.logo !== ''){
                this.$validator.validateAll('add').then((isValid) => {
                    if (isValid) {
                        this.isBusy = true
                        let form = new FormData();
                        form.append('logo', this.add.logo)
                        form.append('name', this.add.name)

                        axios.post(this.api + '/auth-admin/create_new_bookmaker', form, this.adminHeaders)
                        .then((res)=> {
                            this.isBusy = false
                            this.addNewDial = false
                            this.addNewSuccess = true
                            this.bookmakers.unshift(res.data)
                            this.cancelUpload()
                            this.add.name = ''
                            this.$validator.pause()
                            this.$validator.fields.items.forEach(field => field.reset())
                            this.$validator.errors.clear()
                        }).catch((err)=>{
                            this.isBusy = false
                            this.addNewFailed = true
                        })
                    }
                })
            }else{
                this.$validator.validateAll('add').then((isValid) => {
                    if (isValid) {
                        this.isBusy = true
                        axios.post(this.api + '/auth-admin/create_new_bookmaker_without_logo', {
                            bkmk: this.add.name
                        }, this.adminHeaders).then((res) =>{
                            this.isBusy = false
                            this.addNewDial = false
                            this.addNewSuccess = true
                            this.bookmakers.unshift(res.data)
                            this.add.name = ''
                            this.$validator.pause()
                            this.$validator.fields.items.forEach(field => field.reset())
                            this.$validator.errors.clear()
                        })
                    }
                }).catch(()=>{
                    this.addNewFailed = true
                })
            }
        },
        confirmDel(bkm, i){
            this.bkmTodel = bkm
            this.bkmTodelIndex = i
            this.confirmDelDial = true
        },
        delBkm(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/delete_bookmaker/${this.bkmTodel.id}`, {}, this.adminHeaders)
            .then((res) => {
                console.log(res.data)
                this.isBusy = false
                this.confirmDelDial = false
                this.delSuccess = true
                this.bookmakers.splice(this.bkmTodelIndex, 1)
            }).catch((err)=>{
                this.isBusy = false
                this.delFailed = true
            })
        }
    },
     created(){
         this.getBookmakers()
     }
}
</script>

<style lang="css" scoped>
    table tbody tr td{
        white-space: nowrap !important;
    }
    .v-card{
        overflow-x: scroll !important;
    }
</style>
