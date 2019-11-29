<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Comments</span>
                <p class="caption">Comments(all): {{total}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <!-- <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    v-on:click="add_new_resource=true"
                >Add new category</v-btn>
            </v-col>-->
        </v-row>

        <v-row>
            <!-- <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>-->
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="comments_header"
                        :items="resources"
                        :hide-default-footer="true"
                        :loading="loading"
                    >
                        <template v-slot:item.comment_body="{ item }">
                            <span>{{item.body.substr(0,30)}}...</span>
                        </template>
                        <template v-slot:item.comment_type="{ item }">
                            <span v-if="item.commentable_type==`App\\Comment`">Reply</span>
                            <span v-else>Comment</span>
                        </template>
                        <template v-slot:item.article_title="{ item }">
                            <span>{{item.article.title.substr(0,30)}}...</span>
                        </template>

                        <template v-slot:item.action="{ item }">
                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="red"
                                    v-on:click="delete_resource(item.id)"
                                >Delete</v-btn>

                                <v-btn tile text small v-on:click="show_resource_view(item.id)">Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                    <v-pagination
                        class="mt-5 mb-5 ml-auto"
                        v-model="page"
                        :length="pageCount"
                        @input="onPageChange"
                    ></v-pagination>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" v-if="show_resource_dialog">
            <v-dialog v-model="show_resource_dialog" max-width="55vw">
                <v-card outlined>
                    <v-card-subtitle class="pl-3 pt-3 font-weight-bold black--text">
                        <!-- User - 12/12/12 12:12 -->
                        <!-- {{show_resource.user.name}} -->
                        <template v-if="show_resource.user!=null">{{show_resource.user.name}}</template>
                        <template v-else>User</template>
                        <span class="caption grey--text">- {{show_resource.created_at}}</span>
                    </v-card-subtitle>
                    <v-card-text>
                        <p class="black--text">{{show_resource.body}}</p>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="10"></v-col>

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
            add_new_resource: false,
            loading: false,
            search: "",
            page: 1,
            pageCount: 1,
            itemsPerPage: 1,
            total: 0,
            comments_header: [
                { text: "Comment", value: "comment_body" },
                { text: "Article", value: "article_title" },
                { text: "User", value: "user.name" },
                { text: "Date", value: "updated_at" },
                { text: "Status", value: "meta_status" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],

            resources: [],
            saving_errors: [],

            show_resource: [],
            show_resource_dialog: false
        };
    },
    computed: {},

    methods: {
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
        delete_resource(resource_id) {
            let currentObj = this;

            if (!confirm("Delete the comment?")) {
                return;
            }
            axios
                .delete(`/comment/${resource_id}`)
                .then(function(resp) {
                    // currentObj.resources =
                    let comment = currentObj.resources.filter(resource => {
                        return resource.id == resource_id;
                    })[0];
                    comment.body = "[deleted]";
                    comment.user = null;
                    comment.meta_status = "deleted";
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                    console.log(resp);
                });
        },
        onPageChange() {
            let currentObj = this;
            axios
                .get(`/comment?page=${currentObj.page}`)
                .then(function(resp) {
                    currentObj.resources = resp.data.data.data;
                    currentObj.total = resp.data.data.total;
                    currentObj.pageCount = resp.data.data.last_page;
                    currentObj.itemsPerPage = resp.data.data.per_page;
                    currentObj.page = resp.data.data.current_page;

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
            .get("/comment")
            .then(function(resp) {
                currentObj.resources = resp.data.data.data;
                currentObj.total = resp.data.data.total;

                currentObj.pageCount = resp.data.data.last_page;
                currentObj.itemsPerPage = resp.data.data.per_page;
                currentObj.page = resp.data.data.current_page;

                // console.log(currentObj.pageCount);
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
