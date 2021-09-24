<template>
    <v-container>
        <v-row class="mb-n3 mt-1" justify="space-around">
            <v-col cols="3">
                <v-btn dark small rounded color="primary darken-2" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9"></v-col>
        </v-row>
        <v-row class="ml-n10 mt-5">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="tips.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Daily Tips <v-chip color="primary lighten-2" v-if="tips.length > 0">{{ tips.length }}</v-chip></span></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th># of events</th>
                                        <th>Status(%)</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(tip, i) in tips" :key="tip.id">
                                        <td @click="showDailyTip(tip)">{{ tip.created_at | moment('D/MM/YY') }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.tip_code }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.event_count }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.success }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search returned no tip.
                            </v-alert>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            tips: []
        }
    },
    computed:{
         authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getResult(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/admin_search_for_daily_tips', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.tips = res.data
            })
        },
        showDailyTip(tip){
            this.$router.push({name: 'AdminDailyTipShow', params: {id: tip.id, code: tip.tip_code}})
        },
    },
    created() {
        this.getResult()
    },
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
</style>
