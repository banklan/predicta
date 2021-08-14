<template>
    <div>
        <div v-for="item in items" :key="item.id">{{ item.team }}</div>
    </div>
</template>

<script>
export default {
    props: [],
    data() {
        return {
            q: this.$route.query.q,
            model: this.$route.query.m,
            column: this.$route.query.c,
            isLoading: false,
            items: []
        }
    },
    computed:{
         authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getResult(){
            this.isLoading = true
            if(this.$route.query.q !== null && this.$route.query.m !== '' && this.$route.query.c !== ''){
                axios.post(this.api + `/auth-admin/admin_filter_models`, {
                    q: this.$route.query.q,
                    model: this.$route.query.m,
                    col: this.$route.query.c
                }, this.adminHeaders).then((res) => {
                    console.log(res.data)
                    this.items = res.data
                })
            }
        }
    },
    created(){
        this.getResult()
    }
}
</script>
