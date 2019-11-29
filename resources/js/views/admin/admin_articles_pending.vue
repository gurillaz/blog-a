<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Articles</span>
                <p class="caption py-0 my-0">Pending Articles: {{resources.pending_articles.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="10">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Pending Articles</p>
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
                <p class="pb-0 mb-0 subtitle font-weight-bold">Denied Articles</p>
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
                        :headers="articles_header"
                        :items="resources.denied_articles"
                        :search="denied_search"
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
            <v-dialog v-model="show_resource_dialog" max-width="90vw">
                <v-card tile outlined>
                    <v-card-title
                        class="serif-font mx-5"
                        style="font-size:28px"
                    >{{show_resource.title}}</v-card-title>

                    <v-card-subtitle class="my-1 mx-5">
                        <router-link
                            class="blue--text subtitle"
                            :to="`/user/${show_resource.user.id}`"
                        >{{show_resource.user.name}}</router-link>
                        <!-- <span>-</span> -->
                        <!-- <span class="black--text">{{resource.publishing_date}}</span> -->
                    </v-card-subtitle>
                    <v-card tile color="indigo lighten-5" flat class="mx-5">
                        <v-card-text
                            class="black--text serif-font"
                            style="font-size:16px; line-height:1.3; letter-spacing:1px;"
                            v-html="show_resource.summary"
                        >
                            <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                        </v-card-text>
                        <v-card-text>
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="white"
                                :to="`/category/${show_resource.category.id}`"
                            >
                                <span class="black--text">{{show_resource.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="white"
                                v-for="(tag, index) in show_resource.tags"
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
                            :src="`/storage/images/${show_resource.image_path}`"
                        ></v-img>
                        <div class="mx-5 my-12">
                            <v-card-text
                                class="black--text serif-font"
                                style="font-size:16px; line-height:1.3; letter-spacing:1px"
                                v-html="show_resource.body"
                            >
                                <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                            </v-card-text>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                            <v-col cols="8"></v-col>

                            <v-col cols="2">
                                <v-btn
                                    tile
                                    block
                                    color="green"
                                    v-on:click="approve_resource(show_resource.id)"
                                >Aprove</v-btn>
                            </v-col>
                            <v-col cols="2">
                                <v-btn tile block v-on:click="show_resource_dialog=false">Close</v-btn>
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
            denied_search: "",

            articles_header: [
                { text: "Title", value: "title" },
                { text: "User", value: "user.name" },
                { text: "Category", value: "category.name" },
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


            resources: {
                pending_articles: [],
                pending_comments: []
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
                .get(`/article/${resource_id}`)
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
            if (!confirm("Do you aprove all articles?")) {
                return;
            }
            axios
                .post(`/aprove_all_articles`)
                .then(function(resp) {
                    currentObj.resources.pending_articles = [];
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        approve_resource(resource_id) {
            let currentObj = this;
            if (!confirm("Do you aprove this article?")) {
                return;
            }
            axios
                .post(`/approve_article/${resource_id}`)
                .then(function(resp) {
                    currentObj.resources.pending_articles = currentObj.resources.pending_articles.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    currentObj.resources.denied_articles = currentObj.resources.denied_articles.filter(
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
            if (!confirm("Do you deny this article?")) {
                return;
            }
            axios
                .post(`/deny_article/${resource_id}`)
                .then(function(resp) {
                    currentObj.resources.denied_articles.unshift(
                        currentObj.resources.pending_articles.filter(
                            resource => {
                                return resource.id == resource_id;
                            }
                        )[0]
                    );
                    currentObj.resources.pending_articles = currentObj.resources.pending_articles.filter(
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
            .get("/admin/pending_articles")
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
