<template>
    <v-app>
        <v-navigation-drawer app color dark v-model="drawer">
            <v-list>
                <v-list-item class="mb-12">
                    <v-list-item-content class="text-center">
                        <h1 class="title">Blog-a</h1>
                        <v-list-item-subtitle>Admin dashbard</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title class="title">{{$auth.user().name}}</v-list-item-title>
                        <v-list-item-subtitle>{{$auth.user().email}}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-action>
                        <v-icon>mdi-menu-down</v-icon>
                    </v-list-item-action>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list nav dense>
                <v-list-item-group color="white">
                    <v-list-item to="/admin/home">
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Home</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item to="/admin/pending_articles">
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Pending Articles</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/pending_comments">
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Pending Comments</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/featured_order">
                        <v-list-item-icon>
                            <v-icon>mdi-newspaper</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Fetured & Order</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider></v-divider>

                    <v-list-item to="/admin/users">
                        <v-list-item-icon>
                            <v-icon>mdi-account-group</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Users</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/articles">
                        <v-list-item-icon>
                            <v-icon>mdi-newspaper</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Articles</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/categories">
                        <v-list-item-icon>
                            <v-icon>mdi-note-text-outline</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Categories</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/comments">
                        <v-list-item-icon>
                            <v-icon>mdi-comment-multiple</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Comments</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/tags">
                        <v-list-item-icon>
                            <v-icon>mdi-tag-multiple</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Tags</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="transparent" flat>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <!-- <v-toolbar-title>Admin Dashboard</v-toolbar-title> -->
        </v-app-bar>

        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            drawer: true
        };
    }
};
</script>