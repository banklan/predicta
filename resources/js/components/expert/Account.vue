<template>
    <v-container>
        <expert-top-panel title="My Account"/>
        <v-row justify="space-around" class="mt-5">
            <v-col cols="12" md="4">
                <v-card raised elevation="10" min-height="300">
                    <v-card-title class="justify-center sub_title primary white--text">Profile</v-card-title>
                        <template v-if="!changePic">
                            <v-img :src="`/images/profiles/experts/${ authExpert.picture ? authExpert.picture : 'avatar.jpg'}`" width="100%" height="300" transition="scale-transition"></v-img>
                            <v-card-actions class="justify-center">
                                <v-btn text color="blue darken-3" class="mt-2 mb-n4" @click="changePic = true">Change Picture</v-btn>
                            </v-card-actions>
                        </template>
                        <template v-else>
                            <v-card-actions class="justify-center mt-5" v-if="!prvImg">
                                <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Choose Picture</v-btn>
                                <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                            </v-card-actions>
                            <div v-else class="mt-3">
                                <v-img :src="prvImgUrl" height="150" contain alt="profile photo" aspect-ratio="1"></v-img>
                                <v-card-actions class="justify-center mt-5">
                                    <v-btn dark color="primary" @click="uploadImg" :loading="isUpdating"><v-icon left>cloud</v-icon>Upload</v-btn>
                                    <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                                </v-card-actions>
                            </div>
                        </template>
                    <template v-if="!openEdit">
                        <v-card-text>
                            <v-divider></v-divider>
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width='30%' style="border-top: none">Name:</th>
                                        <td style="border-top: none">{{ authExpert.fullname }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Email:</th>
                                        <td>{{ authExpert.email }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Expert ID:</th>
                                        <td>{{ authExpert.expert_id }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Phone No:</th>
                                        <td>{{ authExpert.phone }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Date Joined:</th>
                                        <td>{{ authExpert.created_at | moment('DD/MM/YYYY') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6 mt-n3" v-if="!openEdit">
                            <v-btn text dark color="blue darken-2" @click="editProfile"><v-icon>edit</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-text-field label="First Name" v-model="edit.first_name" required></v-text-field>
                            <v-text-field label="Last Name" v-model="edit.last_name" required></v-text-field>
                            <v-text-field label="Phone No" v-model="edit.phone" required></v-text-field>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-8">
                            <v-btn text color="red darken-2" large width="30%" @click="openEdit = false">Cancel</v-btn>
                            <v-btn dark color="primary" large width="50%" :loading="isUpdating" @click="updateProfile">Update</v-btn>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card raised elevation="10" light flat min-height="150">
                    <v-card-title class="justify-center subtitle-1 primary white--text">
                        Change Account Password
                    </v-card-title>
                    <v-card-text class="mt-5">
                        <v-btn text color="primary" @click="openChangePswd = !openChangePswd"><v-icon left>lock</v-icon>Change Password</v-btn>
                        <template v-if="openChangePswd">
                            <v-text-field label="Current Password" type="password" v-model="changePassword.current_pswd" @blur="checkOldPswd" v-validate="'required'" :error-messages="errors.collect('pswd.current_pswd')" name="current_pswd" data-vv-scope="pswd" data-vv-as="current password"></v-text-field>
                            <div class="red--text darken-2 mb-3 font-italic subtitle-2" v-if="pswdChngErr">{{ oldPswdError }}</div>
                            <v-text-field label="New Password" type="password" v-model="changePassword.new_pswd" ref="new_pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('pswd.new_pswd')" name="new_pswd" data-vv-scope="pswd" data-vv-as="new password"></v-text-field>
                            <div class="red--text darken-2 mt-n3 mb-3 font-italic subtitle-2" v-if="newPswdChngErr">{{ newPswdErr }}</div>
                            <v-text-field label="Confirm Password" type="password" v-model="changePassword.confirm" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('pswd.confirm')" name="confirm" data-vv-scope="pswd" data-vv-as="confirm password"></v-text-field>
                            <v-card-actions class="justify-center pb-8 mt-3">
                                <v-btn color="primary" large :loading="isUpdating" @click="updatePswd">Update Password</v-btn>
                            </v-card-actions>
                        </template>
                        <!-- <v-divider></v-divider> -->
                    </v-card-text>
                </v-card>
                <v-card raised elevation="10" min-height="300" light flat class="mt-3">
                    <v-card-title class="justify-center sub_title primary white--text">Account Links</v-card-title>
                    <v-card-text>
                        <expert-other-routes />
                    </v-card-text>
                </v-card>
                <v-card raised elevation="10" min-height="200" light flat class="mt-3">
                    <v-card-title class="justify-center sub_title primary white--text">Bank Details</v-card-title>
                    <v-card-text>
                        <template v-if="expertBankDetailsStatus">
                            <template v-if="!editBankDetails">
                                <table class="table table-hover py-4 mt-4">
                                    <thead> </thead>
                                    <tbody>
                                        <tr>
                                            <th style="border-top: none" width="30%">Account Name</th>
                                            <td style="border-top: none">{{ bankInfo.account_name }}</td>
                                        </tr>
                                        <tr>
                                            <th style="border-top: none" width="30%">Bank</th>
                                            <td style="border-top: none">{{ bankInfo.bank.name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Type:</th>
                                            <td>{{ bankInfo.account_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account No</th>
                                            <td>{{ bankInfo.account_no }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <v-card-actions class="justify-center pb-6 mt-n3">
                                    <v-btn text dark color="blue darken-4" @click="openBankDetailsEdit"><v-icon>edit</v-icon></v-btn>
                                </v-card-actions>
                            </template>
                            <template v-else>
                                <v-text-field label="Account Name" persistent-hint v-model="bankInfo.account_name" v-validate="'required|min:3|max:50'" :error-messages="errors.collect('bank-update.account_name')" name="account_name" data-vv-scope="bank-update" data-vv-as="account name"></v-text-field>
                                <v-select label="Select Bank" v-model="bankInfo.bank_id" :items="banks" item-text="name" item-value="id" required v-validate="'required'" :error-messages="errors.collect('bank-update.name')" name="name" data-vv-scope="bank-update" data-vv-as="bank name"></v-select>
                                <v-select label="Account Type" v-model="bankInfo.account_type" :items="accountTypes" item-text="type" item-value="type" required v-validate="'required'" :error-messages="errors.collect('bank-update.type')" name="type" data-vv-scope="bank-update" data-vv-as="account type"></v-select>
                                <v-text-field label="Account No" v-model="bankInfo.account_no" v-validate="'required|numeric|max:10'" :error-messages="errors.collect('bank-update.account_no')" name="account_no" data-vv-scope="bank-update" data-vv-as="account number"></v-text-field>
                                <v-card-actions class="justify-space-around pb-6">
                                    <v-btn text width="30%" large color="red darken-3" @click="editBankDetails = false">Cancel</v-btn>
                                    <v-btn color="primary lighten-2" width="50%" large @click="updateBankDetails" :loading="isUpdating">Save</v-btn>
                                </v-card-actions>
                            </template>
                        </template>
                        <template v-else>
                            <template v-if="!setupBankDetails">
                                <v-alert type="info" class="mt-5">You have not set up your bank details. Bank details are needed for your subscriptions to be paid.</v-alert>
                                <v-card-actions class="justify-center pb-6 mt-n3">
                                    <v-btn text dark color="blue darken-4" @click="setupBankDetails = true">Set Up Bank Details</v-btn>
                                </v-card-actions>
                            </template>
                            <template v-else>
                                <v-text-field label="Account Name" v-model="newBankDetails.account_name" required v-validate="'required|min:5|max:50'" :error-messages="errors.collect('bank.account_name')" name="account_name" data-vv-scope="bank" data-vv-as="account name"></v-text-field>
                                <v-select label="Banks" v-model="newBankDetails.bank" :items="banks" item-text="name" item-value="id" required v-validate="'required'" :error-messages="errors.collect('bank.name')" name="name" data-vv-scope="bank" data-vv-as="bank name"></v-select>
                                <v-select label="Account Type" v-model="newBankDetails.account_type" :items="accountTypes" item-text="type" item-value="type" required v-validate="'required'" :error-messages="errors.collect('bank.type')" name="type" data-vv-scope="bank" data-vv-as="account type"></v-select>
                                <v-text-field label="Account Number" v-model="newBankDetails.account_no" required v-validate="'required|numeric|max:10'" :error-messages="errors.collect('bank.type')" name="type" data-vv-scope="bank" data-vv-as="account number"></v-text-field>
                                <v-card-actions class="justify-center pb-6">
                                    <v-btn width="30%" large text color="red darken-2" @click="setupBankDetails = false">Cancel</v-btn>
                                    <v-btn width="50%" large dark color="primary darken-2" @click="saveBankDetails" :loading="isUpdating">Save</v-btn>
                                </v-card-actions>
                            </template>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="updateProfileFailed" :timeout="6000" top color="red darken-1 white--text">
            Update failed. Please try again.
            <v-btn text color="white--text" @click="updateProfileFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value ="expertProfileUpdated" :timeout="4000" top color="green darken-1 white--text">
            Profile has been updated successfully.
            <v-btn text color="white--text" @click="expertProfileUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChangeFailed" :timeout="6000" top color="red darken-1 white--text">
            Your profile password change failed. Please ensure you have mobile network before trying again.
            <v-btn text color="white--text" @click="passwordChangeFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChanged" :timeout="6000" top color="green darken-1 white--text">
            Your profile password was updated successfully.
            <v-btn text color="white--text" @click="passwordChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDetailsCreateFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to add your bank details. Please make sure all fields are filled before trying again.
            <v-btn text color="white--text" @click="bankDetailsCreateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDetailsCreated" :timeout="4000" top color="green darken-1 white--text">
            Your bank details was added succssfully.
            <v-btn text color="white--text" @click="bankDetailsCreated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDetailsUpdated" :timeout="4000" top color="green darken-1 white--text">
            Your bank details was updated succssfully.
            <v-btn text color="white--text" @click="bankDetailsUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="bankDetailsUpdateFail" :timeout="4000" top color="red darken-1 white--text">
            There was an error while trying to update your bank details. Please make sure all fields are filled before trying again.
            <v-btn text color="white--text" @click="bankDetailsUpdateFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            openEdit: false,
            edit: {
                first_name: '',
                last_name: '',
                phone: ''
            },
            isUpdating: false,
            updateProfileFailed: false,
            changePic: false,
            prvImg: false,
            prvImgUrl: '',
            image: '',
            imgUploadSuccess: false,
            openChangePswd: false,
            changePassword: {
                current_pswd: '',
                new_pswd: '',
                confirm: ''
            },
            pswdChngErr: false,
            oldPswdError: '',
            newPswdChngErr: false,
            newPswdErr: '',
            passwordChanged: false,
            passwordChangeFailed: false,
            setupBankDetails: false,
            editBankDetails: false,
            bankDetails: {
                bank_id: null,
                bank: null,
                account_no: '',
                account_type: '',
                account_name: ''
            },
            banks: [],
            accountTypes: [
                {id: 1, type: 'Savings'},
                {id: 2, type: 'Current'},
                {id: 3, type: 'Others'},
            ],
            newBankDetails: {
                bank: null,
                account_type: '',
                account_no: null,
                account_name: ''
            },
            bankDetailsCreateFailed: false,
            bankDetailsCreated: false,
            expertBankDetailsStatus: false,
            bankInfo: {
                bank_id: null,
                bank: null,
                account_type: '',
                account_no: '',
                account_name: ''
            },
            bankDetailsUpdated: false,
            bankDetailsUpdateFail: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authExpert(){
            return this.$store.getters.authExpert
        },
        expertHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authExpert.token}`
                }
            }
            return headers
        },
        expertProfileUpdated(){
            return this.$store.getters.expertProfileUpdated
        },
    },
    methods: {
        editProfile(){
            this.openEdit = true
            this.edit = this.authExpert
        },
        updateProfile(){
            this.isUpdating = true
            axios.post(this.api + `/auth-expert/update_profile`, {
                profile: this.edit
            }, this.expertHeader).then((res) =>{
                this.isUpdating = false
                this.$store.commit('updatedExpertProfile', res.data)
                this.openEdit = false
            }).catch(()=>{
                this.isUpdating = false
                this.updateProfileFailed = true
            })
        },
        openUpload(){
            this.$refs.image.click()
        },
        pickImg(e){
            const file = e.target.files[0]
            this.image = file
            this.prvImg = true
            this.prvImgUrl = URL.createObjectURL(file)
        },
        uploadImg(){
            if(this.image !== null){
                this.isUpdating = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth-expert/update_profile_picture`, form, this.expertHeader)
                .then((res) => {
                    this.isUpdating = false
                    this.changePic = false
                    const payload = {}
                    payload.user = res.data
                    payload.token = this.authExpert.token
                    this.$store.commit('expertprofilePicUpdated', payload)
                    this.removeImg()
                }).catch(() => {
                    this.isUpdating = false
                    this.updateProfileFailed = true
                })
            }

        },
        removeImg(){
            this.image = null
            this.prvImg = false
            this.prvImgUrl = ''
        },
        checkOldPswd(){
            if(this.changePassword.current_pswd !== ''){
                this.pswdChngErr = false
                axios.post(this.api + '/auth-expert/confirm_current_pswd', {
                    password: this.changePassword.current_pswd
                }, this.expertHeader).then((res) => {
                    if(res.data == false){
                        this.pswdChngErr = true
                        this.oldPswdError = 'Invalid password.'
                    }
                })
            }
        },
        updatePswd(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if (isValid) {
                    if(this.pswdChngErr == false){
                        if(this.changePassword.current_pswd !== this.changePassword.new_pswd){
                            this.isUpdating = true
                            axios.post(this.api + '/auth-expert/update_profile_password', {
                                password: this.changePassword.new_pswd.trim(),
                                password_confirmation: this.changePassword.confirm.trim()
                            }, this.expertHeader)
                            .then((res) => {
                                this.isUpdating = false
                                this.passwordChanged = true
                                this.openChangePswd = false
                                this.changePassword.current_pswd = ''
                                this.changePassword.new_pswd = ''
                                this.changePassword.confirm = ''
                                this.$validator.pause()
                                this.$validator.fields.items.forEach(field => field.reset())
                                this.$validator.errors.clear()
                                this.newPswdChngErr = false
                                this.newPswdErr = ''
                            }).catch(()=>{
                                this.passwordChangeFailed = true
                            })
                        }else{
                            this.newPswdChngErr = true
                            this.newPswdErr = 'Invalid action. New password cannot be the same as the one you are trying to change.'
                        }
                    }
                }
            })
        },
        openBankDetailsEdit(){
            this.editBankDetails = true
            // this.bankDetails.bank_id = this.bankInfo.bank.id
            // this.bankDetails.bank = this.bankInfo.bank
            // this.bankDetails.account_no = this.bankInfo.account_no
            // this.bankDetails.account_type = this.bankInfo.account_type
            // this.bankDetails.account_name = this.bankInfo.account_name
        },
        getAllBanks(){
            axios.get(this.api + `/get_all_banks`).then((res) => {
                this.banks = res.data
            })
        },
        getBankDetails(){
            axios.get(this.api + '/auth-expert/get_expert_bank_details', this.expertHeader)
            .then((res) => {
                // console.log(res.data)
                if(res.data.bank_id){
                    this.expertBankDetailsStatus = true
                    this.bankInfo.bank_id = res.data.bank_id
                    this.bankInfo.bank = res.data.bank
                    this.bankInfo.account_no = res.data.account_no
                    this.bankInfo.account_type = res.data.account_type
                    this.bankInfo.account_name = res.data.account_name
                }else{
                    // console.log(res)
                }
            }).catch((err) => {
                if(err.response.status == 404){
                    this.expertBankDetailsStatus = false
                }
            })
        },
        saveBankDetails(){
            this.$validator.validateAll('bank').then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth-expert/add_bank_details', {
                        details: this.newBankDetails
                    }, this.expertHeader).then((res) => {
                        this.isUpdating = false
                        this.expertBankDetailsStatus = true
                        this.bankDetailsCreated = true
                        this.getBankDetails()
                    }).catch(() => {
                        this.isUpdating = false
                        this.bankDetailsCreateFailed = true
                    })
                }
            })
        },
        updateBankDetails(){
            this.$validator.validateAll('bank-update').then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                     axios.post(this.api + `/auth-expert/update_expert_bank_details`, {
                        details: this.bankInfo
                    }, this.expertHeader).then((res)=>{
                        this.isUpdating = false
                        this.getBankDetails()
                        this.bankDetailsUpdated = true
                        this.editBankDetails = false
                    }).catch(()=>{
                        this.isUpdating = false
                        this.bankDetailsUpdateFail = true
                    })
                    // console.log(this.bankDetails)
                }
            });
        }
    },
    created(){
        this.getAllBanks()
        this.getBankDetails()
    }
}
</script>

<style lang="css" scoped>


</style>
