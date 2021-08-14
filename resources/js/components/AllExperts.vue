<template lang="">
    <v-container>
        <tips-route />
        <v-row>
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="300">
                    <v-card-title class="sub_title primary white--text justify-center">All Tip Experts</v-card-title>
                    <v-card-text>
                        <template v-if="experts.length > 0">
                            <v-simple-table light fixed-header height="300">
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
                                    <tr v-for="exp in experts" :key="exp.id">
                                        <td>{{ exp.username }}</td>
                                        <td>{{ exp.winning_rate }}</td>
                                        <td><v-btn small text color="primary">Access</v-btn></td>
                                        <td><v-btn small text color="primary">Access</v-btn></td>
                                        <td><v-btn small text color="primary">Access</v-btn></td>
                                        <td><v-btn small text color="primary">Access</v-btn></td>
                                    </tr>

                                </tbody>
                                </template>
                            </v-simple-table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are no tip experts to display at the moment.
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
            experts: [],
            isLoading: false,
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getExperts(){
            this.isLoading = true
            axios.get(this.api + '/get_all_experts').then((res) => {
                this.isLoading = false
                this.experts = res.data
            })
        }
    },
    created(){
        this.getExperts()
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

