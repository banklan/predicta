<template>
    <div class="footer">
        <div class="wrapper">
            <div class="top">
                <div class="pillar">
                    <ul>
                        <li class="font-weight-bold header">Quick Links</li>
                        <li><router-link to="/">Home</router-link></li>
                        <li><router-link to="/about-us">About Us</router-link></li>
                        <li><router-link to="/contact-us">Contact Us</router-link></li>
                        <li><router-link to="/todays-tips">Today's Tips</router-link></li>
                    </ul>
                </div>
                <div class="pillar">
                    <ul>
                        <li class="font-weight-bold header">Quick Links</li>
                        <li><router-link to="/tip-experts">Tip Experts </router-link></li>
                        <li><router-link to="/login">User Login </router-link></li>
                        <li><router-link to="/register">User Register </router-link></li>
                        <li><router-link to="/expert-login">Expert Login</router-link></li>
                    </ul>
                </div>
                <div class="pillar">
                    <ul>
                        <li class="font-weight-bold header">Quick Links</li>
                        <li><router-link to="/tip-experts">Tip Experts </router-link></li>
                        <li><router-link to="/login">User Login </router-link></li>
                        <li><router-link to="/register">User Register </router-link></li>
                        <li><router-link to="/expert-login">Expert Login</router-link></li>
                    </ul>
                </div>
                <div class="pillar">
                    <ul>
                        <li class="font-weight-bold header">Extras</li>
                        <li><router-link to="/advertise">Promote Your Services</router-link></li>
                        <li><router-link to="/faq">FAQ</router-link></li>
                        <li><router-link to="/terms-conditions">Terms & Conditions</router-link></li>
                        <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
                        <li><router-link to="/join-mailing-list">Receive Daily Sure Bets</router-link></li>
                    </ul>
                </div>
            </div>
            <div class="bottom">
                <div class="social">
                    <v-btn large v-for="icon in icons" :key="icon.name" class="white--text mx-4" icon :href="`${icon.link}`" target="_blank" link>
                        <v-icon :color="icon.color">{{ icon.name }}</v-icon>
                    </v-btn>
                </div>
                <div class="copy">
                    &copy; {{ new Date().getFullYear() }} tipexpats.com &nbsp;&nbsp; All Rights Reserved
                </div>
            </div>
        </div>
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

<style lang="scss" scoped>
    .footer{
        min-height: 350px;
        color: #fff;
        width: 100vw;
        .wrapper{
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;

            .top{
                display: flex;
                flex: 2 1 auto;
                flex-wrap: auto;
                box-sizing: border-box;
                justify-content: space-between;
                align-items: center;
                height: 90%;
                margin: 0;
                background: #17014a ;
                .pillar{
                    display: flex;
                    flex-direction: column;
                    flex: 1 0 auto;
                    box-sizing: border-box;
                    vertical-align: baseline;
                    justify-content: center;
                    align-items: center;
                    ul{
                        margin: 0;
                        list-style: none;
                        box-sizing: border-box;
                        padding-left: 0;
                        margin-left: 0;
                        line-height: 1.7;
                        li{
                            box-sizing: border-box;
                            display: list-item;
                            text-align: -webkit-match-parent;

                            a{
                                text-decoration: none;
                                color: #9ca5af;
                                font-size: 17px;

                                &:hover{
                                    color: #fff;
                                }
                            }
                        }
                        li.header{
                            color: #fff;
                            padding-bottom: 5px;
                            text-transform: uppercase !important;
                        }
                    }
                }
            }
            @media screen and (max-width: 767px){
                .top{
                    flex-direction: column;
                    padding-top: 30px;
                    .pillar{
                        margin-bottom: 25px;
                    }
                }
            }
            .bottom{
                display: flex;
                justify-content: space-around;
                align-items: center;
                background: #120138;
                padding: 15px;
                .v-btn{
                    text-decoration: none;
                }
            }
            @media screen and (max-width: 767px){
                .bottom{
                    flex-direction: column;
                    .copy{
                        padding-top: 10px;
                    }
                }
            }
        }
    }
</style>
