<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="6" offset-md="6">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="My Account" />
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="4">
                <v-card raised elevation="10" min-height="300">
                    <v-card-title class="justify-center sub_title primary white--text">Profile</v-card-title>
                        <template v-if="!changePic">
                            <v-img :src="`/images/profiles/users/${ authUser.picture ? authUser.picture : 'avatar.jpg'}`" width="100%" height="300" transition="scale-transition"></v-img>
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
                                        <td style="border-top: none">{{ authUser.fullname }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Email:</th>
                                        <td>{{ authUser.email }}</td>
                                    </tr>
                                    <tr style="border-top: none">
                                        <th>Phone No:</th>
                                        <td>{{ authUser.phone }}</td>
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
                    </v-card-text>
                    <v-card-text class="mt-n5 px-5">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <v-btn text color="primary darken-2" :to="{name: 'Subscriptions'}">My Subscriptions</v-btn>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <v-btn text color="primary darken-2" :to="{name: 'AllTipExperts'}">New Subscription</v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="updateProfileFailed" :timeout="6000" top color="red darken-1 white--text">
            Update failed. Please try again.
            <v-btn text color="white--text" @click="updateProfileFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value ="userProfileUpdated" :timeout="4000" top color="green darken-1 white--text">
            Profile has been updated successfully.
            <v-btn text color="white--text" @click="userProfileUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChangeFailed" :timeout="6000" top color="red darken-1 white--text">
            Your profile password change failed. Please ensure you have mobile network before trying again.
            <v-btn text color="white--text" @click="passwordChangeFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChanged" :timeout="6000" top color="green darken-1 white--text">
            Your profile password was updated successfully.
            <v-btn text color="white--text" @click="passwordChanged = false">Close</v-btn>
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
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        userProfileUpdated(){
            return this.$store.getters.userProfileUpdated
        },
    },
    methods: {
        editProfile(){
            this.openEdit = true
            this.edit = this.authUser
        },
        updateProfile(){
            this.isUpdating = true
            axios.post(this.api + `/auth/update_profile`, {
                profile: this.edit
            }, this.authHeader).then((res) =>{
                this.isUpdating = false
                this.$store.commit('updatedUserProfile', res.data)
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

                axios.post(this.api + `/auth/update_profile_picture`, form, this.authHeader)
                .then((res) => {
                    this.isUpdating = false
                    this.changePic = false
                    const payload = {}
                    payload.user = res.data
                    payload.token = this.authUser.token
                    this.$store.commit('userProfilePicUpdated', payload)
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
                axios.post(this.api + '/auth/confirm_current_pswd', {
                    password: this.changePassword.current_pswd
                }, this.authHeader).then((res) => {
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
                            axios.post(this.api + '/auth/update_profile_password', {
                                password: this.changePassword.new_pswd.trim(),
                                password_confirmation: this.changePassword.confirm.trim()
                            }, this.authHeader)
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
    },
    created(){
    }
}
</script>

<style lang="css" scoped>


</style>
