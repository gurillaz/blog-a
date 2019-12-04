<template>
    <div>
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
            <v-col
                cols="12"
                v-for="(article,index) in resource_relations.latest_article"
                :key="index"
            >
                <article12main :article="article"></article12main>
            </v-col>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Posted Articles</p>
            </v-col>
            <v-col cols="4" v-for="(article,index) in resource_relations.articles" :key="index">
                <article4 :article="article"></article4>
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
    </div>
</template>

<script>
import article12main from "../common/article_card_12_main";

import article4 from "../common/article_card_4";
export default {
    components: { article4, article12main },
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
            .get(`/guest/user/${currentObj.id}`)
            .then(function(resp) {
                currentObj.resource = resp.data.resource;
                currentObj.resource_relations = resp.data.resource_relations;
            })
            .catch(function(resp) {
                console.log(resp);
                currentObj.$router.push({ name: "main.error" });
            });
    }
};
</script>
