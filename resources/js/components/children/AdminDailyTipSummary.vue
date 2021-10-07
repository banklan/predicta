<template>
    <div class="summary">
        <v-card light raised elevation="8" min-height="200" class="scroll">
            <v-card-title class="subtitle-1 primary white--text justify-center">Daily Tip Summary <span v-if="summary" class="ml-2">({{ summary.daily_tip.length > 0 && summary.daily_tip[0].event_date | moment('D/M/Y')}}) </span></v-card-title>
            <v-card-text class="subtitle-2">
                <table class="table table-striped table-hover">
                    <thead></thead>
                    <tbody v-if="summary">
                        <tr >
                            <th>Created By:</th>
                            <td>{{ summary.admin.first_name }} ({{ summary.admin.admin_role}})</td>
                        </tr>
                        <tr>
                            <th>Published:</th>
                            <td>{{ summary.published }}</td>
                        </tr>
                        <tr>
                            <th>Event Date:</th>
                            <td v-if="summary">{{ summary.daily_tip.length > 0 && summary.daily_tip[0].event_date | moment('D/M/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tip Code:</th>
                            <td>{{ summary.tip_code }}</td>
                        </tr>
                        <tr>
                            <th>No of Events:</th>
                            <td>{{ summary.event_count }}</td>
                        </tr>
                        <tr>
                            <th>Feature Status:</th>
                            <td>{{ summary.is_featured ? 'Featured' : 'Not Featured' }}</td>
                        </tr>
                        <tr>
                            <th>% of Success:</th>
                            <td>{{ summary.success }}%</td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
            <v-card-actions class="justify-space-around mt-2 pb-8" v-if="showBtns">
                <v-btn large width="60%" color="primary darken-2" :to="{name: 'AdminDailyTipAddNew'}">Add New Tip</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['summary', 'showBtns'],
}
</script>

<style lang="css" scoped>
    table tbody tr td{
        white-space: nowrap !important;
    }
</style>
