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
                <top-experts />
                <!-- <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Our Top Tip Experts</v-card-title>
                    <v-card-text>
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                                <tr class="caption">
                                    <th>Expert</th>
                                    <th>Win Rate(%)</th>
                                    <th>Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John</td>
                                    <td>58</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Matt</td>
                                    <td>54</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Moses</td>
                                    <td>48</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-5">
                        <v-btn text large color="primary darken-2" :to="{name: 'AllExperts'}">View All Experts</v-btn>
                    </v-card-actions>
                </v-card> -->
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
</style>

