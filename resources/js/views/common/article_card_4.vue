    <template>
    <v-card tile class="px-4 pt-3 pb-0" outlined>
        <v-card-title
            class="serif-font pb-0 mb-0"
            style="font-size:18px; line-height:1.1"
        >{{article.title}}</v-card-title>
        <v-card-subtitle class="my-0 py-0">
            <span class="black--text caption">{{article.publishing_date}}</span>
        </v-card-subtitle>
        <v-card-subtitle class="mb-1">
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
            <template v-if="$auth.check()==false">
                <v-btn depressed color="white" fab class="mr-0 mb-1" to="/login">
                    <v-icon color="grey">mdi-bookmark-outline</v-icon>
                </v-btn>
            </template>
            <template v-else>
                <v-btn
                    depressed
                    color="white"
                    fab
                    small
                    class="mb-1"
                    v-if="$auth.user().bookmarks.includes(article.id)"
                    v-on:click="toggle_bookmark(article.id)"
                >
                    <v-icon color="red">mdi-bookmark</v-icon>
                </v-btn>
                <v-btn
                    depressed
                    color="white"
                    fab
                    small
                    class="mb-1"
                    v-else
                    v-on:click="toggle_bookmark(article.id)"
                >
                    <v-icon>mdi-bookmark-outline</v-icon>
                </v-btn>
            </template>
        </v-card-actions>
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
                    .post("/auth/toggle_bookmark", { article_id: article_id })
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
                    .post("/auth/toggle_bookmark", { article_id: article_id })
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
