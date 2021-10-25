<template>
    <div>
       <v-card raised min-height="100" :class="index == 0 ? '' : 'mt-10'">
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
                            <th>Status</th>
                            <td v-if="newStat === 0"><div class="status nyd">Not Decided</div></td>
                            <td v-if="newStat === 1"><div class="status lost">Lost</div></td>
                            <td v-if="newStat === 2"><div class="status won">Won</div></td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
            <v-card-text class="d-flex align-items-start">
                <v-select solo dense small label="Change Status" :items="statuses" item-text="status" item-value="id" class="ml-3" v-model="newStatus"></v-select>
                <v-btn width="30%" dark color="primary rounded darken-2" class="ml-2" @click="changeStatus(fc)" :loading="isBusy">Save</v-btn>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['fc', 'index'],
    data() {
        return {
            statuses: [
                {id: 0, status: 'Not Decided'},
                {id: 1, status: 'Lost'},
                {id: 2, status: 'Won'},
            ],
            newStatus: null,
            isBusy: false,
            newStat: this.fc.status
        }
    },
    computed: {
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
        currentStatus:{
            get(){
                return this.fc
            },
            set(fc){
                this.fc = fc
            }
        }
    },
    methods: {
        changeStatus(fc){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/change_event_status/${fc.id}`, {
                status: this.newStatus
            }, this.adminHeaders)
            .then((res) => {
                this.isBusy = false
                this.newStat = res.data.status
                this.newStatus = null
            })
        },
    }
}
</script>

<style lang="css" scoped>
    .status{
        font-weight: 400;
    }
    .nyd{
        color: #ffa501;
    }
    .lost{
        color: #f3420d;
    }
    .won{
        color: #00b900;
    }
</style>
