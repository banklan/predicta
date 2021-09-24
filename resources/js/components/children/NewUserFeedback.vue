<template>
    <div class="new_feedback">
        <v-card flat min-height="100">
            <template v-if="!feedbackSent">
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
                    <v-text-field label="Subject" required v-model="fb.subject" v-validate="'required|min:3|max:120'" :error-messages="errors.collect('subject')" name="subject"></v-text-field>
                    <v-textarea label="Type feedback here" rows="2" auto-grow v-model="fb.body" v-validate="'required|min:10|max:600'" :error-messages="errors.collect('body')" name="body"></v-textarea>
                </v-card-text>
                <v-card-actions class="justify-center pb-8">
                    <v-btn dark width="60%" large color="primary darken-2" :loading="isBusy" @click="submit">Submit</v-btn>
                </v-card-actions>
            </template>
            <template v-else>
                <v-alert type="success" class="mt-10 mx-5">
                    Your feedback has been sent. We shall get back to you as soon as possible. Thanks.
                </v-alert>
            </template>
        </v-card>
    </div>
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
            isBusy: false,
            feedbackSent: false
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
            console.log(this.fb.about)
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
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isBusy = true
                    axios.post(this.api + '/auth/submit_users_feedback', {
                        feedback: this.fb
                    }, this.authHeaders).then((res) => {
                        this.isBusy = false
                        this.feedbackSent = true
                        console.log(res.data)
                    }).catch(() => {
                        this.isBusy = false
                    })
                }
            })
        }
    }
}
</script>
