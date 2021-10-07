<template>
   <v-container>
        <admin-top-panel title="Daily Tips" />
        <v-row :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
           <v-col cols="12" md="5" class="mb-sm-n10">
               <admin-search model="DailyTips" searchFor="daily tips"/>
           </v-col>
           <v-col cols="12" md="4" offset-md="3" :class="$vuetify.breakpoint.smAndDown ? 'mt-n7':''">
               <v-btn dark  color="primary" :to="{name: 'AdminCreateDailyTips'}"><v-icon left>add</v-icon>New Daily Tip</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="sub_title primary white--text justify-center">Daily Tips</v-card-title>
                    <v-card-text class="subtitle-2">
                        <template v-if="total > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th># of events</th>
                                        <th>Status(%)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tip, i) in dailyTips" :key="tip.id">
                                        <td @click="showDailyTip(tip)">{{ tip.created_at | moment('D/MM/YY') }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.tip_code }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.event_count }}</td>
                                        <td @click="showDailyTip(tip)">{{ tip.success }}</td>
                                        <td><v-btn small text color="red darken-2" @click="confirmDel(tip, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                You currently have no daily tips in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getDailyTips(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getDailyTips(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getDailyTips(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getDailyTips(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete this daily Tip?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    Are you sure you want to delete this daily tip? All the events associated with it will be irrecoverably deleted.                </v-card-text>
                <v-card-actions class="pb-5 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="deleteTip" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="dailyTipDeleted" :timeout="4000" top color="green darken-1 white--text">
            A daily tip has been removed.
            <v-btn text color="white--text" @click="dailyTipDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="dailyTipDelError" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to delete the daily tip. Please try again.
            <v-btn text color="white--text" @click="dailyTipDelError = false">Close</v-btn>
        </v-snackbar>
   </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            dailyTips: [],
            total: 0,
            pagination: {},
            confirmDelDial: false,
            isUpdating: false,
            tipIndexToDel: null,
            tipToDel: null,
            dailyTipDelError: false
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
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
        dailyTipDeleted(){
            return this.$store.getters.dailyTipDeleted
        }
    },
    methods: {
        getDailyTips(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_daily_tips`
            axios.get(pag, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.dailyTips = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        showDailyTip(tip){
            this.$router.push({name: 'AdminDailyTipShow', params: {id: tip.id, code: tip.tip_code}})
        },
        confirmDel(tip, i){
            this.tipToDel = tip
            this.tipIndexToDel = i
            this.confirmDelDial = true
        },
        deleteTip(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_delete_daily_tip_summary/${this.tipToDel.tip_code}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.confirmDelDial = false
                this.dailyTips.splice(this.tipIndexToDel, 1)
                this.$store.commit('dailyTipDeleted')
            }).catch((err) => {
                this.isUpdating = false
                this.dailyTipDelError = true
            })
        }
    },
    created(){
        this.getDailyTips()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
</style>
