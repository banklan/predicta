<template>
    <v-container>
        <v-row wrap justify="center" class="mt-3">
            <v-col cols="12" md="8">
                <v-card shaped light class="mx-auto pa-8">
                    <v-card-title class="headline justify-center">Frequently Asked Questions</v-card-title>
                    <v-card-text class="mt-5">
                        <div v-for="faq in faqs" :key="faq.i" class="mb-5">
                            <div class="font-weight-bold subtitle-1">{{ faq.i }} Q: {{ faq.q }}?</div>
                            <div class="subtitle-1">A: {{ faq.a }}</div>
                        </div>
                        <div class="">
                             <div class="font-weight-bold subtitle-1">
                                14: I can predict result of football games. How can i join tipexpats.com as a tip expert?
                             </div>
                             <div class="subtitle-1">
                                 A: Easy. Register <router-link :to="{name: 'ExpertRegister'}">here</router-link> and we will get back to you.
                             </div>
                        </div>
                        <div class="mt-4">
                             <div class="font-weight-bold subtitle-1">
                                15: I have a concern or enquiry. How can i contact tipexpats?
                             </div>
                             <div class="subtitle-1">
                                 A: You can reach us through any means on our <router-link :to="{name: 'ContactUs'}">contact page</router-link> and we will get back to you shortly after.
                             </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            faqs: [
                {i: 1, q: 'Does tipexpats offer free football predictions', a: 'Yes. Our daily tips are absolutely free.'},
                {i:2, q: 'How does your premium service work',
                    a: 'We have a group of talented tipsters who has a track record of success in football prediction over time. Through a process of careful analysis of the teams past games, present forms and other factors, they predict the likely outcomes of matches. They are rated according to their performances over a period of time. Users can select any of the experts and subscribe to an odd category. This gives them access to all predictions made by the expert for 7 days.'},
                {i:3, q: 'How much does our expert tip premium services cost',
                    a: 'For a 3 odd acca, it is priced at N3,000, 5 odd acca for N5,000 & 10 odd acca for N10,000 '},
                {i:4, q: 'Are the expert predictions always guaranteed to be correct',
                    a: 'While we strife to ensure that we engage expert tipsters whose predictions are 100% correct, the game of football itself carries some elements of uncertainties, part of which even the most talented tipsters may not foresee. For this reasons, predictions are never 100% guaranteed.'},
                {i:5, q: 'Are the payments refundable', a: 'No. Payments made are non-refundable'},
                {i:6, q: 'How often can i get free predictions on the site', a: 'Free tips are published every morning before the commencement of the days games.'},
                {i:7, q: 'I dont reside in Nigeria. How can i pay for your premium service', a: 'For dollar using customers, the rate is $10 for 3-odds category, $15 for 5-odds and $30 dollars for a 10 odds. Any other foreign currency should be converted and paid at dollars rate.'},
                {i:8, q: 'How long access do i have to a subscribed service', a: '7 days'},
                // {i:9, q: 'I can predict result of football games. How can i join surepredict as a tip expert', a: 'Easy. Register at `${/home}`'},
                {i:9, q: 'How do i know the tip experts to subscribe to ', a: 'Looking closely, one will observe that each tip expert has a unique prediction style. Some might favour some particular markets while some may favour other markets. However, we try to help our users make their decisions by rating the experts based on their success rate within the last couple of weeks. It is advised that experts that are ranked high and publish predictions frequently should be subscribed to.'},
                {i:10, q: 'As an expert, how much do i get from a subscription', a: '50% of the subscription amount goes to the expert'},
                {i:11, q: 'When do i get paid as an expert when a user has subscribed to my predictions', a: 'Payments to experts are processed on tuesdays and wednesdays.'},
                {i:12, q: 'How often should experts publish predictions', a: 'Basically, daily. Experts are expected to publish their predictions for each odd category on daily basis, offering as many as they can for each category. However, experts should note carefully that they are been rated according to their success rate in relation to the published predictions. This means an expert that has 2 of his 3 predictions correct (67% success rate) will be rated higher than one that had 3 of his 6 predictions (50% success rate) even though the latter published more predictions. Emphasis is in quality and not quantity.'},
                {i:13, q: 'What if all my predictions as an expert always come out wrong', a: 'Experts with low success rate over time will be de-listed after a period.'},
            ]
        }
    },
}
</script>
