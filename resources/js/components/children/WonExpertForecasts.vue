<template>
    <div class="won_tips">
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-card v-else light raised elevation="8" min-height="200">
            <v-card-title class="sub_title primary white--text justify-center">Won Expert Forecasts </v-card-title>
            <v-card-text>
                <template v-if="fcs.length > 0">
                    <table class="table table-striped table-hover">
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
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="mt-5">
                        There are no won forecasts to view yet.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-6">
                <v-btn text color="primary darken-2" :to="{name: 'WonExpertsForecasts'}">View All Won Forecasts</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fcs: [],
            isLoading: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getBriefWonFcs(){
            this.isLoading = true
            axios.get(this.api + '/get_brief_won_expert_forecasts').then((res) =>{
                this.isLoading = false
                this.fcs = res.data
                console.log(res.data)
            })
        }
    },
    created() {
        this.getBriefWonFcs()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
