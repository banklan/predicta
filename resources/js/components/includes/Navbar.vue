<template>
    <nav>
        <template v-if="expertIsLoggedIn">
            <expert-navbar></expert-navbar>
        </template>
        <template v-if="userIsLoggedIn">
            <auth-navbar />
        </template>
        <template v-else>
            <v-app-bar flat light color="primary">
                <v-toolbar-title class="ml-5 my-2 white--text">
                    <router-link to="/" style="cursor:pointer" exact>
                        <span class="font-weight-thin headline headline secondary--text">Surepredict</span>
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
            <v-navigation-drawer absolute v-model="expertDrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
                <v-toolbar-title class="white--text ml-4 mt-3 pb-4">
                    <router-link to="/" style="cursor: pointer" exact>
                        <span>surePredict</span>
                    </router-link>
                </v-toolbar-title>
                <v-divider></v-divider>
                <v-list class="ml-4">
                    <template v-if="expertIsLoggedIn">
                        <v-list-item dark class="white--text" v-for="item in expertAuthMenuItems" :key="item.title" link :to="item.path">
                            <v-list-item-content>
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item dark class="white--text">
                            <button type="button" class="white--text" @click="logout">Logout</button>
                        </v-list-item>
                    </template>
            <!-- <template v-if>
                <v-list-item dark class="white--text" v-for="item in nonAuthMenuItems" :key="item.title" link :to="item.path">
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template> -->
        </v-list>
            </v-navigation-drawer>
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
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
            ],
            adminMenu: [
                {title: 'Dashboard', path: '/admin'},
                {title: "Account", path: "/admin/account"},
            ],
            nonAuthMenu: [
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
            ],
            expertAuthMenuItems:[
                {title: 'Dashboard', path: '/admin'},
                {title: "Account", path: "/admin/account"},
            ],
            expertDrawer: false
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
