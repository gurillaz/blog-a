<template>
    <div>
        <v-row class="mb-12">
            <v-col cols="12">
                <p class="title py-0 my-0">Category</p>
                <span class="headline font-weight-bold py-0 my-0">{{resource.name}}</span>
                <p class="caption">Articles: {{resource_relations.articles.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="font-weight-bold">{{resource.description}}</p>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Category articles</p>
            </v-col>
            <v-col v-for="(article,index) in resource_relations.articles" :key="index" cols="12">
                <article12 :article="article"></article12>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import article12 from "../common/article_card_12";
export default {
    components: { article12 },
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
            .get(`/guest/category/${currentObj.id}`)
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
