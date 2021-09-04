<template>
    <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left :to="{name: 'AdminDailyTips'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Daily Tip" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10">
            <v-col cols="12" md="6">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <template v-if="tipSummary && tipSummary.event_count > 0">
                        <v-card-title class="sub_title primary white--text justify-center">Daily Tips</v-card-title>
                        <v-card-text class="subtitle-2">
                            <template v-if="tips.length > 0">
                                <v-card flat min-height="100" v-for="(fc, index) in tips" :key="fc.id" :class="index == 0 ? '' : 'mt-2'">
                                    <v-card-text>
                                        <table class="table table-condensed table-hover">
                                            <thead></thead>
                                            <tbody>
                                                <tr>
                                                    <th width="35%">ID</th>
                                                    <td>{{ fc.id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Event</th>
                                                    <td>{{ fc.home }} Vs {{ fc.away }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Prediction</th>
                                                    <td>{{ fc.tip }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Odd</th>
                                                    <td>{{ fc.odd }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <td>{{ fc.date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Time</th>
                                                    <td>{{ fc.time }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Event Result:</th>
                                                    <td>{{ fc.result }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Feature Event?</th>
                                                    <td>{{ fc.is_featured ? 'Yes' : 'No' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td v-if="fc.status == 0"><div class="status nyd"></div></td>
                                                    <td v-if="fc.status == 1"><div class="status lost"></div></td>
                                                    <td v-if="fc.status == 2"><div class="status won"></div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </v-card-text>
                                    <!-- <v-card-text class="d-flex text-center mt-n5">
                                        <v-row class="justify-center">
                                            <v-col cols="6">
                                                <v-select solo dense small label="Change Status" :items="statuses" item-text="status" item-value="id" v-model="updateEvent.newStatus"></v-select>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-btn block dark color="primary rounded darken-2" class="ml-2" @click="changeStatus(fc)" :loading="isBusy">Save</v-btn>
                                            </v-col>
                                        </v-row>
                                    </v-card-text> -->
                                    <v-card-actions class="justify-space-around mt-n3 pb-6">
                                        <v-btn dark color="primary darken-2" width="40%" :to="{name: 'AdminDailyTipUpdate', params: {code:$route.params.code, tip: fc.id}}">Update Event</v-btn>
                                        <v-btn text color="red darken-3" width="40%" @click="confirmRemoveEvent(fc, index)"><v-icon>delete_forever</v-icon>Remove Event</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </template>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-alert type="warning" border="left" class="mt-5">
                            The daily tip you are trying to view does not exist.
                        </v-alert>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <template v-if="tipSummary && tipSummary.event_count > 0">
                    <admin-dailytip-summary :summary="tipSummary" showBtns="true" />
                </template>
                <admin-update-dailytip-summary :summary="tipSummary" />
            </v-col>
        </v-row>
        <v-dialog v-model="removeDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete Event?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    Are you sure you want to delete this event from the list?
                </v-card-text>
                <v-card-actions class="pb-5 justify-center">
                    <v-btn text color="red darken--2" @click="removeDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="removeEvent" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- <v-snackbar v-model="changeStatusFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to change event status. Please try again.
            <v-btn text color="white--text" @click="changeStatusFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="statusChanged" :timeout="4000" top color="green darken-1 white--text">
            Status of event has been changed.
            <v-btn text color="white--text" @click="statusChanged = false">Close</v-btn>
        </v-snackbar> -->
        <v-snackbar v-model="eventRemoved" :timeout="4000" top color="green darken-1 white--text">
            An event has been removed from the list.
            <v-btn text color="white--text" @click="eventRemoved = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="eventRmvFail" :timeout="6000" top color="red darken-1 white--text">
            Error occured while trying to remove an event from the list. Please try again.
            <v-btn text color="white--text" @click="eventRmvFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedTip" :timeout="4000" top color="green darken-1 white--text">
            A tip has been updated.
            <v-btn text color="white--text" @click="adminUpdatedTip = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            tipSummary: null,
            tips: [],
            isLoading: false,
            isBusy: false,
            statuses: [
                {id: 0, status: 'Not Decided'},
                {id: 1, status: 'Lost'},
                {id: 2, status: 'Won'},
            ],
            updateEvent: {
                newStatus: null,
                isFeatured: null
            },
            // changeStatusFail: false,
            // statusChanged: false,
            eventToRemove: null,
            eventToRmvindex: null,
            removeDial: false,
            isUpdating: false,
            eventRemoved: false,
            eventRmvFail: false,
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
        adminUpdatedTip(){
            return this.$store.getters.adminUpdatedTip
        }
    },
    methods: {
        getTipSummary(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_daily_tip_summary/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.tipSummary = res.data
                this.tips = res.data.daily_tip
                console.log(res.data)
            })
        },
        // changeStatus(fc){
        //     if(this.updateEvent.newStatus == null){
        //         this.updateEvent.newStatus = fc.status
        //     }
        //     this.updateEvent.isFeatured = fc.is_featured
        //     this.isBusy = true
        //     axios.post(this.api + `/auth-admin/change_daily_tip_status/${fc.id}`, {
        //         status: this.updateEvent
        //     }, this.adminHeaders)
        //     .then((res) => {
        //         this.isBusy = false
        //         let event = this.tips.filter((item)=>item.id == fc.id)
        //         fc.status = res.data.status
        //         this.updateEvent.newStatus = null
        //         this.updateEvent.isFeatured = null
        //         this.statusChanged = true
        //     }).catch(() => {
        //         this.isBusy = false
        //         this.changeStatusFail = true
        //     })
        // },
        confirmRemoveEvent(fc, index){
            this.eventToRemove = fc
            this.eventToRmvindex = index
            this.removeDial = true
        },
        removeEvent(){
            // console.log()
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/remove_event_from_daily_tips/${this.eventToRemove.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isUpdating = false
                this.removeDial = false
                this.eventRemoved = true
                this.tipSummary = res.data
                this.tips.splice(this.eventToRmvindex, 1)
            }).catch(()=>{
                this.isUpdating = false
                this.eventRmvFail = true
            })
        },
    },
    created(){
        this.getTipSummary()
    }
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    .v-card{
        overflow-x: scroll !important;
    }
    .status{
        text-align: center;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    .nyd{
        background: #ffa501;
        border-radius: 1px solid #f59f02;
    }
    .lost{
        background: #f3420d;
        border-radius: 1px solid #f3420d;
    }
    .won{
        background: #00b900;
        border-radius: 1px solid #01a201;
    }
</style>
