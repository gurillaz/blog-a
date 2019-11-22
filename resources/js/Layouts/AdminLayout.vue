<template>
    <v-app>
        <v-app-bar app absolute color="transparent" flat>
            <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer" /> -->
            <v-container>
                <v-row class="d-flex justify-content-between px-5">
                    <v-toolbar-title>
                        <v-btn text icon large color="orange" class="mb-1">
                            <v-icon>mdi-newspaper</v-icon>
                        </v-btn>
                        Blog-a

                    </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <!-- <v-btn text small color="primary" to="/login">Login</v-btn> -->
                    <v-menu offset-y>
                        <template v-slot:activator="{ on }">
                            <v-btn color="black" text dark v-on="on">{{$auth.user().name}}</v-btn>
                        </template>
                        <v-list>
                            <v-list-item>
                                <v-list-item-title>Log out</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-row>
            </v-container>
        </v-app-bar>
        <v-content>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
import Menu from "./components/Menu.vue";

export default {
    data() {
        return {
            //
        };
    },
    components: {
        Menu
    }
};
</script>