<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Add New Tip" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10 pr-3">
            <v-col cols="12" md="7">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="justify-center primary white--text sub_title">Add New Tip</v-card-title>
                    <v-card-text class="mt-5 px-10">
                        <v-select :items="eventTypes" item-text="type" return-object label="Select Game Type" v-model="event" required></v-select>
                        <template v-if="event.id === 1">
                            <admin-new-club-tip />
                        </template>
                        <template v-else>
                            <create-daily-tips-international />
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <admin-dailytip-summary :summary="tipSummary" :showBtns="false" />
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            tipSummary: null,
            eventTypes: [
                {id:1, type: 'Club'},
                {id:2, type: 'International'},
            ],
            event: {
                id: 1,
                type: 'Club'
            },
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
        newTipAdded(){
            if(this.$store.getters.tipAddedToDailyTips){
                this.tipSummary.event_count++
            }
        }
    },
    methods: {
        getTipSummary(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_daily_tip_summary/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.tipSummary = res.data
            })
        },
    },
    created() {
        this.getTipSummary()
    },
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>


