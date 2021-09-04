<template>
    <nav v-if="expertIsLoggedIn">
        <v-app-bar flat light color="primary">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="secondary--text hidden-md-and-up" @click="expertDrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 my-2 white--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="font-weight-thin headline headline secondary--text">Surepredict</span>
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
        <v-navigation-drawer v-model="expertDrawer" absolute hide-overlay color="primary white--text" class="" disable-resize-watcher>
            <v-toolbar-title class="white--text ml-4 mt-3 pb-4">
                <router-link to="/" style="cursor: pointer" exact>
                    <span class="font-weight-thin headline headline secondary--text">Surepredict</span>
                </router-link>
            </v-toolbar-title>
            <v-divider></v-divider>
            <v-list class="ml-4">
                <v-list-item dark class="white--text" v-for="item in menus" :key="item.title" link :to="item.path" exact>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
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
                {title: 'Dashboard', path: '/expert'},
                {title: 'New Forecast', path: '/expert/new-forecast'},
                {title: 'My Forecasts', path: '/expert/forecasts'},
                {title: 'Subscriptions', path: '/expert/subscriptions'},
                {title: 'Earnings', path: '/expert/earnings'},
                {title: "Account", path: "/expert/account"},
            ],
            expertDrawer: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        expertHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authExpert.token}`
                }
            }
            return headers
        },
        expertIsLoggedIn(){
            return this.$store.getters.expertIsLoggedIn
        }
    },
    methods: {
        logout(){
            if(this.expertIsLoggedIn){
                this.$store.commit('logOutExpert')
                this.$router.push('/')
                // axios.post(this.api + `/auth-expert/logout`, {}, this.expertHeaders).then(() =>{
                //     this.$store.commit('logOutExpert')
                //     this.$router.push('/')
                // })
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
