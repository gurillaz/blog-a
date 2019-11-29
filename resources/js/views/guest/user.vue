<template>
    <v-container>
        <v-row class="mb-12">
            <v-col cols="12">
                <span class="headline">{{resource.name}}</span>
                <p
                    class="caption"
                >Articles: {{resource_relations.articles.length}} Comments: {{resource_relations.comments.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
        </v-row>
        <v-row
            v-if="resource_relations.articles.length != 0 || resource_relations.latest_article.length !=0"
        >
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Latest Article</p>
            </v-col>
            <v-col cols="12">
                <v-card
                    class="pl-0 py-0"
                    tile
                    outlined
                    v-for="(article,index) in resource_relations.latest_article"
                    :key="index"
                >
                    <v-container class="pl-0 py-0">
                        <v-row justify="space-between">
                            <v-col cols="7" class="ma-0 py-0">
                                <v-img
                                    width="auto"
                                    height="auto"
                                    :src="`/storage/images/${article.image_path}`"
                                ></v-img>
                            </v-col>

                            <v-col cols="5" class>
                                <v-row class="flex-column ma-0 fill-height">
                                    <v-card-title
                                        class="serif-font"
                                        style="font-size:28px;height:5.1rem"
                                    >{{article.title}}</v-card-title>
                                    <v-card-subtitle class="my-1">
                                        <router-link
                                            class="blue--text subtitle"
                                            :to="`/user/${article.user.id}`"
                                        >{{article.user.name}}</router-link>
                                        <span>-</span>
                                        <router-link
                                            class="black--text text-uppercase subtitle"
                                            style="text-decoration:none"
                                            :to="`/category/${article.category.id}`"
                                        >{{article.category.name}}</router-link>
                                    </v-card-subtitle>
                                    <v-card-text
                                        class="black--text serif-font mb-0 pb-0"
                                        style="font-size:16px; line-height:1.3; letter-spacing:1px; height:8rem"
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
                                        <v-btn
                                            depressed
                                            small
                                            color="white"
                                            fab
                                            class="ml-auto mr-2 mb-2"
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
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Posted Articles</p>
            </v-col>
            <v-col cols="4" v-for="(article,index) in resource_relations.articles" :key="index">
                <v-card class="pl-0 py-0" tile outlined>
                    <v-container class="pl-0 py-0">
                        <v-row class="flex-column ma-1 fill-height">
                            <v-col cols="12">
                                <v-card-title
                                    class="serif-font"
                                    style="font-size:24px;height:7rem"
                                >{{article.title}}</v-card-title>
                                <v-card-subtitle class="my-1">
                                    <router-link
                                        class="blue--text subtitle"
                                        :to="`/user/${article.user.id}`"
                                    >{{article.user.name}}</router-link>
                                    <span>-</span>
                                    <router-link
                                        class="black--text text-uppercase subtitle"
                                        style="text-decoration:none"
                                        :to="`/category/${article.category.id}`"
                                    >{{article.category.name}}</router-link>
                                </v-card-subtitle>
                                <v-card-text
                                    class="black--text serif-font"
                                    style="font-size:14px; line-height:1.2; letter-spacing:1px; height:10rem"
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
                                    <v-btn color="orange" text :to="`/article/${article.slug}`">Read</v-btn>

                                    <!-- <v-btn color="orange" text>Share</v-btn> -->
                                    <v-btn
                                        depressed
                                        small
                                        color="white"
                                        fab
                                        class="ml-auto mr-2 mb-2"
                                    >
                                        <v-icon>mdi-bookmark-outline</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Articles</p>
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="subtitle grey--text my-5">User hasn't posted any articles yet.</p>
            </v-col>
        </v-row>

        <v-row v-if="resource_relations.comments.length !=0">
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>
            <v-col cols="12">
                <v-data-table
                    :headers="headers"
                    :items="resource_relations.comments"
                    class="elevation-1"
                >
                    <template v-slot:item.comment_body="{ item }">
                        <span>{{item.body.substr(0,40)}}...</span>
                    </template>
                    <template v-slot:item.comment_type="{ item }">
                        <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                        <span v-else>Comment</span>
                    </template>
                    <template v-slot:item.article_title="{ item }">
                        <span>{{item.article.title}}</span>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-row>
                            <v-btn
                                tile
                                text
                                small
                                link
                                :to="`/article/${item.article.slug}`"
                                target="_blank"
                            >Show</v-btn>
                        </v-row>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="subtitle grey--text my-5">User hasn't posted any comments yet.</p>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: ["id"],
    data() {
        return {
            headers: [
                { text: "Comment", value: "comment_body" },
                { text: "Type", value: "comment_type" },
                { text: "Article", value: "article_title" },
                { text: "Date", value: "updated_at" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],
            resource: {},
            resource_relations: {
                articles: [],
                comments: [],
                latest_article: []
            }
        };
    },
    computed: {},
    methods: {},
    beforeMount: function() {
        let currentObj = this;
        axios
            .get(`/user/${currentObj.id}`)
            .then(function(resp) {
                currentObj.resource = resp.data.resource;
                currentObj.resource_relations = resp.data.resource_relations;
            })
            .catch(function(resp) {
                             console.log(resp);
                currentObj.$router.push({name:'main.error'});
            });
    }
};
</script>
