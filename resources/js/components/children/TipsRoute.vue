<template>
    <v-container>
        <v-row class="justify-md-start mb-2">
            <v-col cols="12" md="2">
                <v-btn text block dark color="primary darken-2" :to="{name: 'TodaysTips'}">Today's Tips</v-btn>
            </v-col>
             <v-col cols="12" md="2">
                <v-btn text block dark color="primary darken-2" :to="{name: 'TomorrowsTips'}">Tomorrow's Tips</v-btn>
             </v-col>
             <v-col cols="12" md="2">
                <v-btn text block dark color="primary darken-2" :to="{name: 'WonTips'}">Won Tips</v-btn>
             </v-col>
        </v-row>
    </v-container>
</template>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
</style>
