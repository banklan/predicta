<template>
    <v-app>
        <Navbar />
        <div v-if="authAdmin && $route.path.substring(0, 6) == '/admin'">
            <v-row>
                <v-col cols="4" md="3">
                    <AdminNavDrawer />
                </v-col>
                <v-col cols="8" md="9">
                    <v-row justify="start" class="">
                        <v-col cols="12">
                            <router-view></router-view>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </div>
        <template v-else>
            <router-view></router-view>
        </template>
    </v-app>
</template>

<script>
import Navbar from './includes/Navbar'
import AdminNavDrawer from './includes/AdminNavDrawer'
export default {
    components: {
        Navbar, AdminNavDrawer
    },
    data() {
        return {

        }
    },
    computed:{
        authAdmin(){
            return this.$store.getters.authAdmin
        }
    },
}
</script>
