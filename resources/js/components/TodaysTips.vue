<template lang="">
    <v-container>
        <tips-route />
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Today's Tips {{ tipsDate | moment('DD/MM/YY') }}</v-card-title>
                    <v-card-text>
                        <v-simple-table light fixed-header height="250">
                            <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>League</th>
                                    <th>Time</th>
                                    <th>Home</th>
                                    <th>Away</th>
                                    <th>Tip</th>
                                    <th>Odd</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tip in todaysTips" :key="tip.id">
                                    <td>{{ tip.country }}</td>
                                    <td>{{ tip.league }}</td>
                                    <td>{{ tip.time }}</td>
                                    <td>{{ tip.home }}</td>
                                    <td>{{ tip.away }}</td>
                                    <td>{{ tip.tip }}</td>
                                    <td>{{ tip.odd }}</td>
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
            todaysTips: [],
            isLoading: false,
            tipsDate: null
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getTodaysTips(){
            this.isLoading = true
            axios.get(this.api + '/get_todays_tips').then((res) => {
                this.isLoading = false
                this.todaysTips = res.data.tips
                this.tipsDate = res.data.tipDate
            })
        },
    },
    created(){
        this.getTodaysTips()
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

