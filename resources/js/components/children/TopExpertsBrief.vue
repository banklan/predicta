<template>
    <div class="won_tips">
        <v-progress-circular indeterminate color="primary" :width="5" :size="50" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-card v-else light raised elevation="8" min-height="200">
            <v-card-title class="sub_title primary white--text justify-center">Our Top Experts </v-card-title>
            <v-card-text>
                <template v-if="experts.length > 0">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr class="caption" style="text-align:center">
                                <th>Expert</th>
                                <th>Win Rate(%)</th>
                                <th>Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="exp in experts"  :key="exp.id" style="text-align:center">
                                <td>{{ exp.username }}</td>
                                <td>{{ exp.winning_rate }}%</td>
                                <td><v-btn small text color="primary" :to="{name: 'TipExpertView', params: {id: exp.expert_id}}">Access</v-btn></td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <v-alert type="info" border="left" class="mt-5">
                        There are no experts to view yet.
                    </v-alert>
                </template>
            </v-card-text>
            <v-card-actions class="justify-center pb-6 mt-2">
                <v-btn text color="primary-darken-2" :to="{name: 'AllTipExperts'}">View All Tip-Experts</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            experts: [],
            isLoading: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getTopExperts(){
            this.isLoading = true
            axios.get(this.api + '/get_top_experts').then((res) =>{
                this.isLoading = false
                this.experts = res.data
                console.log('top experts =>', res.data)
            })
        }
    },
    created() {
        this.getTopExperts()
    },
}
</script>

<style lang="css" scoped>
    .v-card, .v-card__text{
        overflow-x: scroll !important;
    }
</style>
