<template lang="">
    <v-container>
        <tips-route />
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Expert Forecasts won within the last month</v-card-title>
                    <v-card-text>
                        <template v-if="fcs.length > 0">
                            <v-simple-table light fixed-header height="400">
                                <template v-slot:default>
                                    <thead>
                                        <tr class="">
                                            <th>Date</th>
                                            <th>Expert</th>
                                            <th>Forecast id</th>
                                            <th>Odd Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="fc in fcs" :key="fc.id">
                                            <td>{{ fc.published  }}</td>
                                            <td v-if="fc.expert">{{ fc.expert.username }}</td>
                                            <td>{{ fc.forecast_id }}</td>
                                            <td>{{ fc.forecast_odd }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are no won forecasts to view yet.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            fcs: [],
            isLoading: false,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getWonTips(){
            this.isLoading = true
            axios.get(this.api + '/get_won_experts_forecasts').then((res) => {
                this.isLoading = false
                this.fcs = res.data
                console.log(res.data)
            })
        },
    },
    created(){
        this.getWonTips()
    }
}
</script>

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

