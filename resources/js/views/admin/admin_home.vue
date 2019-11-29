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
                                    v-on:click="show_resource_article_view(item.id)"
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
                            <span v-if="item.article != null">{{item.article.title.substr(0,30)}}...</span>
                            <span v-else>[Deleted]</span>
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
                                    v-on:click="show_resource_comment_view(item.id)"
                                >Show</v-btn>
                            </v-row>
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
                        :headers="featured_header"
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
                                    v-on:click="show_resource_article_view(item.id)"
                                >Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" v-if="show_resource_comment_dialog">
            <v-dialog v-model="show_resource_comment_dialog" max-width="55vw">
                <v-card outlined>
                    <v-card-subtitle class="pl-3 pt-3 font-weight-bold black--text">
                        <!-- User - 12/12/12 12:12 -->
                        <!-- {{show_resource.user.name}} -->
                        <template
                            v-if="show_resource_comment.user!=null"
                        >{{show_resource_comment.user.name}}</template>
                        <template v-else>User</template>
                        <span class="caption grey--text">- {{show_resource_comment.created_at}}</span>
                    </v-card-subtitle>
                    <v-card-text>
                        <p class="black--text">{{show_resource_comment.body}}</p>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="10"></v-col>

                        <v-col cols="2">
                            <v-btn
                                tile
                                small
                                block
                                v-on:click="show_resource_comment_dialog=false"
                            >Close</v-btn>
                        </v-col>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <v-row justify="center" v-if="show_resource_article_dialog">
            <v-dialog v-model="show_resource_article_dialog" max-width="90vw">
                <v-card tile outlined>
                    <v-card-title
                        class="serif-font mx-5"
                        style="font-size:28px"
                    >{{show_resource_article.title}}</v-card-title>

                    <v-card-subtitle class="my-1 mx-5">
                        <router-link
                            class="blue--text subtitle"
                            :to="`/user/${show_resource_article.user.id}`"
                        >{{show_resource_article.user.name}}</router-link>
                        <!-- <span>-</span> -->
                        <!-- <span class="black--text">{{resource.publishing_date}}</span> -->
                    </v-card-subtitle>
                    <v-card tile color="indigo lighten-5" flat class="mx-5">
                        <v-card-text
                            class="black--text serif-font"
                            style="font-size:16px; line-height:1.3; letter-spacing:1px;"
                            v-html="show_resource_article.summary"
                        >
                            <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                        </v-card-text>
                        <v-card-text>
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="white"
                                :to="`/category/${show_resource_article.category.id}`"
                            >
                                <span class="black--text">{{show_resource_article.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="white"
                                v-for="(tag, index) in show_resource_article.tags"
                                :key="index"
                                :to="`/tag/${tag.id}`"
                            >
                                <span class="black--text">#{{tag.name}}</span>
                            </v-chip>
                        </v-card-text>
                    </v-card>
                    <v-card-text>
                        <v-img
                            class="px-1n"
                            width="auto"
                            height="auto"
                            :src="`/storage/images/${show_resource_article.image_path}`"
                        ></v-img>
                        <div class="mx-5 my-12">
                            <v-card-text
                                class="black--text serif-font"
                                style="font-size:16px; line-height:1.3; letter-spacing:1px"
                                v-html="show_resource_article.body"
                            >
                                <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                            </v-card-text>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="1-"></v-col>

                        <v-col cols="2">
                            <v-btn tile block v-on:click="show_resource_article_dialog=false">Close</v-btn>
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
            articles_search: "",
            comments_search: "",
            featured_search: "",

            articles_header: [
                { text: "Title", value: "title" },
                { text: "User", value: "user.name" },
                { text: "Category", value: "category.name" },
                // { text: "Place No", value: "meta_list_place" },
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
            featured_header: [
                { text: "Title", value: "title" },
                { text: "User", value: "user.name" },
                { text: "Category", value: "category.name" },
                { text: "Place No", value: "meta_list_place" },
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
            comments_header: [
                { text: "Comment", value: "comment_body" },
                { text: "Article", value: "article_title" },
                { text: "User", value: "user.name" },
                { text: "Type", value: "comment_type" },

                { text: "Date", value: "created_at" },
                { text: "Status", value: "meta_status" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],

            resources: {
                latest_featured: [],
                pending_comments: [],
                pending_articles: []
            },

            show_resource_comment: [],
            show_resource_comment_dialog: false,

            show_resource_article: [],
            show_resource_article_dialog: false
        };
    },
    computed: {},

    methods: {
        show_resource_article_view(resource_id) {
            let currentObj = this;
            axios
                .get(`/admin/article/${resource_id}`)
                .then(function(resp) {
                    // console.log(resp.data);

                    currentObj.show_resource_article = resp.data.resource;
                    currentObj.show_resource_article_dialog = true;

                    // currentObj.belongs_to = resp.data.belongs_to;
                    // currentObj.created_by = resp.data.created_by;
                    // currentObj.model = resp.data.model;
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                });
        },
        show_resource_comment_view(resource_id) {
            let currentObj = this;
            axios
                .get(`/admin/comment/${resource_id}`)
                .then(function(resp) {
                    // console.log(resp.data);

                    currentObj.show_resource_comment = resp.data.resource;
                    currentObj.show_resource_comment_dialog = true;

                    // currentObj.belongs_to = resp.data.belongs_to;
                    // currentObj.created_by = resp.data.created_by;
                    // currentObj.model = resp.data.model;
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                });
        },

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
            .get("/admin/admin_home")
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
