<template>
    <div class="auth_toppanel primary darken-2 subtitle-1 white--text">
        <v-row justify="center">
            <v-col cols="6">
                <div class="pl-3">{{ user }}</div>
            </v-col>
            <v-col cols="6">
                 <div class="pr-2">{{ title }}</div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    props: ['user', 'title']
}
</script>
