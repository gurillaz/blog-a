    <template>
    <v-card class="pl-0 py-0" tile outlined>
        <v-container class="pl-0 py-0">
            <v-row class="flex-column ma-1 fill-height">
                <v-col cols="12">
                    <v-card-title class="serif-font" style="font-size:18px">{{article.title}}</v-card-title>
                    <v-card-subtitle class="my-0 py-0">
                        <span class="black--text">{{article.publishing_date}}</span>      
                    </v-card-subtitle>
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
                            <router-link :to="`/article/${article.slug}`">Continue reading</router-link>
                        </div>

                        <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                    </v-card-text>

                    <v-card-actions>
                        <!-- <v-btn color="orange" text 
                        :to="{ name: 'article_slug', params: { slug: article.slug }}">Lexo</v-btn>-->
                        <v-btn color="orange" text :to="`/article/${article.slug}`">Read</v-btn>

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
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

    <script>
export default {
    props: ["article"],
    data() {
        return {};
    },
    computed: {},
    methods: {
        toggle_bookmark(article_id) {
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
    }
};
</script>
