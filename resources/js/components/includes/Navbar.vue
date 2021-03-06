<template>
    <nav>
        <template v-if="expertIsLoggedIn">
            <expert-navbar></expert-navbar>
        </template>
        <template v-if="userIsLoggedIn">
            <auth-navbar />
        </template>
        <template v-if="!expertIsLoggedIn && !userIsLoggedIn">
            <v-app-bar flat light color="primary">
                <span class="hidden-md-and-up" v-if="!expertIsLoggedIn && !userIsLoggedIn && !adminIsLoggedIn">
                    <v-app-bar-nav-icon class="secondary--text hidden-md-and-up" @click="notLoggedInDrawer = true"></v-app-bar-nav-icon>
                </span>
                <span class="hidden-md-and-up" v-if="adminIsLoggedIn">
                    <v-app-bar-nav-icon class="secondary--text hidden-md-and-up" @click="adminLoggedInHomeDrawer = true"></v-app-bar-nav-icon>
                </span>
                <v-toolbar-title class="ml-5 my-2 white--text">
                    <router-link to="/" style="cursor:pointer" exact>
                        <span class="font-weight-thin headline headline secondary--text">TipExpats</span>
                    </router-link>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <span class="white--text subtitle-2 mt-1 mr-5">{{ new Date() | moment("Do MMMM, YYYY") }}</span>
                <v-toolbar-items class="hidden-sm-and-down align-center">
                    <template v-if="adminIsLoggedIn">
                        <v-btn class="primary" text v-for="menu in adminMenu" :key="menu.title">
                            <router-link class="primary white--text" :to="menu.path">{{ menu.title }}</router-link>
                        </v-btn>
                        <v-btn class="primary" text @click="logout">Logout</v-btn>
                    </template>
                    <template v-else>
                        <v-btn class="primary" text v-for="menu in menus" :key="menu.title">
                            <router-link class="primary white--text" :to="menu.path">{{ menu.title }}</router-link>
                        </v-btn>
                    </template>
                </v-toolbar-items>
            </v-app-bar>
            <template v-if="adminIsLoggedIn">
                <v-navigation-drawer absolute v-model="adminLoggedInHomeDrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
                    <v-toolbar-title class="white--text ml-4 mt-3 pb-4">
                        <router-link to="/" style="cursor: pointer" exact>
                            <span>TipExpats</span>
                        </router-link>
                    </v-toolbar-title>
                    <v-divider></v-divider>
                    <v-list class="ml-4">
                        <template>
                            <v-list-item dark class="white--text" v-for="item in adminMenu" :key="item.title" link :to="item.path">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item dark class="white--text"  @click="adminLogout">
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item>
                        </template>
                    </v-list>
                </v-navigation-drawer>
            </template>
            <template v-else>
                <v-navigation-drawer absolute v-model="notLoggedInDrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
                    <v-toolbar-title class="white--text ml-4 mt-3 pb-4">
                        <router-link to="/" style="cursor: pointer" exact>
                            <span>TipExpats</span>
                        </router-link>
                    </v-toolbar-title>
                    <v-divider></v-divider>
                    <v-list class="ml-4">
                        <template v-if="!expertIsLoggedIn && !userIsLoggedIn && !adminIsLoggedIn">
                            <v-list-item dark class="white--text" v-for="item in menus" :key="item.title" link :to="item.path">
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </template>
                    </v-list>
                </v-navigation-drawer>
            </template>
        </template>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            menus: [
                { title: "About Us", path: "/about-us"},
                { title: "Contact Us", path: "/contact-us"},
                { title: "Experts", path: "/expert-login"},
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
            ],
            adminMenu: [
                {title: 'Dashboard', path: '/admin'},
            ],
            nonAuthMenu: [
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
            ],
            expertAuthMenuItems:[
                {title: 'Dashboard', path: '/admin'},
                {title: "Account", path: "/admin/account"},
            ],
            expertDrawer: false,
            notLoggedInDrawer: false,
            adminLoggedInHomeDrawer: false
        }
    },
    computed: {
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        adminIsLoggedIn(){
            return this.$store.getters.adminIsLoggedIn
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
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
            if(this.authUser){
                axios.post(this.api + `/logout`, {}, {
                    headers: {
                        "Authorization": `Bearer ${this.authUser.token}`
                    }
                }).then(() =>{
                    this.$store.commit('logOut')
                    this.$router.push('/')
                })
            }else if(this.authAdmin){
                axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() => {
                    this.$store.commit('logOutAdmin')
                    this.$router.push('/')
                })
            }
            this.$router.push('/')
        },
        adminLogout(){
            axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() => {
                this.$store.commit('logOutAdmin')
                this.$router.push('/')
            })
        }
    },
    created() {
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
