<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="5">
                    <v-card tile outlined class="mb-6" v-if="success">
                        <v-card-text>
                            <span class="text-uppercase font-weight-bold">Verification mail sent.</span>
                            <router-link
                                class="green--text font-weight-bold"
                                to="/"
                            >Click here to go home</router-link>

                            <!-- <v-btn text small color="orange"></v-btn> -->
                        </v-card-text>
                    </v-card>
                    <v-card tile class="elevation-12" v-else>
                        <v-toolbar :color="has_error?'red':success?'green':'indigo'" dark flat>
                            <v-toolbar-title>Reset Password</v-toolbar-title>
                        </v-toolbar>

                        <v-card-text class="px-6">
                            <v-text-field
                                class="mt-5"
                                label="Email:"
                                name="email"
                                v-model="email"
                                prepend-inner-icon="mdi-email"
                                type="text"
                                :error="errors.email != undefined"
                                :error-messages="errors.email"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions class="pa-5">
                            <div class="flex-grow-1"></div>
                            <v-btn
                                tile
                                :color="has_error?'red':'indigo'"
                                @click="requestResetPassword"
                                block
                                dark
                            >Send Password Reset Link</v-btn>
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
            has_error: false,
            error: "",
            errors: [],
            success: false
        };
    },
    methods: {
        requestResetPassword() {
            this.$http.post("/auth/reset-password", { email: this.email }).then(
                result => {
                    this.response = result.data;
                    this.success = true;
                    this.has_error = false;
                    this.errors = [];
                },
                error => {
                    this.has_error = true;
                    this.errors = error.response.data.errors || {};
                    console.error(error);
                }
            );
        }
    }
};
</script>