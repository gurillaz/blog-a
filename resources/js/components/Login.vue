<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="5">
                    <v-card outlined class="mb-6">
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
                    <v-card class="elevation-12">
                        <v-toolbar :color="has_error?'red':'indigo'" dark flat>
                            <v-toolbar-title>Login</v-toolbar-title>
                        </v-toolbar>

                        <v-card-text class="px-5">
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
                            <v-btn text small color="orange" to="/reset-password">Forgot password?</v-btn>
                        </v-card-text>
                        <v-card-actions class="pa-5">
                            <v-btn :color="has_error?'red':'indigo'" @click="login" block dark>Login</v-btn>
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
                rememberMe: true,
                redirect: "/",
                fetchUser: true
            });
        }
    }
};
</script>
