<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Export data</span>
                <p class="caption py-0 my-0">Choose resource and select dates to generate file.</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-title>
                        <v-col cols="3" class="py-0">
                            <v-autocomplete
                                v-model="query.model"
                                :items="models"
                                item-value="value"
                                item-text="name"
                                label="Resource:"
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="3" class="py-0">
                            <v-autocomplete
                                v-model="query.type"
                                :items="file_types"
                                item-value="value"
                                item-text="name"
                                label="File type:"
                            ></v-autocomplete>
                        </v-col>

                        <v-col cols="3" class="py-0">
                            <v-menu
                                v-model="dp_start"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="query.date_start"
                                        label="Created from (Date):"
                                        clearable
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="query.date_start"
                                    no-title
                                    @input="dp_start = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="3" class="py-0">
                            <v-menu
                                v-model="dp_end"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="query.date_end"
                                        label="Created to (Date):"
                                        clearable
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="query.date_end"
                                    no-title
                                    @input="dp_end = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12" class="mt-12">
                            <v-btn
                                block
                                outlined
                                tile
                                v-on:click="get_data()"
                                :disabled="disabled_download"
                            >
                                Download file
                                <v-icon right dark>icon</v-icon>
                                <!-- Remove if don't want to use icon. -->
                            </v-btn>
                        </v-col>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            log_search: "",
            dp_start: false,
            dp_end: false,

            models: [
                {
                    name: "Articles",
                    value: "articles"
                },
                {
                    name: "Comments",
                    value: "comments"
                },
                {
                    name: "Categories",
                    value: "categories"
                },
                {
                    name: "Tags",
                    value: "tags"
                },
                {
                    name: "Users",
                    value: "users"
                }
            ],
            file_types: [
                {
                    name: "Excel",
                    value: "excel"
                },
                {
                    name: "CSV",
                    value: "csv"
                }
            ],
            query: {
                token: "",
                model: "",
                type: "excel",
                date_start: "",
                date_end: ""
            }
        };
    },
    computed: {
        disabled_download: function() {
            return (
                this.query.model.length == 0 ||
                this.query.date_start == null ||
                this.query.date_end == null
            );
        }
    },

    methods: {
        get_data() {
            let currentObj = this;

            const url = `${process.env.MIX_APP_URL}/${currentObj.query.model}/export?token=${currentObj.query.token}&date_start=${currentObj.query.date_start}&type=${currentObj.query.type}&date_end=${currentObj.query.date_end}`;

            var win = window.open(url, "_blank");
            win.focus();
        }
    },

    beforeMount: function() {
        let currentObj = this;

        let today_date = new Date();
        let days_before = new Date(
            new Date().setDate(new Date().getDate() - 30)
        );
        currentObj.query.token = currentObj.$auth.token();
        currentObj.query.date_start = days_before.toISOString().substr(0, 10);
        currentObj.query.date_end = today_date.toISOString().substr(0, 10);
    }
};
</script>
