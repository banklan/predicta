<template>
    <v-container>
        <v-row wrap justify="center" class="mt-3">
            <v-col cols="12" md="8">
                <v-card shaped light class="mx-auto px-8">
                    <v-card-title class="headline justify-center">About Us</v-card-title>
                    <v-card-text>
                        <p>Surepredict is a platform that was created to help bettors minimize risks by predicting the most likely outcomes of football matches.</p>
                        <p>We employ talented experts who are very familiar with each football league and tournament, and whose accuracy over time is very high.</p>
                        <p>Our daily tips offer list of daily matches and their likely outcomes for free. Users are free to choose matches and tips from these.</p>
                        <p>However, our premium service offers users who want to invest in football betting at safer low-odds of 3, 5 and 10 odds, with the objective of making profits at the long run. While we cannot always fully guarantee the result of
                            football matches due to some other factors that are unmanageable, it isn't a hidden fact that low odd predictions offer a higher chance of making profits in the betting world.
                            Kindly note that a subscription to our premium expert service offers users access to all the predictions of that
                            expert for 7 days. Users can place bets on any of the accumulators of the expert within their access period.
                            However, please note that the games a user chooses to place bets on is solely at their discretion and by signing up to the service, they have already agreed that no refunds shall
                            be granted after such services has been subscribed to.</p>
                        <p>We are constantly in the look out for talented and professional football game tipsters to add to our ranks, so if you are one, kindly <router-link to="/contact-us"> contact </router-link>us today.</p>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="scss" scoped>
    .v-card__text{
        font-family: 'Source Sans Pro', sans-serif;
        line-height: 1.80;
        color: rgba(0,0,0,.88) !important;

        p{
            font-size: 19px !important;
            line-height: 1.80;
            font-family: 'Source Sans Pro', sans-serif;
        }
    }
</style>
