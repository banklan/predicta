<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <authuser-top-panel :user="`Welcome ${authUser.first_name}`" title="New Feedback" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="100" class="mt-5 scroll">
                    <v-card-title class="primary darken-2 white--text subtitle-1 justify-center">New Feedback</v-card-title>
                    <v-card-text class="mt-5">
                        <v-row>
                            <v-col cols="12" md='6'>
                                <v-select label="What is your feedback about" v-model="fb.about" :items="abouts" item-text="text" item-value="id" @change="loadAbouts"></v-select>
                            </v-col>
                            <v-col cols="12" md='6' v-if=openSubIdField>
                                <v-text-field label="Input subscription ID" v-model="fb.sub_id" required></v-text-field>
                            </v-col>
                            <v-col cols="12" md='6' v-if=openExpField>
                                <v-select label="Choose Expert" v-model="fb.expert" :items="experts" item-text="username" item-value="username"></v-select>
                            </v-col>
                        </v-row>
                        <v-text-field label="Subject" required v-model="fb.subject"></v-text-field>
                        <v-textarea rows="2" label="Type your feedback here" v-model="fb.body"></v-textarea>

                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn dark width="60%" large color="primary darken-2" :loading="isBusy" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isLoading: false,
            abouts: [
                {id: 1, text: 'Subscriptions'},
                {id: 2, text: 'Payment'},
                {id: 3, text: 'Tip Experts'},
                {id: 4, text: 'Site Admin'},
                {id: 5, text: 'Daily Tips'},
                {id: 6, text: 'General'},
            ],
            fb:{
                about: '',
                sub_id: '',
                expert: null,
                subject: '',
                body: ''
            },
            openSubIdField: false,
            openExpField: false,
            experts: [],
            isBusy: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        loadAbouts(){
            if(this.fb.about == 1){
                this.openSubIdField = true
                this.openExpField = false
            }else
            if(this.fb.about == 3){
                this.openExpField = true
                this.openSubIdField = false
                this.loadExperts()
            }else{
                this.openSubIdField = false
                this.openExpField = false
            }
        },
        loadExperts(){
            this.isLoading = true
            axios.get(this.api + '/get_all_experts').then((res) => {
                this.experts = res.data
            })
        },
        submit(){
            this.isBusy = true
            axios.post(this.api + '/auth/submit_users_feedback', {
                feedback: this.fb
            }, this.authHeaders).then((res) => {
                this.isBusy = false
                this.$store.commit('feedbackPosted')
                this.$router.push({name: 'UserFeedback'})
            }).catch(() => {
                this.isBusy = false
            })
        }
    }
}
</script>
