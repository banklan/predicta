<template>
    <v-container>
        <v-row class="justify-content-end mt-n5 mb-n8">
            <v-col cols="12" md="6" offset-md="3">
                <v-card elevation="4" light raised>
                    <v-card-title class="primary secondary--text subtitle-1 justify-center">
                        <v-row justify="space-between">
                            <v-col cols="6">{{ title }} </v-col>
                            <v-col cols="6">
                                Welcome, {{ authExpert && authExpert.first_name }} | Expert
                            </v-col>
                        </v-row>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: ['title'],
    computed: {
        authExpert(){
            return this.$store.getters.authExpert
        }
    }
}
</script>
