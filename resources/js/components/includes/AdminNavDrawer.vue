<template>
    <v-navigation-drawer dense hide-overlay value="true" permanent class="primary darken-4">
        <v-list dark>
            <v-list-item-group>
                <v-list-item class="justify-center py-2">Welcome {{ authAdmin && authAdmin.first_name }}</v-list-item>
                <v-list-item link to="/admin" exact>
                    <v-list-item-icon>
                        <v-icon>dashboard</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>
                        Dashboard
                    </v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <v-divider></v-divider>
        <v-list nav dark>
            <v-list-item-group v-model="item">
                <v-list-item link v-for="(link, i) in items" :key="i" :to="link.path">
                    <v-list-item-icon>
                        <v-icon v-text="link.icon"></v-icon>
                    </v-list-item-icon>

                    <v-list-item-title v-text="link.title">
                    </v-list-item-title>
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
                {title: "Admins", path: "/admin/super-users", icon:"mdi-account-circle"},
                {title: "Experts", path: "/admin/experts", icon:"mdi-account"},
                {title: "users", path: "/admin/users", icon: "mdi-account"},
                {title: "Forecasts", path: "/admin/forecasts-by-experts", icon: "mdi-account"},
                {title: "Countries", path: "/admin/countries", icon: "navigation"},
                {title: "Banks", path: "/admin/banks", icon: "navigation"},
                {title: "Leagues", path: "/admin/leagues", icon: "comments"},
                {title: "Teams", path: "/admin/teams", icon: "storage"},
                {title: "Bookmakers", path: "/admin/bookmakers", icon: "navigation"},
                {title: "Markets", path: "/admin/markets", icon: "navigation"},
                {title: "Enquiries", path: "/admin/enquiries", icon: "email"},
                {title: "PasswordResets", path: "/admin/reset-logs", icon: "lock"},
            ],
            mini: true
        }
    },
    computed:{
        authAdmin(){
            return this.$store.getters.authAdmin
        }
    },
    methods: {

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

