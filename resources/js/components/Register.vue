<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="5">
                    <v-card outlined class="mb-6">
                        <v-card-text>
                            <span
                                class="text-uppercase font-weight-bold"
                            >Already have an account? You can</span>
                            <router-link class="green--text font-weight-bold" to="/login">LOGIN HERE</router-link>
                            <!-- <v-btn text small color="orange"></v-btn> -->
                        </v-card-text>
                    </v-card>
                    <v-card class="elevation-12">
                        <v-toolbar :color="has_error?'red':'indigo'" dark flat>
                            <v-toolbar-title>Register</v-toolbar-title>
                        </v-toolbar>

                        <v-card-text class="px-10">
                            <v-text-field
                                class="mt-5"
                                label="Name:"
                                v-model="name"
                                type="text"
                                :error="errors.name != undefined"
                                :error-messages="errors.name"
                            ></v-text-field>
                            <v-text-field
                                class="mt-5"
                                label="Email:"
                                v-model="email"
                                type="text"
                                :error="errors.email != undefined"
                                :error-messages="errors.email"
                            ></v-text-field>
                            <v-text-field
                                class="mt-5"
                                label="Passwoord:"
                                v-model="password"
                                type="password"
                                :error="errors.password != undefined"
                                :error-messages="errors.password"
                            ></v-text-field>
                            <v-text-field
                                class="mt-5"
                                label="Repeat password:"
                                v-model="password_r"
                                type="password"
                                :error="password_error"
                                :error-messages="password_error?'Password does not match!':''"
                                :success-messages="!password_error && password_r!=null?'Password does match!':''"
                                :success="password_r!=null && password==password_r"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions class="pa-5">
                            <div class="flex-grow-1"></div>
                            <v-btn
                                :color="has_error?'red':'indigo'"
                                @click="register"
                                block
                                dark
                            >Submit</v-btn>
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
            name: "",
            email: "",
            password: null,
            password_r: null,
            has_error: false,
            errors: [],
            success: false
        };
    },
    computed: {
        password_error() {
            return this.password_r != null && this.password != this.password_r;
        }
    },
    methods: {
        register() {
            const app = this;
            this.$auth.register({
                params: {
                    name: app.name,
                    email: app.email,
                    password: app.password
                },
                success: function() {
                    app.success = true;
                    this.$router.push({
                        name: "login",
                        successRegistrationRedirect: true
                    });
                },
                error: function(resp) {
                    // console.log(resp.response.data.errors);
                    app.has_error = true;
                    app.errors = resp.response.data.errors || {};
                },
                redirect: null
            });
        }
    }
};
</script>
