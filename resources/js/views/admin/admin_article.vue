<template>
    <v-container>
        <v-row>
            <v-col cols="10">
                <p class="headline font-weight-bold py-0 my-0">{{resource.name}}</p>
                <p class="caption py-0 my-0">
                    User: {{resource.user.name}} -
                    Category: {{resource.category.name}}
                </p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>

            <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="orange"
                    dark
                    v-on:click="delete_resource()"
                >Delete Article</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card tile outlined>
                    <v-card-title class="serif-font mx-5" style="font-size:28px">{{resource.title}}</v-card-title>

                    <v-card-subtitle class="my-1 mx-5">
                        <router-link
                            class="blue--text subtitle"
                            :to="`/user/${resource.user.id}`"
                        >{{resource.user.name}}</router-link>
                        <span>-</span>
                        <span class="black--text">{{resource.publishing_date}}</span>
                    </v-card-subtitle>
                    <v-card tile color="indigo lighten-5" flat class="mx-5">
                        <v-card-text
                            class="black--text serif-font"
                            style="font-size:16px; line-height:1.3; letter-spacing:1px;"
                            v-html="resource.summary"
                        >
                            <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                        </v-card-text>
                        <v-card-text>
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="white"
                                :to="`/category/${resource.category.id}`"
                            >
                                <span class="black--text">{{resource.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="white"
                                v-for="(tag, index) in resource.tags"
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
                            :src="`/storage/images/${resource.image_path}`"
                        ></v-img>
                        <div class="mx-5 my-12">
                            <v-card-text
                                class="black--text serif-font"
                                style="font-size:16px; line-height:1.3; letter-spacing:1px"
                                v-html="resource.body"
                            >
                                <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                            </v-card-text>
                        </div>
                        <v-divider class="mt-12 mx-12"></v-divider>
                        <v-card-subtitle class="my-1 mx-5">
                            <router-link
                                class="blue--text subtitle"
                                :to="`/user/${resource.user.id}`"
                            >{{resource.user.name}}</router-link>
                            <span>-</span>
                            <span class="black--text">{{resource.publishing_date}}</span>
                        </v-card-subtitle>
                        <v-card-text class="mx-5">
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="indigo lighten-5"
                                :to="`/category/${resource.category.id}`"
                            >
                                <span class="black--text">{{resource.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="indigo lighten-5"
                                v-for="(tag, index) in resource.tags"
                                :key="index"
                                :to="`/tag/${tag.id}`"
                            >
                                <span class="black--text">#{{tag.name}}</span>
                            </v-chip>
                        </v-card-text>
                        <!-- <v-card-actions>
                                        <v-btn
                                            color="orange"
                                            text
                                            :to="`/article/${resource.slug}`"
                                        >Read</v-btn>

                                        <v-btn
                                            depressed
                                            small
                                            color="white"
                                            fab
                                            class="ml-auto mr-2 mb-2"
                                        >
                                            <v-icon>mdi-bookmark-outline</v-icon>
                                        </v-btn>
                        </v-card-actions>-->
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: ["id"],
    data() {
        return {
            loading: false,
            articles_search: "",
            comments_search: "",

            articles_header: [
                { text: "Title", value: "title" },
                { text: "User", value: "user.name" },
                { text: "Category", value: "category.name" },
                // { text: "Place No", value: "meta_list_place" },
                { text: "Comments No", value: "comments_count" },
                { text: "Created at", value: "created_at" },
                // { text: "Published at", value: "publishing_date" },
                { text: "Status", value: "meta_status" },

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
                // { text: "User", value: "user.name" },
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

            resource: {
                comments: [],
                articles: []
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
                .get(`/article/${resource_id}`)
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
                .get(`/comment/${resource_id}`)
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

        delete_resource(resource_id) {
            let currentObj = this;
            if (!confirm("Do you want to delete this user?")) {
                return;
            }
            axios
                .delete(`/admin/user/${currentObj.resource.id}`)
                .then(function(resp) {
                    currentObj.$router.replace("/admin/users");
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log("Something wrong");
                    console.log(resp);
                });
        },
        make_admin() {
            let currentObj = this;
            if (
                !confirm(
                    `Do you want to make ${currentObj.resource.name} admin?`
                )
            ) {
                return;
            }
            if (!confirm(`Are you sure?`)) {
                return;
            }
            axios
                .post(`/admin/make_admin/${currentObj.resource.id}`)
                .then(function(resp) {
                    currentObj.resource.role = "admin";
                    alert("It is done!");
                })
                .catch(function(resp) {
                    console.log("Something wrong");
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
            .get(`/user/${currentObj.id}`)
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
