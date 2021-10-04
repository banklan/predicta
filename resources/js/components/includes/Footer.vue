<template>
    <div class="footer pt-5" color="#fbff05" light>
        <v-container min-height="400">
            <v-row align="start" justify="center">
                <v-col cols="12" md="4">
                    <div class="col_wrap">
                        <p class="white--text">Quick Links</p>
                        <ul class="">
                            <li><router-link to="/">Home</router-link></li>
                            <li><router-link to="/todays-tips">Today's Tips</router-link></li>
                            <li><router-link to="/about-us">About Us</router-link></li>
                            <li><router-link to="/contact-us">Contact Us</router-link></li>
                        </ul>
                    </div>
                </v-col>
                <v-col cols="12" md="4">
                    <div class="col_wrap">
                        <p class="white--text">Quick Links</p>
                        <ul>
                            <li><router-link to="/tip-experts">Tip Experts </router-link></li>
                            <li><router-link to="/login">User Login </router-link></li>
                            <li><router-link to="/register">User Register </router-link></li>
                            <li><router-link to="/expert-login">Expert Login</router-link></li>
                        </ul>
                    </div>
                </v-col>
                <v-col cols="12" md="4">
                    <div class="col_wrap">
                        <p class="white--text ml-n16">Extras</p>
                        <ul>
                            <li><router-link to="/advert">Promote Your Service</router-link></li>
                            <li><router-link to="/faq">FAQ</router-link></li>
                            <li><router-link to="/terms-conditions">Terms & Conditions</router-link></li>
                            <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
                            <li><router-link to="/join-mailing-list">Receive Daily Sure Bets</router-link></li>
                        </ul>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <v-row justify="center" class="base">
            <v-col cols="6" class="text-center">
                <v-btn large v-for="icon in icons" :key="icon.name" class="white--text mx-4" icon :href="`${icon.link}`" target="_blank" link>
                    <v-icon :color="icon.color">{{ icon.name }}</v-icon>
                </v-btn>
            </v-col>
            <v-col cols="6" class="text-center">
                <div class="body-2 pt-2 white--text">&copy; {{ new Date().getFullYear() }} tipexpats.com All rights reserved </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    data(){
        return{
            icons: [
                {name: 'mdi-facebook', color:'#1877f2', link:'https://www.facebook.com/tipexpats'},
                {name: 'mdi-twitter', color: '#1da1f2', link: 'https://www.twitter.com/tipexpats'},
                {name: 'mdi-instagram', color: '#c32aa3', link: 'https://www.instagram.com/tipexpats'}
            ],
        }
    }
}
</script>

<style scoped lang="scss">
    .footer{
        background-color: #1f0066 ;
        width: 100vw !important;

        .col_wrap{
            margin: 15px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;

            p{
                align-self: center;
                margin-left: 15px;
                font-size: 17px;
            }
            ul{
                list-style: none;

                li{
                    line-height: 1.9;
                    padding-bottom: 5px;

                    a{
                        text-decoration: none;
                        color: #cfcccc;
                        transition: all .4s;
                        font-size: 16px;

                        &:hover{
                            color:rgb(255, 255, 255);
                        }
                    }
                }
            }
        }
        .base{
            background-color: #120138;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    }
</style>
