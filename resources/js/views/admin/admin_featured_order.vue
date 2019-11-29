<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Add articles to featured</p>
            </v-col>
            <v-col cols="12">
                <v-card outlined tile>
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
                        v-model="selected_articles_dt"
                        show-select
                        :headers="articles_header"
                        :items="articles"
                        :search="articles_search"
                        :loading="loading"
                        :items-per-page="6"
                        v-on:item-selected="add_to_selected"
                        v-on:toggle-select-all="toggle_all_selected"
                    ></v-data-table>
                </v-card>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Featured and order</p>
                <!-- <p class="pb-0 mb-0 caption">Add articles and drag and drop to order them.</p> -->
            </v-col>

            <!-- {{resources.map(art=>{return art.id})}} -->
            <v-col cols="12">
                <div>
                    <draggable
                        v-model="selected_articles"
                        v-bind="dragOptions"
                        @start="drag = true"
                        @end="drag = false"
                    >
                        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                            <div v-for="article in selected_articles" :key="article.id">
                                <v-card tile hover class="ma-2">
                                    <v-row>
                                        <v-col cols="2" class="ma-0 py-0">
                                            <v-img
                                                height="127"
                                                width="auto"
                                                :src="`/storage/images/${article.image_path}`.replace('.jpg','_thumbnail.png')"
                                            ></v-img>
                                        </v-col>
                                        <v-col cols="9">
                                            <v-card-title>{{article.meta_list_place}} - {{article.title}}</v-card-title>
                                            <v-card-subtitle class="my-0">
                                                <span
                                                    class="blue--text subtitle"
                                                >{{article.user.name}}</span>
                                                <span>-</span>
                                                <span
                                                    class="black--text"
                                                >{{article.publishing_date}}</span>
                                                <span>-</span>
                                                <span
                                                    class="black--text text-uppercase subtitle"
                                                >{{article.category.name}}</span>
                                            </v-card-subtitle>
                                        </v-col>
                                        <v-col cols="1">
                                            <v-btn
                                                color="primary"
                                                dark
                                                small
                                                text
                                                v-on:click="remove_selected(article.id)"
                                            >Remove</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-card>
                            </div>
                        </transition-group>
                    </draggable>
                </div>
            </v-col>
            <v-col cols="8"></v-col>
            <v-col cols="4">
                <v-btn block small tile color="green" dark v-on:click="save_featured_order()">Save</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
// import { VueNestable, VueNestableHandle } from "vue-nestable";
// import { Container, Draggable } from "vue-smooth-dnd";
// import applyDrag from "vue-smooth-dnd";

import draggable from "vuedraggable";
export default {
    components: {
        draggable
    },
    //   components: {},
    data() {
        return {
            drag: false,
            loading: false,

            add_article_to_featured_dialog: false,

            articles_search: "",

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

            selected_articles_dt: [],
            selected_articles: [],
            articles: []
        };
    },
    computed: {
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
                // sort:false,
            };
        }
    },
    watch: {
        // this.selected_articles = this.selected_articles.sort(function(a,b){
        // });
    },

    methods: {
        add_to_selected(event) {
            // console.log(event.value);
            // console.log(event.item.id);
            // return

            if (event.value == false) {
                this.selected_articles = this.selected_articles.filter(
                    article => {
                        return article.id != event.item.id;
                    }
                );
            } else {
                this.selected_articles.unshift(event.item);
            }
            // console.log(selected)
            // this.selected_articles.unshift(item);
        },
        toggle_all_selected(event) {
            // console.log(event);
            if (event.value == false) {
                this.selected_articles = [];
            }
            if (event.value == true) {
                this.selected_articles = this.article;
            }
        },
        save_featured_order() {
            let currentObj = this;

            let articles_order = [];

            currentObj.selected_articles.forEach(function(article, index) {
                articles_order.push(article.id);
                // featured_order.push(article.id, index + 1);
            });

            axios
                .post("/save_featured_order", articles_order)
                .then(function(resp) {
                    console.log(resp.data.request);
                    alert("Done");
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                    console.log(resp);
                });

            console.log(articles_order);
        },
        remove_selected(resource_id) {
            let currentObj = this;

            currentObj.selected_articles = currentObj.selected_articles.filter(
                resource => {
                    return resource.id != resource_id;
                }
            );
            currentObj.selected_articles_dt = currentObj.selected_articles;
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
            .get("/featured_order")
            .then(function(resp) {
                currentObj.articles = resp.data.resources.articles;
                currentObj.selected_articles =
                    resp.data.resources.currently_featured;
                currentObj.selected_articles_dt =
                    resp.data.resources.currently_featured;

                // console.log(currentObj.pageCount);
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
<style lang="css">
.flip-list-move {
    transition: transform 0.5s;
}
.no-move {
    transition: transform 0s;
}
.ghost {
    opacity: 0.9;
    background: #c8ebfb;
}
</style>
