<template>
    <v-container>
        <expert-top-panel />
        <v-row justify="space-around" wrap class="mt-5">
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised min-height="300" color="#f72585">
                    <v-card-title class="white--text sub_title justify-center">My Forecasts</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Total Forecasts:</th>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <th>Correct Forecasts:</th>
                                    <td>68</td>
                                </tr>
                                <tr>
                                    <th>Winning Rate(%)</th>
                                    <td>68</td>
                                </tr>
                                <tr>
                                    <th>Total Subscriptions:</th>
                                    <td>122</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6 mt-n4">
                        <v-btn outlined color="white">My Forecasts</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card elevation="8" dark raised color="#1d9478" min-height="300">
                    <v-card-title class="white--text sub_title justify-center">Current Forecasts</v-card-title>
                    <v-card-text class="my-5 pl-7 pr-7">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th>Total Forecasts:</th>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <th>Won</th>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <th>Earnings</th>
                                    <td>&#8358;10,500</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-6 mt-n4">
                        <v-btn outlined color="white">Current Forecast</v-btn>
                        <v-btn outlined color="white" :to="{name: 'NewForecast'}">New Forecast</v-btn>
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

        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authExpert(){
            return this.$store.getters.authExpert
        }
    }
}
</script>

<style lang="css" scoped>
    table tr{
        color: #fff !important;
    }
</style>
