<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="12" md="3">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <admin-top-panel title="Create Daily Tips" />
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n10" justify="space-around">
            <v-col cols="12" md="5">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center">Create Daily Tip</v-card-title>
                    <v-card-text class="body-1 mt-5 px-8">
                        <v-select :items="eventTypes" item-text="type" return-object label="Select Game Type" v-model="event" required></v-select>
                        <template v-if="event.id === 1">
                            <create-daily-tips-club />
                        </template>
                        <template v-else>
                            <create-daily-tips-international />
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="7">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="sub_title primary white--text justify-center mx-auto">Forecast <v-chip color="primary lighten-2" v-if="dailyTips.length > 0" class="ml-1">{{ dailyTips.length }}</v-chip></v-card-title>
                    <v-card-text class="caption px-8">
                        <template v-if="dailyTips.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>League</th>
                                        <th>Game</th>
                                        <th>Tip</th>
                                        <th>Odd</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tip, index) in dailyTips" :key="index">
                                        <td>{{ tip.date |  moment('D/MM/YY')}} - {{ tip.time }}</td>
                                        <td>{{ tip.league }}</td>
                                        <td>{{ tip.home }} Vs {{ tip.away }}</td>
                                        <td>{{ tip.tip }}</td>
                                        <td>{{ tip.odd }}</td>
                                        <td><v-btn x-small text color="red darken-2" @click="openDelDial(index)"><v-icon small>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="tip_date mt-8">
                                <div class="text-center subtitle-1">Select Tip Date</div>
                                <v-menu ref="datePicker" v-model="datePicker" :close-on-content-click="false" :return-value.sync="tipDate" transition="scale-transition" offset-y min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="tipDate" label="Event Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="tipDate" color="primary" elevation="4" scrollable :allowed-dates="disablePastDates" >
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="red darken-2" @click="datePicker = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.datePicker.save(tipDate)">Ok</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </div>
                        </template>
                        <template v-else>
                            <v-alert class="mt-5" type="info" border="left">
                                You have not created any tips.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-7">
                        <v-btn large width="30%" text color="red darken-2">Clear</v-btn>
                        <v-btn large width="30%" color="primary darken-2" @click="submit" :loading="isBusy">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="removeDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Delete Event?</v-card-title>
                <v-card-text class="text-center mt-5 body_text">
                    Are you sure you want to delete this event from the list?
                </v-card-text>
                <v-card-actions class="pb-5 space-around">
                    <v-btn text color="red darken--2" @click="removeDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="removeTip" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="dailyTipCreateError" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to submit daily tips. Please review the list before trying again.
            <v-btn text color="white--text" @click="dailyTipCreateError = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            eventTypes: [
                {id:1, type: 'Club'},
                {id:2, type: 'International'},
            ],
            event: {
                id: 1,
                type: 'Club'
            },
            isUpdating: false,
            tipToRemoveIndex: null,
            removeDial: false,
            isBusy: false,
            submitSuccess: false,
            dailyTipCreateError: false,
            date: new Date().toISOString().substr(0, 10),
            datePicker: false,
            tipDate: new Date().toISOString().substr(0, 10),
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
        dailyTips(){
            return this.$store.getters.getDailyTips
        }
    },
    methods: {
        openDelDial(index){
            this.tipToRemoveIndex = index
            this.removeDial = true
        },
        removeTip(){
            this.isUpdating = true
            let index = this.tipToRemoveIndex
            this.$store.commit('removeDailyTip', index)
            this.isUpdating = false
            this.removeDial = false
        },
        submit(){
            this.isBusy = true
            axios.post(this.api + '/auth-admin/create_daily_tips', {
                tips: this.dailyTips,
                tipDate: this.tipDate
            }, this.adminHeaders).then((res)=>{
                console.log(res.data)
                this.isBusy = false
                this.$store.commit('newDaiyTipCreated')
                this.$store.commit('clearDailyTip')
                this.$router.push({name: 'AdminDailyTips'})
            }).catch((err) => {
                console.log(err)
                this.isBusy = false
                this.dailyTipCreateError = true
            })
        },
        disablePastDates(val) {
            return val >= new Date().toISOString().substr(0, 10)
        },
    }
}
</script>

<style lang="css" scoped>
    .v-card{
        overflow-x: scroll !important;
    }
</style>
