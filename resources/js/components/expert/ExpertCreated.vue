<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-alert type="success" border="left" class="mt-8 py-1">
                    You have successfully registered as an expert on tipexparts.com. We have sent an email to your email address({{ newExpertEmail }}). Kindly check and verify your email to enable you login. 
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    computed:{
        newExpertEmail(){
            return this.$store.getters.newRegExpert
        }
    }
}
</script>