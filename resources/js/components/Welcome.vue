<template lang="">
    <v-container>
        <tips-route />
        <v-row justify="center">
            <v-col cols="12" md="9">
                <sure-odds />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="9">
                <won-tips />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="9">
                <won-expert-forecasts />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="9">
                <v-card elevation="12" light raised min-height="100" class="mt-5 py-5">
                    <v-card-title class="justify-center title font-weight-normal">Are you a good football tipster?</v-card-title>
                    <v-img src="/images/assets/thinking.png" contain height="300" transition="scale-transition" ></v-img>
                    <v-card-text class="mt-5 pb-8 px-5 caption_text">
                        Do you have a passion for football games prediction? Are you a professional or talented proven football tipster/forecaster?
                        If your answers are yes, then you are the one we are looking for! You can earn good money by the side by predicting for tipexpats.
                        Kindly <router-link to="/expert-register">register with us </router-link> today to become a paid expert tipster.
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="9">
                <top-experts />
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
</style>

