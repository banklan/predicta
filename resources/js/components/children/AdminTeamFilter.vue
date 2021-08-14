<template>
    <div>
        <v-select label="Filter Leagues" :items="leagues" item-text="league" item-value="id" v-model="league" required outlined dense @change="filterLeague"></v-select>
    </div>
</template>

<script>
export default {
    props: ['leagues'],
    data() {
        return {
            team: '',
            league: null
        }
    },
    methods: {
        filterLeague(){
            this.$router.push({name: 'AdminFilterTeams', query:{league: this.league}})
        }
    }
}
</script>
