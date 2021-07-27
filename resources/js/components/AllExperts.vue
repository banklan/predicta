<template lang="">
    <v-container>
        <tips-route />
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">All Experts</v-card-title>
                    <v-card-text>
                        <v-simple-table light fixed-header height="400">
                            <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Win Wate(%)</th>
                                    <th>3+ Odds</th>
                                    <th>5+ Odds</th>
                                    <th>10+ Odds</th>
                                    <th>VVIP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Matt</td>
                                    <td>88</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Jose</td>
                                    <td>75</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Rich</td>
                                    <td>70</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Mike</td>
                                    <td>59</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Fine</td>
                                    <td>49</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Dan</td>
                                    <td>52</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                                <tr>
                                    <td>Ben</td>
                                    <td>46</td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                    <td><v-btn small text color="primary">Access</v-btn></td>
                                </tr>
                            </tbody>
                            </template>
                        </v-simple-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
    .sub_title {
        font-size: 1.2rem;
        font-weight: 500;
    }
    .v-container{
        background-color: #000 !important;
    }
</style>

