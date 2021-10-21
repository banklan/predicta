<template>
    <v-container>
        <v-row justify="center" class="mt-10 pb-8">
            <v-col cols="12" md="8">
                <div class="msg text-center">
                    Sorry, looks like we sent you the wrong way!!
                </div>
                <div class="btn_wrap mt-10">
                    <div class="text-center btns px-2">
                        <v-btn x-large text color="primary darken-2" elevation="3" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Go Back</v-btn>
                        <v-btn x-large text color="primary darken-2" left elevation="3" to="/" class="ml-5"><v-icon left>home</v-icon> Go To Home</v-btn>
                    </div>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="css" scoped>
    .msg{
        font-weight: 400;
        font-size: 1.6rem !important;
    }
    .btn_wrap{
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .btns{
        padding: 5px 15px;
    }
</style>
