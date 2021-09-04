<template>
    <div class="mt-5">
        <v-list nav rounded>
            <v-list-item-group color="primary">
                <v-list-item v-for="(item, i) in otherRoutes" :key="i" link :to="item.path" exact>
                    <v-list-item-content>
                        <v-list-item-title v-text="item.title" class="pl-2 primary--text"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </div>
</template>

<script>
export default {
    data() {
        return {
            otherRoutes:[
                {title: 'My Forecasts', path: '/expert/forecasts'},
                {title: 'Subscriptions', path: '/expert/subscriptions'},
                {title: 'Earnings', path: '/expert/earnings'},
                {title: 'Make New Forecast', path: '/expert/new-forecast'},
            ]
        }
    },
}
</script>
