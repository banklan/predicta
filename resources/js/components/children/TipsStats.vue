<template>
    <div class="tips_starts">
        <v-row>
            <v-col cols="12" md="4">
                <v-card raised elevation="14" min-height="200" color="primary darken-2">
                    <v-card-title class="justify-center pt-3">
                        <v-icon x-large color="#fff">insert_chart</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center white--text">
                        <div class="headline font-weight-thin pa-2">{{ active }}</div>
                        <div>Active Expert tips</div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="experts > 0">
                        <v-btn outlined color="white" large :to="{name: 'AllTipExperts'}">View Tip Experts</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card raised elevation="14" min-height="200" color="primary darken-2">
                    <v-card-title class="justify-center pt-3">
                        <v-icon x-large color="#fff">supervisor_account</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center white--text">
                        <div class="headline font-weight-thin pa-2">{{ experts }}</div>
                        <div>Tip Experts</div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="experts > 0">
                        <v-btn outlined color="white" large :to="{name: 'AllTipExperts'}">View Tip experts</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card raised elevation="14" min-height="200" color="primary darken-2">
                    <v-card-title class="justify-center pt-3">
                        <v-icon x-large color="#fff">done_all</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center white--text">
                        <div class="headline font-weight-thin pa-2">{{ won }}</div>
                        <div>Won Forecasts</div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="won > 0">
                        <v-btn outlined color="white" large :to="{name: 'WonExpertsForecasts'}">View Won Forecasts</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            won: null,
            active: null,
            experts: null
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getStats(){
            axios.get(this.api + '/get_forecasts_brief_starts').then((res) => {
                let won = res.data.filter(item => item.prog_status == 2)
                this.won = won.length
                let active = res.data.filter(item => item.prog_status == 0)
                this.active = active.length
                // console.log(res.data)
            })
        },
        getExperts(){
            axios.get(this.api + '/get_all_experts').then((res)=>{
                this.experts = res.data.length
            })
        }
    },
    created() {
        this.getStats()
        this.getExperts()
    },
}
</script>
