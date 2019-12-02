<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Activity Log</span>
                <p class="caption py-0 my-0">Activites last 30 days. You can filter activites by date(from,to) and by resoource type.</p>
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
                                label="Activity on:"
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
                                        label="From (Date):"
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
                                        label="To (Date):"
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
                        <v-col cols="3" class="py-0">
                            <v-btn
                                block
                                small
                                outlined
                                tile
                                :loading="loading"
                                v-on:click="get_data()"
                                :disabled="loading"
                            >
                                Get data
                                <v-icon right dark>icon</v-icon>
                                <!-- Remove if don't want to use icon. -->
                            </v-btn>
                        </v-col>
                        <v-col cols="6" class="py-0"></v-col>
                        <v-col cols="6" class="py-0">
                            <v-text-field v-model="log_search" append-icon="search" label="Search"></v-text-field>
                        </v-col>
                    </v-card-title>
                    <v-data-table
                        :headers="activity_headers"
                        :items="resource"
                        :search="log_search"
                        :loading="loading"
                    >
                        <!-- <template v-slot:item.desc="{ item }">
                            <span>{{item.description!=null&&item.description.length>30?item.description.substr(0,30)+'...':item.description}}</span>
                        </template>-->
                        <template v-slot:item.action="{ item }">
                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    v-on:click="show_activity_view(item.id)"
                                >Show activity</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>

        <v-row justify="center" v-if="show_activity_dialog">
            <v-dialog v-model="show_activity_dialog" max-width="55vw">
                <v-card outlined>
                    <v-card-title>Changes</v-card-title>
                    <v-card-subtitle>As JSON array</v-card-subtitle>
                    <v-card-text class="text-center mt-5">
                        <code class="text-left">{{show_activity.properties}}</code>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="10"></v-col>

                        <v-col cols="2">
                            <v-btn tile small block v-on:click="show_activity_dialog=false">Close</v-btn>
                        </v-col>
                    </v-card-actions>
                </v-card>
            </v-dialog>
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

            activity_headers: [
                { text: "Causer", value: "causer.name" },
                { text: "Causer role", value: "causer.role" },
                { text: "Change", value: "description" },
                { text: "Subject", value: "subject_type" },
                { text: "Subject ID", value: "subject_id" },
                // { text: "Comments No", value: "comments_count" },
                { text: "Created at", value: "created_at" },
                // { text: "Published at", value: "publishing_date" },
                // { text: "Status", value: "meta_status" },

                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],
            models: [
                {
                    name: "All",
                    value: "all"
                },
                {
                    name: "Article",
                    value: "article"
                },
                {
                    name: "Comments",
                    value: "comment"
                },
                {
                    name: "Category",
                    value: "category"
                },
                {
                    name: "Tag",
                    value: "tag"
                },
                {
                    name: "User",
                    value: "user"
                }
            ],
            query: {
                model: "all",
                date_start: "",
                date_end: ""
            },

            resource: [],

            show_activity_dialog: false,
            show_activity: ""
        };
    },
    computed: {},

    methods: {
        show_activity_view(activity_id) {
            let currentObj = this;

            currentObj.show_activity = currentObj.resource.filter(
                activity => activity.id == activity_id
            )[0];

            currentObj.show_activity_dialog = true;
        },
        get_data() {
            let currentObj = this;
            axios
                .get("/admin/activity_log", {
                    params: currentObj.query
                })
                .then(function(resp) {
                    currentObj.resource = resp.data.resource;

                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        }
    },
    created() {
        this.axios.interceptors.request.use(
            config => {
                // this.$store.commit("loading", true);
                this.loading = true;

                return config;
            },
            error => {
                // this.$store.commit("loading", false);
                this.loading = false;

                return Promise.reject(error);
            }
        );

        this.axios.interceptors.response.use(
            response => {
                // this.$store.commit("loading", false);
                this.loading = false;

                return response;
            },
            error => {
                // this.$store.commit("loading", false);
                this.loading = false;

                return Promise.reject(error);
            }
        );
    },

    beforeMount: function() {
        let currentObj = this;

        let today_date = new Date();
        let days_before = new Date(
            new Date().setDate(new Date().getDate() - 30)
        );
        currentObj.query.date_start = days_before.toISOString().substr(0, 10);
        currentObj.query.date_end = today_date.toISOString().substr(0, 10);
        // currentObj.query.date_end = new Date(Date.now()).toISOString();
        axios
            .get("/admin/activity_log", {
                params: currentObj.query
            })
            .then(function(resp) {
                currentObj.resource = resp.data.resource;

                // console.log(currentObj.pageCount);
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
