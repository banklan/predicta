<template lang="">
    <v-container>
        <tips-route />
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Won Tips</v-card-title>
                    <v-card-text>
                        <v-simple-table light fixed-header height="400">
                            <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>League</th>
                                    <th>Date</th>
                                    <th>Game</th>
                                    <th>Tip</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tip in wonTips" :key="tip.id">
                                    <td>{{ tip.country }}</td>
                                    <td>{{ tip.league }}</td>
                                    <td>{{ tip.date }}</td>
                                    <td>{{ tip.game }}</td>
                                    <td>{{ tip.tip }}</td>
                                    <td>{{ tip.result }}</td>
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

<script>
export default {
    data() {
        return {
            wonTips: [],
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
            axios.get(this.api + '/get_won_tips').then((res) => {
                this.isLoading = false
                this.wonTips = res.data
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

