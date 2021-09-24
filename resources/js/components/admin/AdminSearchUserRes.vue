<template>
    <v-container>
        <v-row class="mb-n3 mt-1" justify="space-around">
            <v-col cols="3">
                <v-btn dark small rounded color="primary darken-2" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9"></v-col>
        </v-row>
        <v-row class="ml-n10 mt-5">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="users.length > 0">
                        <v-card-title class="sub_title primary white--text justify-center"> Users <v-chip color="primary lighten-2" v-if="users.length > 0">{{ users.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fullname</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="user in users" :key="user.id">
                                        <td @click="showUser(user)">{{ user.id }}</td>
                                        <td @click="showUser(user)">{{ user.fullname }}</td>
                                        <td @click="showUser(user)">{{ user.email }}</td>
                                        <td>{{ user.status ? 'Active' : 'Disabled' }}</td>
                                        <td><v-btn small text color="primary" :to="{name: 'AdminUserUpdate', params:{id: user.id}}"><v-icon>edit</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search returned no users.
                            </v-alert>
                        </v-card-text>
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
            isLoading: false,
            users: []
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
        getResult(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/admin_search_for_users', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.users = res.data
            })
        },
        showUser(user){
            this.$router.push({name: 'AdminUserShow', params:{id: user.id}})
        },
    },
    created() {
        this.getResult()
    },
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
</style>
