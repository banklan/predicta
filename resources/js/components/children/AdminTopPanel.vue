<template>
    <!-- <v-container> -->
        <v-row class="justify-content-end" wrap>
            <v-col cols="12" md="6">
                <v-card elevation="4" light raised>
                    <v-card-title class="primary darken-2 secondary--text subtitle-2 justify-center">
                        <v-row justify="space-between">
                            <v-col cols="6">{{ title }} </v-col>
                            <v-col cols="6">
                                {{ authAdmin && authAdmin.first_name }} | Admin
                            </v-col>
                        </v-row>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    <!-- </v-container> -->
</template>

<script>
export default {
    props: ['title'],
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        }
    }
}
</script>
