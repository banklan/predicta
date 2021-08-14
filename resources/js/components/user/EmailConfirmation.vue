<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <div class="white--text text-center pa-5 mt-8 body_text" :class="`${messageType}`">
                    {{ message }} <span v-if="messageType == 'success'"> You can now <router-link to="/login">login</router-link> into your account. Thank you.</span>
               </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            token: this.$route.query.token,
            message: '',
            messageType: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods:{
        confirmToken(){
            axios.post(this.api +`/confirm_user_email`, {
                token: this.$route.query.token
            }).then((res) => {
                if(res.status === 202){
                    this.message = 'The email you are trying to confirm has been previously confirmed!'
                    this.messageType = 'warning'
                }else if(res.status === 200){
                    this.message = 'Your email address has been confirmed.'
                    this.messageType = 'success'
                }
            })
        }
    },
    created() {
        this.confirmToken()
    },
}
</script>
