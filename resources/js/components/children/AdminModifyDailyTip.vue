<template>
    <div>
        <v-card light raised elevation="8" min-height="150" class="mt-8">
            <v-card-title class="justify-center subtitle-1 white--text primary">Modify Daily Tip</v-card-title>
            <v-card-text class="mt-4" v-if="summary">
                <v-btn text color="primary darken-2" @click="confirmStatusDial = true">{{ summary.is_featured ? 'Un-feature' : 'Feature'}}</v-btn>
            </v-card-text>
            <v-card-text class="mt-n4 mb-5">
                <v-btn text color="red darken-2" @click="confirmDel = true">Delete this tip</v-btn>
            </v-card-text>
        </v-card>
        <v-dialog v-model="confirmDel" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete Daily Tips?</v-card-title>
                <v-card-text class="text-center mt-5 pa-2 subtitle-1">
                    Are you sure you want to delete this tip from the database? All the events in the accumulator will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-5 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDel = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delDailyTip" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmStatusDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center pt-4"><span v-if="summary">{{ summary.is_featured ? 'Un-feature' : 'Feature'}}&nbsp;</span> this tip?</v-card-title>
                <v-card-actions class="mt-8 pb-8 justify-space-around">
                    <v-btn text color="red darken--2" @click="confirmStatusDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="changeStatus" width="50%">Yes, &nbsp;<span v-if="summary">{{ summary.is_featured ? 'Un-feature' : 'Feature' }}</span></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="delDailyTipFail" :timeout="6000" top color="red darken-1 white--text">
            Error occured while trying to delete this daily tips. Please try again.
            <v-btn text color="white--text" @click="delDailyTipFail = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    props: ['summary'],
    data() {
        return {
            confirmDel: false,
            isUpdating: false,
            delDailyTipFail: false,
            confirmStatusDial: false
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
        delDailyTip(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_delete_daily_tips/${this.summary.tip_code}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.$store.commit('dailyTipDeleted')
                this.$router.push({name: 'AdminDailyTips'})
            }).catch(()=>{
                this.isUpdating = false
                this.delDailyTipFail = true
            })
        },
        changeStatus(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/change_daily_tip_is_featured/${this.$route.params.id}`, {},  this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.confirmStatusDial = false
                this.summary.is_featured = res.data.is_featured
                console.log(res.data)
            })
        }
    }
}
</script>
