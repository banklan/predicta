<template>
    <v-container>
        <!-- <admin-top-panel title="Create Country"></admin-top-panel> -->
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Create Country" />
            </v-col>
        </v-row>
        <v-row class="justify-start mt-5 ml-n10">
            <v-col cols="12" md="8">
                <v-card light raised elevation="10" min-height="200">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text">New Country </v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="Country" v-model="country.name" required v-validate="'required|min:3|max:20'" :error-messages="errors.collect('country')" name="country"></v-text-field>
                        <v-text-field label="Abbreviation" v-model="country.abbrv" required v-validate="'required|min:3|max:8'" :error-messages="errors.collect('abbreviation')" name="abbreviation"></v-text-field>
                        <template v-if="!previewImg">
                            <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Choose Flag</v-btn>
                            <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </template>
                        <template v-else>
                            <v-card-subtitle class="text-center subtitle-1">Preview</v-card-subtitle>
                            <v-img :src="previewImgUrl" height="100" contain alt="Flag" aspect-ratio="1"></v-img>
                            <v-card-actions class="justify-center mt-4 ml-n3">
                              <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                            </v-card-actions>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-5">
                        <v-btn dark color="primary" @click="save" :loading="isLoading"><v-icon left>cloud</v-icon>Save Country</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <!-- <v-snackbar v-model="emptyFields" :timeout="6000" top color="red darken-1 white--text">
            Both the country name and abbreviation fields must be filled.
            <v-btn text color="white--text" @click="emptyFields = false">Close</v-btn>
        </v-snackbar> -->
        <v-snackbar v-model="createFailed" :timeout="6000" top color="red darken-1 white--text">
            An error occured while trying to create the new country. Please try again.
            <v-btn text color="white--text" @click="createFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            country: {
                name: '',
                abbrv: '',
                flag: ''
            },
            previewImg: false,
            previewImgUrl: '',
            // emptyFields: false,
            isLoading: false,
            createFailed: false
        }
    },
    computed: {
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
        }
    },
    methods: {
        openUpload(){
            this.$refs.image.click()
        },
        pickImg(e){
            const file = e.target.files[0]
            this.country.flag = file
            this.previewImg = true
            this.previewImgUrl = URL.createObjectURL(file)
        },
        save(){
             this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isLoading = true
                    let form = new FormData();
                    form.append('flag', this.country.flag)
                    form.append('country', this.country.name)
                    form.append('abbrv', this.country.abbrv)
                    axios.post(this.api + '/auth-admin/create_country', form, this.adminHeaders)
                    .then((res)=>{
                        this.isLoading = false
                        this.$store.commit('newCountryAdded')
                        this.$router.push({name: 'AdminCountryList'})
                    }).catch((err)=>{
                        this.isLoading = false
                        this.createFailed = true
                        console.log(err)
                    })
                }
             })
        },
        removeImg(){
            this.country.flag = null
            this.previewImg = null
            this.previewImgUrl = ''
        },
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    /* .v-card .v-card__text{
        overflow: scroll !important;
    } */
</style>
