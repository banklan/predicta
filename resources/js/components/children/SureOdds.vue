<template>
    <div class="sureodds">
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-card v-else light raised elevation="8" min-height="200">
            <v-card-title class="sub_title primary white--text justify-center">Free Daily Tips &nbsp;<span v-if="tips.length > 0">{{ tipDate | moment('DD/MM/YY')  }}</span> </v-card-title>
            <v-card-text>
                <template v-if="tips.length > 0">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr class="subtitle-2">
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
                            <tr class="subtitle-2" v-for="tip in tips" :key="tip.id">
                                <td>{{ tip.country }}</td>
                                <td>{{ tip.league }}</td>
                                <td>{{ tip.time }}</td>
                                <td>{{ tip.home }}</td>
                                <td>{{ tip.away }}</td>
                                <td>{{ tip.tip }}</td>
                                <td>{{ tip.odd }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="mt-5">
                        There are no daily tips in the database yet.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-5">
                <v-btn large text color="primary darken-2" :to="{name: 'TodaysTips'}">View All Today's Tips</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tips: [],
            tipDate: null,
            isLoading: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getFeaturedDailyTips(){
            this.isLoading = true
            axios.get(this.api + '/get_featured_daily_tips').then((res) =>{
                this.isLoading = false
                this.tips = res.data.tips
                this.tipDate = res.data.tipDate
                // console.log(res.data)
            })
        }
    },
    created() {
        this.getFeaturedDailyTips()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
