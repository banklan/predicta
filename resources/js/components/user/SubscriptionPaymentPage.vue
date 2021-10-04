<template>
    <v-container>
        <expert-top-panel title="Payment"/>
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="7">
                <v-card raised flat elevation="6" light min-height="150">
                    <v-card-title class="justify-center primary white--text subtitle-1">Payment Page</v-card-title>
                    <v-card-text class="mt-5 px-6">
                        <div class="text-center mb-2 subtitle-1">User Information</div>
                        <table class="table table-hover table-striped">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th width="30%">Name: </th>
                                    <td>{{ authUser.fullname }}</td>
                                </tr>
                                <tr>
                                    <th>User ID: </th>
                                    <td>{{ authUser.id }}</td>
                                </tr>
                                <tr>
                                    <th>Odd Category</th>
                                    <td>{{ $route.params.odd }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-text class="px-8">
                        <!-- <vue-credit-card :preview-enabled="true" v-model="payment" :card-types="cardTypes"></vue-credit-card> -->
                        <v-text-field label="Card No" mask="debit-card" v-model="payment.card_no"></v-text-field>
                        <v-row>
                            <v-col cols="6">
                                <v-select label="Select Year" :items="years" item-value="year" item-text="year" v-model="payment.year"></v-select>
                                <!-- <v-text-field label="Expiry Month" mask="expiry-date" v-model="payment.expiry"></v-text-field> -->
                            </v-col>
                            <v-col cols="6">
                                <v-select label="Select Month" :items="months" item-value="month" item-text="month" v-model="payment.month"></v-select>

                            </v-col>
                        </v-row>
                        <v-text-field label="CVV" v-model="payment.cvv"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn dark large width="80%" color="primary darken-2" elevation="10" :loading="isBusy" @click="makePayment">Make Payment</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            ref: '',
            price: null,
            payment: {
                holder: '',
                number: null,
                month: null,
                year: null,
                cvv: null
            },
            isBusy: false,
            cardTypes: [
                {regEx: /^4[0-9]{5}/ig, name: 'visa'},
                {regEx: /^5[1-5][0-9]{4}/ig, name: 'mastercard'},
            ],
            years: [
                {id: 0, year: '2021'},
                {id: 1, year: '2021'},
                {id: 2, year: '2021'},
                {id: 3, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
                {id: 0, year: '2021'},
            ],
            minCardYear: new Date().getFullYear()
        }
    },
    computed:{
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
        fwpk(){
            return this.$store.getters.fwpk
        },
        fwsk(){
            return this.$store.getters.fwsk
        },
    },
    methods: {
        generateTrxRef(len){
            var ref = ''
            let characters = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'
            let charLent = characters.length
            for(var i=0; i<length; i++){
                ref += characters.charAt(Math.floor(Math.random() * charLent))
            }
            this.ref = ref;
        },
        getOddPrice(){
            axios.get(this.api + `/auth/get_odd_price/${this.$route.params.odd}`, this.authHeaders)
            .then((res) => {
                let price = res.data.price / 100
                this.price = price
            })
        },
        saveResponse(resp){
            axios.post(this.api + `/store_response`, {
                resp: resp
            }).then((res)=>{
                console.log(res.data)
            })
        },
    },
    created() {
        this.getOddPrice()
        this.generateTrxRef()
    },
}
</script>
