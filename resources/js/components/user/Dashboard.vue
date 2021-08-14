<template>
    <v-container>
        <v-row class="mt-2">
            <v-col cols="12" md="6" offset-md="6">
                <authuser-top-panel :right="`Welcome ${authUser.first_name}`" left="Dashboard" />
            </v-col>
        </v-row>
        <v-row justify="space-around" wrap class="mt-5">
            <v-col cols="12" md="5">
                <v-card elevation="6" light raised min-height="200" class="">
                    <v-card-title class="justify-center primary white--text subtitle-1">My Subscriptions</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="subscriptions.length = 0"></template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                You have no subscriptions at the moment.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">{{ isLoggedIn }}</v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            subscriptions: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        isLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        }
    }
}
</script>
