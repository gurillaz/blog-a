<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Comments</span>
                <p class="caption py-0 my-0">Pending Comments: {{resources.pending_comments.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="10">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Pending Comments</p>
            </v-col>
            <v-col cols="2">
                <v-btn block small tile color="primary" dark v-on:click="aprove_all()">Aprove All</v-btn>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="comments_search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                        :headers="comments_header"
                        :items="resources.pending_comments"
                        :search="comments_search"
                        :loading="loading"
                    >
                        <!-- <template v-slot:item.desc="{ item }">
                            <span>{{item.description!=null&&item.description.length>30?item.description.substr(0,30)+'...':item.description}}</span>
                        </template>-->
                        <template v-slot:item.comment_body="{ item }">
                            <span>{{item.body.substr(0,30)}}...</span>
                        </template>
                        <template v-slot:item.comment_type="{ item }">
                            <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                            <span v-else>Comment</span>
                        </template>
                        <template v-slot:item.article_title="{ item }">
                            <span>{{item.article.title.substr(0,30)}}...</span>
                        </template>
                        <template v-slot:item.comment_type="{ item }">
                            <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                            <span v-else>Comment</span>
                        </template>
                        <template v-slot:item.action="{ item }">
                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="red"
                                    v-on:click="deny_resource(item.id)"
                                >Deny</v-btn>
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="green"
                                    v-on:click="approve_resource(item.id)"
                                >Aprove</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn tile text small v-on:click="show_resource_view(item.id)">Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Denied Comments</p>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="denied_search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                        :headers="comments_header"
                        :items="resources.denied_comments"
                        :search="denied_search"
                        :loading="loading"
                    >
                        <!-- <template v-slot:item.desc="{ item }">
                            <span>{{item.description!=null&&item.description.length>30?item.description.substr(0,30)+'...':item.description}}</span>
                        </template>-->
                        <template v-slot:item.comment_body="{ item }">
                            <span>{{item.body.substr(0,30)}}...</span>
                        </template>
                        <template v-slot:item.comment_type="{ item }">
                            <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                            <span v-else>Comment</span>
                        </template>
                        <template v-slot:item.article_title="{ item }">
                            <span>{{item.article.title.substr(0,30)}}...</span>
                        </template>
                        <template v-slot:item.comment_type="{ item }">
                            <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                            <span v-else>Comment</span>
                        </template>
                        <template v-slot:item.action="{ item }">
                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="green"
                                    v-on:click="approve_resource(item.id)"
                                >Aprove</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn tile text small v-on:click="show_resource_view(item.id)">Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" v-if="show_resource_dialog">
            <v-dialog v-model="show_resource_dialog" max-width="55vw">
                <v-card outlined>
                    <v-card-subtitle class="pl-3 pt-3 font-weight-bold black--text">
                        <!-- User - 12/12/12 12:12 -->
                        {{show_resource.user.name}}
                        <span
                            class="caption grey--text"
                        >- {{show_resource.created_at}}</span>
                    </v-card-subtitle>
                    <v-card-text>
                        <p class="black--text">{{show_resource.body}}</p>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="8"></v-col>

                        <v-col cols="2">
                            <v-btn
                                tile
                                block
                                small
                                color="green"
                                v-on:click="approve_resource(show_resource.id)"
                            >Aprove</v-btn>
                        </v-col>
                        <v-col cols="2">
                            <v-btn tile small block v-on:click="show_resource_dialog=false">Close</v-btn>
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
            comments_search: "",
            denied_search: "",

            comments_header: [
                { text: "Comment", value: "comment_body" },
                { text: "Article", value: "article_title" },
                { text: "User", value: "user.name" },
                { text: "Type", value: "comment_type" },

                { text: "Date", value: "created_at" },

                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
                // { text: "Status", value: "meta_status" }
            ],

            resources: {
                pending_comments: [],
                denied_comments: []
            },
            show_resource: [],
            show_resource_dialog: false
        };
    },
    computed: {},

    methods: {
        delete_resource(resource_id) {
            let currentObj = this;
            axios
                .delete(`/user/${user_id}`)
                .then(function(resp) {
                    currentObj.resources = currentObj.resources.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        show_resource_view(resource_id) {
            let currentObj = this;
            axios
                .get(`/comment/${resource_id}`)
                .then(function(resp) {
                    // console.log(resp.data);

                    currentObj.show_resource = resp.data.resource;
                    currentObj.show_resource_dialog = true;

                    // currentObj.belongs_to = resp.data.belongs_to;
                    // currentObj.created_by = resp.data.created_by;
                    // currentObj.model = resp.data.model;
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                });
        },
        aprove_all() {
            let currentObj = this;
            if (!confirm("Do you aprove all comments?")) {
                return;
            }
            axios
                .post(`/aprove_all_comments`)
                .then(function(resp) {
                    currentObj.resources.pending_comments = [];
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        approve_resource(resource_id) {
            let currentObj = this;
            if (!confirm("Do you aprove this comment?")) {
                return;
            }
            axios
                .post(`/approve_comment/${resource_id}`)
                .then(function(resp) {
                    currentObj.resources.pending_comments = currentObj.resources.pending_comments.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    currentObj.resources.denied_comments = currentObj.resources.denied_comments.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    currentObj.show_resource_dialog = false;
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        deny_resource(resource_id) {
            let currentObj = this;
            if (!confirm("Do you deny this comment?")) {
                return;
            }
            axios
                .post(`/deny_comment/${resource_id}`)
                .then(function(resp) {
                    currentObj.resources.denied_comments.unshift(
                        currentObj.resources.pending_comments.filter(
                            resource => {
                                return resource.id == resource_id;
                            }
                        )[0]
                    );
                    currentObj.resources.pending_comments = currentObj.resources.pending_comments.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    currentObj.show_resource_dialog = false;

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
        axios
            .get("/pending_comments")
            .then(function(resp) {
                currentObj.resources = resp.data.resources;

                // console.log(currentObj.pageCount);
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
