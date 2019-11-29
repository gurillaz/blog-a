<template>
    <v-app>
        <v-container fluid fill-height blue-grey>
            <v-row>
                <div class="flex-grow-1"></div>

                <v-col cols="10">
                    <p style="font-size:150px; color:white">
                        (
                        <span class="logo">.</span>_.)
                    </p>
                    <h1 class="white--text">Error 404!</h1>
                    <h4 class="white--text">Not found! Do you need a map?</h4>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>
<style lang="css" scoped>
.logo {
    -webkit-animation: flicker 2s infinite;
}

@-webkit-keyframes flicker {
    0% {
        opacity: 0;
    }
    9% {
        opacity: 0;
    }
    10% {
        opacity: 0.5;
    }
    13% {
        opacity: 0;
    }
    20% {
        opacity: 0.5;
    }
    25% {
        opacity: 1;
    }
}
</style>
