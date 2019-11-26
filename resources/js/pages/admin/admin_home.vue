<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Dashboard</span>
                <p class="caption py-0 my-0">Pending Articles: {{resources.pending_articles.length}}</p>
                <p class="caption py-0 my-0">Pending Coments: {{resources.pending_comments.length}}</p>
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
            <v-col cols="10">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Pending Articles</p>
            </v-col>
                        <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    to="/admin/pending_articles"
                >Review Articles</v-btn>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="articles_search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                        :headers="articles_header"
                        :items="resources.pending_articles"
                        :search="articles_search"
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
                                    color="red"
                                    v-on:click="delete_resource(item.id)"
                                >Delete</v-btn>
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="orange"
                                    v-on:click="delete_resource(item.id)"
                                >Edit</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn
                                    tile
                                    text
                                    small
                                    link
                                    :to="`/admin/article/${item.slug}`"
                                    target="_blank"
                                >Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="10">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Pending Comments</p>
            </v-col>
            <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    to="/admin/pending_comments"
                >Review Comments</v-btn>
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
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Featured Articles</p>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="featured_search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                        :headers="articles_header"
                        :items="resources.latest_featured"
                        :search="featured_search"
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
                                    color="red"
                                    v-on:click="delete_resource(item.id)"
                                >Delete</v-btn>
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="orange"
                                    v-on:click="delete_resource(item.id)"
                                >Edit</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn
                                    tile
                                    text
                                    small
                                    link
                                    :to="`/admin/article/${item.slug}`"
                                    target="_blank"
                                >Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
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
            articles_search: "",
            comments_search: "",
            featured_search: "",

            articles_header: [
                { text: "Title", value: "title" },
                { text: "User", value: "user.name" },
                { text: "Category", value: "category.name" },
                // { text: "Comments No", value: "comments_count" },
                { text: "Created at", value: "created_at" }
                // { text: "Published at", value: "publishing_date" },
                // { text: "Status", value: "meta_status" },

                // {
                //     text: "Action",
                //     // align: "right",
                //     sortable: false,
                //     value: "action"
                // }
            ],
            comments_header: [
                { text: "Comment", value: "comment_body" },
                { text: "Article", value: "article_title" },
                { text: "User", value: "user.name" },
                { text: "Type", value: "comment_type" },

                { text: "Date", value: "created_at" },
                { text: "Status", value: "meta_status" }
            ],

            resources: {
                latest_featured: [],
                pending_comments: [],
                pending_articles: []
            }
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
            .get("/admin_home")
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
