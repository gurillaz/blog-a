<template>
    <div>
        <v-row v-if="resources.main_featured.length!=0">
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Featured</p>
            </v-col>
            <v-col cols="12" v-for="(article,index) in resources.main_featured" :key="index">
                <article12main :article="article"></article12main>
            </v-col>
        </v-row>

        <v-row v-if="resources.three_main_featured.length!=0">
            <v-col cols="4" v-for="(article,index) in resources.three_main_featured" :key="index">
                <article4 :article="article"></article4>
            </v-col>
            <!-- <h5>{{resources.featured[0].image_path}}</h5> -->
            <!-- <v-img src="/storage/images/c4f354a1d9a3bfb87ec0e7c49865bcd6.jpg" aspect-ratio="1"></v-img> -->
            <!-- <img src="/storage/images/1af961ba8b8a3955855f89932209a75e.jpg" alt=""> -->
            <!-- C:\Users\ThinkPad T440\Documents\Code\blog-a\public\storage\images\c4f354a1d9a3bfb87ec0e7c49865bcd6.jpg -->
        </v-row>
        <v-row v-if="resources.others_featured.length!=0">
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Others featured</p>
            </v-col>
            <v-col v-for="(article,index) in resources.others_featured" :key="index" cols="12">
                <article12 :article="article"></article12>
            </v-col>
        </v-row>

        <v-row v-if="resources.latest.length!=0">
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Latest</p>
            </v-col>
            <v-col v-for="(article,index) in resources.latest" :key="index" cols="12">
                <article12 :article="article"></article12>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12" class="text-center">
                <p
                    class="pb-0 mb-0 header font-weight-bold"
                >There are not any articles published right now. Check later!</p>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Categories</p>
            </v-col>
            <v-col cols="12">
                <v-row justify="space-between" align="center">
                    <v-col cols="2" v-for="(category, index) in resources.categories" :key="index">
                        <v-card
                            class="ma-3 text-center"
                            outlined
                            hover
                            tile
                            :to="`/category/${category.id}`"
                        >
                            <p
                                class="mt-6 pb-0 mb-0 subtitle font-weight-bold text-uppercase"
                            >{{category.name}}</p>
                            <p class="my-0 py-0 caption">Articles</p>
                            <p
                                class="my-0 py-0 caption font-weight-bold mb-6"
                            >{{category.articles_count}}</p>
                        </v-card>
                    </v-col>
                </v-row>
                <!-- <div class="text-center my-7">
                    <v-chip
                        v-for="(category,index) in resources.categories"
                        :key="index"
                        class="ma-2"
                        label
                    >{{category.name}}</v-chip>
                </div>-->
            </v-col>
        </v-row>
    </div>
</template>
<script>
import article12main from "../common/article_card_12_main";
import article12 from "../common/article_card_12";
import article4 from "../common/article_card_4";
export default {
    components: { article12, article4, article12main },
    data() {
        return {
            resources: {
                main_featured:[],
                three_main_featured:[],
                others_featured:[],
                latest:[],
                categories:[],
            }
        };
    },
    methods: {},
    beforeMount: function() {
        let currentObj = this;
        axios
            .get("/guest/home")
            .then(function(resp) {
                // console.log(resp.data);

                currentObj.resources = resp.data.resources;

                // currentObj.belongs_to = resp.data.belongs_to;
                // currentObj.created_by = resp.data.created_by;
                // currentObj.model = resp.data.model;
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
<style lang="css">
.bottom-gradient {
    background-image: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.4) 0%,
        transparent 72px
    );
}
</style>