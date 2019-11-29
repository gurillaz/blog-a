<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="5">
                    <v-card outlined class="mb-6" tile>
                        <v-card-text>
                            <span
                                class="text-uppercase font-weight-bold"
                            >If you don't have an account, you can</span>
                            <router-link
                                class="green--text font-weight-bold"
                                to="/register"
                            >REGISTER HERE</router-link>
                            <!-- <v-btn text small color="orange"></v-btn> -->
                        </v-card-text>
                    </v-card>
                    <v-card class="elevation-12" tile>
                        <v-toolbar :color="has_error?'red':'indigo'" dark flat>
                            <v-toolbar-title>Login</v-toolbar-title>
                        </v-toolbar>

                        <v-card-text class="pt-5 pb-0">
                            <v-text-field
                                class="mt-5"
                                label="Email:"
                                name="email"
                                v-model="email"
                                prepend-inner-icon="mdi-email"
                                type="text"
                                :error="has_error"
                                :error-messages="error"
                            ></v-text-field>

                            <v-text-field
                                class="mt-5"
                                label="Password:"
                                name="password"
                                v-model="password"
                                prepend-inner-icon="mdi-lock"
                                type="password"
                                :error="has_error"
                                :error-messages="error"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-text class="mt-3 pt-0">
                            <v-row class="mx-1">

                            <v-checkbox
                            class="mt-0 pt-0"
                                v-model="remember"
                                label="Remember me"
                                color="blue"
                                hide-details
                            ></v-checkbox>
                            <v-btn class="ml-auto" text small color="orange" to="/reset-password">Forgot password?</v-btn>
                            </v-row>
                        </v-card-text>
                        <v-card-actions class="pa-5">
                            <v-btn tile :color="has_error?'red':'indigo'" @click="login" block dark>Login</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    data() {
        return {
            email: null,
            password: null,
            remember: false,
            has_error: false,
            error: "",
            success: false
        };
    },
    methods: {
        login() {
            const app = this;
            this.$auth.login({
                params: {
                    email: app.email,
                    password: app.password
                },
                success: function() {
                    app.success = true;
                },
                error: function(resp) {
                    console.log(resp.response.data.error);
                    app.has_error = true;
                    app.error = resp.response.data.msg;
                },
                rememberMe: this.remember,
                redirect: "/",
                fetchUser: true
            });
        }
    }
};
</script>
