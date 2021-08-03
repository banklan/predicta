<template>
    <v-container>
        <expert-top-panel title="Subscriptions"/>
        <v-row justify="space-around" class="mt-5">
            <v-col cols="12" md="8">
                <v-card raised elevation="10" min-height="300">
                    <v-card-title class="justify-center sub_title primary white--text">Subscriptions to my forecasts </v-card-title>
                    <table class="table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr class="primary white--text text-center">
                                <th>Date</th>
                                <th>User</th>
                                <th>Prediction</th>
                                <th>Odd</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td>28/07/2021</td>
                                <td>Ademola Joshua</td>
                                <td>0145252145</td>
                                <td>3</td>
                                <td>Won</td>
                            </tr>
                            <tr align="center">
                                <td>28/07/2021</td>
                                <td>Ademola Joshua</td>
                                <td>0145252145</td>
                                <td>3</td>
                                <td>Won</td>
                            </tr>
                            <tr align="center">
                                <td>28/07/2021</td>
                                <td>Ademola Joshua</td>
                                <td>0145252145</td>
                                <td>3</td>
                                <td>Won</td>
                            </tr>
                            <tr align="center">
                                <td>28/07/2021</td>
                                <td>Ademola Joshua</td>
                                <td>0145252145</td>
                                <td>3</td>
                                <td>Won</td>
                            </tr>
                        </tbody>
                    </table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>


<style lang="css" scoped>
    .v-card .v-card__text{
        overflow: scroll !important;
    }
</style>
