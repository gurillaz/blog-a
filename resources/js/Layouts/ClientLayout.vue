<template>
    <v-app>
        <v-app-bar app absolute color="white" height="100" elevation="2">
            <v-container class="pt-0 mt-0 pb-0">
                <v-row class="py-0 mb-1">
                    <v-col cols="12" class="d-flex justify-content-between py-0">
                        <router-link
                            to="/"
                            class="ml-1 toolbar-logo serif-font grey--text text--darken-3"
                        >
                            <v-icon large color="grey darken-3" class="mb-1">mdi-newspaper</v-icon>Blog-A
                        </router-link>

                        <v-spacer></v-spacer>
                        <!-- <v-btn text small color="primary" to="/login">Login</v-btn> -->

                        <template v-if="!$auth.check()">
                            <div class="mt-1">
                                <v-btn text color="black" to="/login">Login</v-btn>
                                <v-btn text color="black" to="/register">register</v-btn>
                            </div>
                        </template>
                        <template v-else>
                            <v-menu offset-y>
                                <template v-slot:activator="{ on }">
                                    <v-btn text small class="mt-2" v-on="on">
                                        {{$auth.user().name}}
                                        <v-icon right>mdi-chevron-down</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item v-on:click="$auth.logout()">
                                        <v-list-item-title>Logout</v-list-item-title>
                                    </v-list-item>
                                    <v-list-item to="/profile">
                                        <v-list-item-title>Profile</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                        <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer" /> -->
                    </v-col>
                </v-row>
                <v-row class="py-0 mt-0">
                    <v-col cols="12" class="py-0 mt-0 px-0">
                        <v-toolbar
                            flat
                            tile
                            dense
                            color="transparent"
                            class="mt-1 pt-0"
                            height="20"
                        >
                            <!-- <div class="black--text">{{$auth.user().name}}</div> -->

                            <v-spacer></v-spacer>

                            <v-toolbar-items>
                                               <router-link
                                    v-if="$auth.user().role == 'admin'"
                                    to="/admin/home"
                                    class="main-toolbar-link my-auto text-uppercase caption font-weight-bold mx-2 red--text"
                                >Admin dashboard</router-link>
                                <router-link
                                    to="/search"
                                    class="main-toolbar-link my-auto text-uppercase caption font-weight-bold mx-2"
                                >Search</router-link>
                                <router-link
                                    to="/"
                                    class="main-toolbar-link my-auto text-uppercase caption font-weight-bold mx-2"
                                >Home</router-link>
                                <router-link
                                    v-if="$auth.check()==true"
                                    to="/profile"
                                    class="main-toolbar-link my-auto text-uppercase caption font-weight-bold mx-2"
                                >Profile</router-link>
                                <router-link
                                    v-if="$auth.check()==true"
                                    to="/article/new"
                                    class="main-toolbar-link my-auto text-uppercase caption font-weight-bold mx-2"
                                >Add new article</router-link>
                            </v-toolbar-items>
                        </v-toolbar>
                        <!-- <template v-if="$auth.check()">
                            
                            <v-btn class="my-auto" color="black" text dark to="/profile">
                                {{$auth.user().name}}
                            </v-btn>
                        </template>-->
                    </v-col>
                </v-row>
            </v-container>
        </v-app-bar>
        <v-content>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer absolute app class="font-weight-medium" color="white">
            <v-container>
                <v-row height="300">
                    <v-col class="text-center" cols="12">
                        {{ new Date().getFullYear() }} —
                        <strong>Blog-A</strong>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>

<script>
// import Menu from "./components/Menu.vue";

export default {
    data() {
        return {
            drawer: true
        };
    },
    components: {
        // Menu
    }
};
</script>
<style lang="css">
.serif-font {
    /* font-family: "Playfair Display", serif !important; */
}
.main-toolbar-link-active {
    font-weight: bold;
    background: none;
}
.main-toolbar-link {
    text-decoration: none;
}
.toolbar-logo {
    letter-spacing: 1px;
    font-weight: bold;
    font-size: 1.6rem;
    text-decoration: none;
}
</style>