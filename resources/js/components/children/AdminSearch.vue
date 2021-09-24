<template>
    <div class="d-flex">
        <v-text-field :placeholder="`Search for ${ searchFor }`" v-model="q" outlined dense append-icon="search" clearable @keypress.enter="search"></v-text-field>
        <!-- <v-btn class="primary" @click.prevent="search">Go</v-btn> -->
    </div>
</template>

<script>
export default {
    props: ['model', 'searchFor'],
    data() {
        return {
            q: ''
        }
    },
    methods: {
        search(){
            if(this.q.trim() !== ''){
                this.$router.push({name: `AdminSearch${this.model}Res`, query:{q: this.q.trim()}})
            }
        }
    }
}
</script>

<style lang="css" scoped>
.v-input__slot{
    border-radius: 50px !important;
}

</style>
