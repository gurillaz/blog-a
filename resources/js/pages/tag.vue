<template>
    <v-container>
        <v-row class="mb-12">
            <v-col cols="12">
                <p class="title py-0 my-0">Tag</p>
                <span class="headline font-weight-bold py-0 my-0">#{{resource.name}}</span>
                <p class="caption">Articles: {{resource_relations.articles.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="font-weight-bold">{{resource.description}}</p>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">
                    Articles with
                    <span class="red--text font-weight-bold">#{{resource.name}}</span> tag
                </p>
            </v-col>
            <v-col v-for="(article,index) in resource_relations.articles" :key="index" cols="12">
                <v-card class="pl-0 py-0" tile outlined>
                    <v-container class="pl-0 py-0">
                        <v-row justify="space-between">
                            <v-col cols="4" class="ma-0 py-0">
                                <v-img
                                    height="250"
                                    width="auto"
                                    :src="`/storage/images/${article.image_path}`.replace('.jpg','_thumbnail.png')"
                                ></v-img>
                            </v-col>

                            <v-col cols="8" class="py-0">
                                <v-row class="flex-column ma-0 fill-height">
                                    <v-card-title
                                        class="serif-font"
                                        style="font-size:22px"
                                    >{{article.title}}</v-card-title>
                                    <v-card-subtitle class="my-0">
                                        <router-link
                                            class="blue--text subtitle"
                                            :to="`/user/${article.user.id}`"
                                        >{{article.user.name}}</router-link>
                                        <span>-</span>
                                        <span class="black--text">{{article.publishing_date}}</span>
                                        <!-- <span>-</span>
                                        <router-link
                                            class="black--text text-uppercase subtitle"
                                            style="text-decoration:none"
                                            :to="`/category/${article.category.id}`"
                                        >{{article.category.name}}</router-link>-->
                                    </v-card-subtitle>
                                    <v-card-text
                                        class="black--text serif-font pt-0 pb-0"
                                        style="font-size:16px; line-height:1.1; letter-spacing:1px; height:5rem"
                                    >
                                        <div>
                                            {{article.summary.substring(0,250)}} ...
                                            <router-link
                                                :to="`/article/${article.slug}`"
                                            >Continue reading</router-link>
                                        </div>

                                        <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                                    </v-card-text>

                                    <v-card-actions>
                                        <!-- <v-btn color="orange" text 
                                        :to="{ name: 'article_slug', params: { slug: article.slug }}">Lexo</v-btn>-->
                                        <v-btn
                                            color="orange"
                                            text
                                            :to="`/article/${article.slug}`"
                                        >Read</v-btn>

                                        <!-- <v-btn color="orange" text>Share</v-btn> -->
                                        <span class="black--text ml-auto mr-3 mb-1">
                                            <v-icon color="black">mdi-comment-outline</v-icon>
                                            <span class="caption mb-3">{{article.comments_count}}</span>
                                        </span>
                                        <v-btn
                                            depressed
                                            small
                                            color="white"
                                            fab
                                            class="mr-2 mb-2"
                                            v-if="$auth.user().bookmarks.includes(article.id)"
                                            v-on:click="toggle_bookmark(article.id)"
                                        >
                                            <v-icon color="red">mdi-bookmark</v-icon>
                                        </v-btn>
                                        <v-btn
                                            depressed
                                            small
                                            color="white"
                                            fab
                                            class="mr-2 mb-2"
                                            v-else
                                            v-on:click="toggle_bookmark(article.id)"
                                        >
                                            <v-icon>mdi-bookmark-outline</v-icon>
                                        </v-btn>
                                    </v-card-actions>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
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
            resource: {},
            resource_relations: {
                articles: []
            }
        };
    },
    computed: {},
    methods: {
        toggle_bookmark(article_id) {
            console.log(this.$auth.user().bookmarks);
            console.log(article_id);
            let currentObj = this;
            if (currentObj.$auth.user().bookmarks.includes(article_id)) {
                axios
                    .post("auth/toggle_bookmark", { article_id: article_id })
                    .then(function(resp) {
                        currentObj.$auth.user().bookmarks = currentObj.$auth
                            .user()
                            .bookmarks.filter(bm => bm !== article_id);
                    })
                    .catch(function(resp) {
                        console.log(resp);
                    });
            } else {
                axios
                    .post("auth/toggle_bookmark", { article_id: article_id })
                    .then(function(resp) {
                        // currentObj.$auth.user().bookmarks =
                        currentObj.$auth.user().bookmarks.push(article_id);
                    })
                    .catch(function(resp) {
                        console.log(resp);
                    });
            }
        }
    },
    beforeMount: function() {
        let currentObj = this;
        axios
            .get(`/tag/${currentObj.id}`)
            .then(function(resp) {
                currentObj.resource = resp.data.resource;
                currentObj.resource_relations = resp.data.resource_relations;
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
