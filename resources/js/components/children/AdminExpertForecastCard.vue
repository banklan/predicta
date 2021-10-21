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
                            <td v-if="fc.status == 0"><div class="status nyd"></div></td>
                            <td v-if="fc.status == 1"><div class="status lost"></div></td>
                            <td v-if="fc.status == 2"><div class="status won"></div></td>
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
                fc.status = res.data.status
                this.newStatus = null
            })
        },
    }
}
</script>
