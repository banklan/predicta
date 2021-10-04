<template>
    <v-navigation-drawer dense hide-overlay value="true" permanent class="primary darken-4">
        <v-list dark>
            <v-list-item-group>
                <v-list-item class="justify-center py-2 body-2">Welcome {{ authAdmin && authAdmin.first_name }}</v-list-item>
                <v-list-item link to="/admin" exact>
                    <v-list-item-icon>
                        <v-icon>dashboard</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title class="body-2">
                        Dashboard
                    </v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <!-- <v-divider></v-divider> -->
        <v-list nav dark class="mt-n3">
            <v-list-item-group v-model="item">
                <v-list-item link v-for="(link, i) in items" :key="i" :to="link.path" class="">
                    <v-list-item-icon>
                        <v-icon v-text="link.icon"></v-icon>
                    </v-list-item-icon>

                    <v-list-item-title v-text="link.title" class="body-2">
                    </v-list-item-title>
                </v-list-item>
                <v-list-item  @click="logout">
                    <v-list-item-icon>
                        <v-icon @click="logout">logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title class="body-2">Logout</v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    data(){
        return{
            item: 0,
            items: [
                {title: "Admins", path: "/admin/super-users", icon:"supervisor_account"},
                {title: "Daily Tips", path: "/admin/daily-tips", icon:"today"},
                {title: "Experts", path: "/admin/experts", icon:"mdi-account-circle"},
                {title: "Users", path: "/admin/users", icon: "mdi-account"},
                {title: "Forecasts", path: "/admin/forecasts-by-experts", icon: "view_list"},
                {title: "Subscriptions", path: "/admin/subscriptions", icon: "receipt"},
                {title: "Countries", path: "/admin/countries", icon: "flag"},
                {title: "Banks", path: "/admin/banks", icon: "account_balance"},
                {title: "Leagues", path: "/admin/leagues", icon: "sports_soccer"},
                {title: "League Teams", path: "/admin/teams", icon: "sports_soccer"},
                {title: "National Teams", path: "/admin/national-teams", icon: "flag"},
                {title: "Bookmakers", path: "/admin/bookmakers", icon: "store"},
                {title: "Markets", path: "/admin/markets", icon: "toc"},
                {title: "Plans", path: "/admin/plans", icon: "category"},
                {title: "Payments", path: "/admin/payments", icon: "credit_card"},
                {title: "Earnings", path: "/admin/earnings", icon: "account_balance_wallet"},
                {title: "Follows", path: "/admin/follows", icon: "person_add"},
                {title: "Feedbacks", path: "/admin/feedbacks", icon: "feedback"},
                {title: "Enquiries", path: "/admin/enquiries", icon: "email"},
                {title: "Mailing List", path: "/admin/mailing-list", icon: "email"},
                {title: "Mailed Daily Tips", path: "/admin/mailed-daily-tips", icon: "email"},
                {title: "Profile", path: "/admin/profile", icon: "lock"},
                // {title: "Logout", path: "/admin/profile", icon: "exit"},
            ],
            mini: true
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        authAdmin(){
            return this.$store.getters.authAdmin
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
        logout(){
            axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() => {
                this.$store.commit('logOutAdmin')
                this.$router.push('/')
            })
        }
    },
    mounted() {

    },
}
</script>

<style lang="scss" scoped>
    .v-navigation-drawer__content .v-list{
        a:hover{
            text-decoration: none !important;
        }
    }
</style>

