<template>
    <nav v-if="userIsLoggedIn">
        <v-app-bar flat light color="primary">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="secondary--text hidden-md-and-up" @click="userDrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 my-2 white--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="font-weight-thin headline headline secondary--text">TipExpats</span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <span class="white--text subtitle-2 mt-1 mr-5">{{ new Date() | moment("Do MMMM, YYYY") }}</span>
            <v-toolbar-items class="hidden-sm-and-down align-center">
                <v-btn class="primary" text v-for="menu in menus" :key="menu.title">
                    <router-link class="primary white--text" :to="menu.path">{{ menu.title }}</router-link>
                </v-btn>
                <v-btn class="primary" text @click="logout">Logout</v-btn>
            </v-toolbar-items>
        </v-app-bar>
        <v-navigation-drawer v-model="userDrawer" absolute hide-overlay color="primary white--text" class="" disable-resize-watcher>
            <v-toolbar-title class="white--text ml-4 mt-3 pb-4">
                <router-link to="/" style="cursor: pointer" exact>
                    <span class="font-weight-thin headline headline secondary--text">TipExpats</span>
                </router-link>
            </v-toolbar-title>
            <v-divider></v-divider>
            <v-list class="ml-4">
                <v-list-item dark class="white--text" v-for="item in menus" :key="item.title" link :to="item.path" exact>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item dark class="white--text"  @click="logout">
                    <v-list-item-title>Logout</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            menus: [
                {title: 'Dashboard', path: '/dashboard'},
                {title: 'My Subscriptions', path: '/subscriptions'},
                {title: "Account", path: "/user-account"},
                {title: "Feedback", path: "/user-feedback"},
            ],
            userDrawer: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        }
    },
    methods: {
        logout(){
            if(this.userIsLoggedIn){
                axios.post(this.api + `/auth/logout`, {}, this.authHeaders).then(() =>{
                    this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
        }
    },
    created() {
        // console.log(this.authUser)
    },
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none;
        text-transform:capitalize;
    }
    a.router-link-exact-active{
        color: #fbff05 !important;
    }
</style>
