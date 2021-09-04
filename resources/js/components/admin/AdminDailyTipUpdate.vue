<template>
     <v-container>
        <v-row justify="space-between">
            <v-col cols="12" md="1">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="11">
                <admin-top-panel title="Daily-Tip Update" />
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-4 ml-n10">
            <v-col cols="12" md="7">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <template v-if="tip">
                        <v-card-title class="sub_title primary white--text justify-center">Daily Tip Update</v-card-title>
                        <v-card-text class="subtitle-2 mb-n8">
                            <table class="table table-condensed table-hover">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="35%">ID</th>
                                        <td>{{ tip.id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event</th>
                                        <td>{{ tip.home }} Vs {{ tip.away }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prediction</th>
                                        <td>{{ tip.tip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Odd</th>
                                        <td>{{ tip.odd }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date/Time</th>
                                        <td>{{ tip.date }} - {{ tip.time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td v-if="tip.status == 0"><div class="status nyd"></div></td>
                                        <td v-if="tip.status == 1"><div class="status lost"></div></td>
                                        <td v-if="tip.status == 2"><div class="status won"></div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-text class="mt-n5">
                           <div class="text-center subtitle-1 font-weight-bold mb-5 mt-3">Update</div>
                           <div class="d-flex mx-5 px-1">
                               <div class="mt-2 mr-5">Result</div>
                               <v-text-field class="mr-3" dense placeholder="Home" v-model="update.home"></v-text-field>
                               <v-text-field class="ml-3" dense placeholder="Away" v-model="update.away"></v-text-field>
                           </div>
                           <div class="d-flex mx-5 px-1">
                               <div class="mt-2 mr-3">Tip Status</div>
                               <v-select dense :items="statuses" item-text="status" item-value="id" v-model="update.status"></v-select>
                           </div>
                           <div class="d-flex mx-5 px-1">
                               <div class="mt-2 mr-3">Feature Tip?</div>
                               <v-select dense :items="feature" item-text="value" item-value="value" v-model="update.feature"></v-select>
                           </div>
                        </v-card-text>
                        <v-card-actions class="justify-center mb-8">
                            <v-btn width="80%" large dark color="primary darken-2" :loading="isSaving" @click="updateTip">Save Updates</v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-alert type="info" class="mt-5">
                            The tip you are trying to view is invalid or does not exist.
                        </v-alert>
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
            id: this.$route.params.tip,
            summary: this.$route.params.code,
            tip: null,
            summary: null,
            isLoading: false,
            statuses: [
                {id: 0, status: 'Not Decided'},
                {id: 1, status: 'Lost'},
                {id: 2, status: 'Won'},
            ],
            feature: [
                {value: true},
                {value: false},
            ],
            update: {
                home: '',
                away: '',
                status: null,
                feature: null,
            },
            isSaving: false
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
        getTip(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_single_daily_tip/${this.$route.params.code}/${this.$route.params.tip}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.tip = res.data
                this.update.status = res.data.status
                this.update.is_featured = res.data.is_featured
            })
        },
        getTipSummary(){
            axios.get(this.api + `/auth-admin/admin_get_daily_tip_summary_with_code/${this.$route.params.code}`, this.adminHeaders)
            .then((res) => {
                this.summary = res.data
            })
        },
        updateTip(){
            this.isSaving = true
            axios.post(this.api + `/auth-admin/admin_update_daily_tip/${this.$route.params.tip}`, {
                update: this.update
            }, this.adminHeaders)
            .then((res) => {
                this.isSaving = false
                this.$store.commit('adminUpdatedTip')
                this.$router.push({name: 'AdminDailyTipShow', params: {id: this.summary.id, code: this.summary.tip_code}})
            })
        }
    },
    created(){
        this.getTip()
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

