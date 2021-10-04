<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Create Country" />
            </v-col>
        </v-row>
        <v-row class="justify-start mt-5 ml-n10">
            <v-col cols="12" md="5">
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
                        <v-btn dark color="primary" @click="addCountry" :loading="isLoading"><v-icon left>plus</v-icon>Add Country</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
             <v-col cols="12" md="7">
                <v-card light raised elevation="10" min-height="200" class="scroll">
                    <v-card-title class="justify-center sub_title primary darken-2 white--text">Countries <v-chip class="ml-1" v-if="countries.length > 0">{{countries.length}} </v-chip></v-card-title>
                    <template v-if="countries.length > 0">
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Country</th>
                                        <th>Abbrv</th>
                                        <th style="text-align:center">Flag</th>
                                        <th style="text-align:center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(country, index) in countries" :key="index">
                                        <td class="capitalize">{{ country.name }}</td>
                                        <td class="capitalize">{{ country.abbrv }}</td>
                                        <td><v-img :src="country.flagPrev" height="20" alt="Flag" aspect-ratio="1"></v-img></td>
                                        <td width="30%" style="text-align:center"><v-btn text dark x-small color="primary"><v-icon>edit</v-icon></v-btn>&nbsp;<v-btn text dark x-small color="red darken-2" @click="delCountry(country, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6">
                            <v-btn width="40%" text large color="red darken--2" @click="clearCountries">Clear All</v-btn>
                            <v-btn width="40%" dark large color="primary darken-2" :loading="isBusy" @click="saveCountries">Submit</v-btn>
                        </v-card-actions>
                    </template>
                    <v-card-text v-else class="body_text my-5">
                        No countries have been added.
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="createFailed" :timeout="6000" top color="red darken-1 white--text">
            An error occured while trying to add the new country. Please ensure the country doesn't already exist and try again.
            <v-btn text color="white--text" @click="createFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="countriesCleared" :timeout="4000" top color="green darken-1 white--text">
            The countries have been cleared.
            <v-btn text color="white--text" @click="countriesCleared = false">Close</v-btn>
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
                flag: '',
                flagPrev: ''
            },
            previewImg: false,
            previewImgUrl: '',
            isLoading: false,
            createFailed: false,
            countries: [],
            isBusy: false,
            countriesCleared: false
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
        addCountry(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isLoading = true
                    this.country.flagPrev = this.previewImgUrl
                    this.countries.unshift(this.country)
                    let countries = JSON.parse(localStorage.getItem('newCountries'))
                    if(countries){
                        countries.unshift(this.country)
                        localStorage.setItem('newCountries', JSON.stringify(countries))
                    }else{
                        localStorage.setItem('newCountries', JSON.stringify(this.countries))
                    }
                    this.resetField()
                    this.removeImg()
                    this.isLoading = false
                }
            })
        },
        delCountry(country, index){
            this.countries.splice(index, 1)
            localStorage.setItem('newCountries', JSON.stringify(this.countries))
        },
        saveCountries(){
            for (let i=0; i< this.countries.length; i++) {
                const item = this.countries[i];
                console.log(item)
            }
        },
        clearCountries(){
            this.countries = []
            localStorage.removeItem('newCountries')
            this.countriesCleared = true
        },
        // save(){
        //      this.$validator.validateAll().then((isValid) => {
        //         if(isValid) {
        //             this.isLoading = true
        //             let form = new FormData();
        //             form.append('flag', this.country.flag)
        //             form.append('country', this.country.name)
        //             form.append('abbrv', this.country.abbrv)
        //             axios.post(this.api + '/auth-admin/create_country', form, this.adminHeaders)
        //             .then((res)=>{
        //                 this.isLoading = false
        //                 this.$store.commit('newCountryAdded')
        //                 this.$router.push({name: 'AdminCountryList'})
        //             }).catch((err)=>{
        //                 this.isLoading = false
        //                 this.createFailed = true
        //             })
        //         }
        //      })
        // },
        resetField(){
            this.country = {}
            this.$validator.reset()
            // this.$validator.pause()
            // this.$validator.fields.items.forEach(field => field.reset())
            // this.$validator.errors.clear()
        },
        removeImg(){
            // this.country.flag = null
            this.previewImg = false
            // this.previewImgUrl = ''
        },
        getCountriesCreated(){
            let countries = JSON.parse(localStorage.getItem('newCountries'))
            this.countries = countries
        }
    },
    created(){
        this.getCountriesCreated()
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    .capitalize{
        text-transform: capitalize;
    }
</style>
