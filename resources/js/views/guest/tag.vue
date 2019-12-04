<template>
    <div>
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

    },
    beforeMount: function() {
        let currentObj = this;
        axios
            .get(`/guest/tag/${currentObj.id}`)
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
