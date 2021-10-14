<template>
    <v-app>
        <Navbar></Navbar>
        <div v-if="authAdmin && $route.path.substring(0, 6) == '/admin'">
            <v-row>
                <v-col cols="4" md="2">
                    <AdminNavDrawer></AdminNavDrawer>
                </v-col>
                <v-col cols="8" md="10">
                    <v-row justify="start">
                        <v-col cols="12">
                            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                                <router-view></router-view>
                            </transition>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </div>
        <template v-else>
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <Footer></Footer>
    </v-app>
</template>

<script>
import Navbar from './includes/Navbar'
import AdminNavDrawer from './includes/AdminNavDrawer'
import Footer from './includes/Footer'
export default {
    components: {
        Navbar, AdminNavDrawer, Footer
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
