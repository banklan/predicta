<template>
    <div class="won_tips">
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-card v-else light raised elevation="8" min-height="200">
            <v-card-title class="sub_title primary white--text justify-center">Won Tips </v-card-title>
            <v-card-text>
                <template v-if="tips.length > 0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="">
                                <th>Date</th>
                                <th>League</th>
                                <th>Event</th>
                                <th>Tip</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="tip in tips" :key="tip.id">
                                <td>{{ tip.date }}</td>
                                <td>{{ tip.league }}</td>
                                <td>{{ tip.game }}</td>
                                <td>{{ tip.tip }}</td>
                                <td>{{ tip.result }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="mt-5">
                        There are no won tips to view yet.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-6">
                <v-btn text color="primary darken-2" :to="{name: 'DailyWonTips'}">View All Won Tips</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tips: [],
            isLoading: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getBriefWonTips(){
            this.isLoading = true
            axios.get(this.api + '/get_brief_won_daily_tips').then((res) =>{
                this.isLoading = false
                this.tips = res.data
                console.log(res.data)
            })
        }
    },
    created() {
        this.getBriefWonTips()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
