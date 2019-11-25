<template>
    <div>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Featured</p>
            </v-col>
            <v-col cols="12">
                <v-card
                    class="pl-0 py-0"
                    tile
                    outlined
                    v-for="(article,index) in resources.latest_featured"
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
            <v-col cols="4" v-for="(article,index) in resources.others_featured" :key="index">
                <v-card
                    class="pl-0 py-0"
                    tile
                    outlined
                    v-for="(article,index) in resources.latest_featured"
                    :key="index"
                >
                    <v-container class="pl-0 py-0">
                        <v-row class="flex-column ma-1 fill-height">
                            <v-col cols="12">
                                <v-card-title
                                    class="serif-font"
                                    style="font-size:28px"
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
            <!-- <h5>{{resources.featured[0].image_path}}</h5> -->
            <!-- <v-img src="/storage/images/c4f354a1d9a3bfb87ec0e7c49865bcd6.jpg" aspect-ratio="1"></v-img> -->
            <!-- <img src="/storage/images/1af961ba8b8a3955855f89932209a75e.jpg" alt=""> -->
            <!-- C:\Users\ThinkPad T440\Documents\Code\blog-a\public\storage\images\c4f354a1d9a3bfb87ec0e7c49865bcd6.jpg -->
        </v-row>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Latest</p>
            </v-col>
                   <v-col v-for="(article,index) in resource.latest" :key="index" cols="12">
                <article12 :article="article"></article12>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Categories</p>
            </v-col>
            <v-col cols="12">
                <v-row justify="space-between" align="center">
                    <v-col cols="2" v-for="(category, index) in resources.categories" :key="index">
                        <v-card class="ma-3 text-center" outlined hover tile :to="`/category/${category.id}`">
                            <p
                                class="mt-6 pb-0 mb-0 subtitle font-weight-bold text-uppercase"
                            >{{category.name}}</p>
                            <p class="my-0 py-0 caption">Articles</p>
                            <p class="my-0 py-0 caption font-weight-bold mb-6">{{category.articles_count}}</p>
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
export default {
    data() {
        return {
            resources: []
        };
    },
    beforeMount: function() {
        let currentObj = this;
        axios
            .get("/article")
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