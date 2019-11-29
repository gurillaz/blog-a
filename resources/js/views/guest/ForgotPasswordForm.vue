<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="5">
                    <v-card class="elevation-12" tile>
                        <v-toolbar :color="has_error?'red':'indigo'" dark flat>
                            <v-toolbar-title>Update password</v-toolbar-title>
                        </v-toolbar>

                        <v-card-text class="px-10">
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
                                label="New password:"
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
                                @click="resetPassword"
                                block
                                dark
                                tile
                            >Update password</v-btn>
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
            token: null,
            email: null,
            password: null,
            password_r: null,
            password_confirmation: null,
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
        resetPassword() {
            const app = this;

            this.$http
                .post("/guest/reset/password/" + this.$route.params.token, {
                    token: this.$route.params.token,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_r
                })
                .then(
                    result => {
                        // success
                        app.success = true;
                        this.$router.push({ name: "login" });
                    },
                    response => {
                        // error
                        console.log(response);
                        app.has_error = true;
                        app.errors = response.errors || [];
                    }
                );
        }
    }
};
</script>