<template>
    <v-container>
        <v-row justify="center" class="mt-1 mr-10">
            <v-col cols="12" md="4">
                <v-select :label="`Filter ${filterFor}`" :items="filter" item-text="league" item-value="id" v-model="sel" required outlined dense @change="filterHandle"></v-select>
            </v-col>
            <v-col cols="12" md="5" class="">
                <v-text-field :placeholder="`Search for ${searchFor}..`" v-model="find" append-icon="search" clearable dense outlined></v-text-field>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: ['filter', 'search', 'searchFor', 'filterFor', 'filterModel', 'filterWith'],
    data() {
        return {
            find: '',
            sel: null
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
        filterHandle(){
            // console.log(this.sel)
            // console.log(this.filterWith)
            this.$router.push({name: 'AdminFilterResult', query: {q: this.sel, m: this.filterModel, c: this.filterWith}})

        }
    }
}
</script>
