    <template>
    <div>
        <v-row></v-row>
        <v-row class="pt-0 mt-0">
            <v-col cols="12">
                <v-card outlined tile>
                    <v-row class="pa-5">
                        <v-col cols="12" class="py-0">
                            <v-text-field
                                v-model="query.search_term"
                                label="Type here to search for posts"
                                hint="Type anything and select filters below"
                                x-large
                                persistent-hint
                                solo
                                :error="query_errors.search_term"
                                :error-messages="query_errors.search_term"
                            ></v-text-field>
                        </v-col>

                        <template v-if="show_filter">
                            <v-col cols="3" class="py-0">
                                <v-autocomplete
                                    v-model="query.sort_by"
                                    :items="order_by"
                                    item-value="value"
                                    item-text="name"
                                    clearable
                                    label="Sort by:"
                                    :error-messages="query_errors.sort_by"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="3" class="py-0">
                                <v-autocomplete
                                    v-model="query.user_id"
                                    :items="data_autofill.users"
                                    item-value="id"
                                    item-text="name"
                                    clearable
                                    label="From user:"
                                    :error-messages="query_errors.user_id"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <v-autocomplete
                                    v-model="query.category_id"
                                    :items="data_autofill.categories"
                                    item-value="id"
                                    item-text="name"
                                    clearable
                                    label="Has category:"
                                    :error-messages="query_errors.category_id"
                                ></v-autocomplete>
                            </v-col>

                            <v-col cols="3" class="py-0">
                                <v-menu
                                    v-model="dp_start"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="query.date_start"
                                            label="Date published after:"
                                            clearable
                                            :error-messages="query_errors.date_start"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="query.date_start"
                                        no-title
                                        @input="dp_start = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="3" class="py-0">
                                <v-menu
                                    v-model="dp_end"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="query.date_end"
                                            label="Date published before:"
                                            :error-messages="query_errors.date_end"
                                            clearable
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="query.date_end"
                                        no-title
                                        @input="dp_end = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <v-autocomplete
                                    v-model="query.tags"
                                    :items="data_autofill.tags"
                                    item-value="id"
                                    item-text="name"
                                    deletable-chips
                                    small-chips
                                    clearable
                                    label="Tags:"
                                    multiple
                                    hide-selected
                                    :error-messages="query_errors.tags"
                                ></v-autocomplete>
                            </v-col>
                        </template>
                        <v-col cols="8"></v-col>
                        <v-col cols="1" class="py-0 mt-2">
                            <v-btn block text v-on:click="clear_all()">Clear</v-btn>
                        </v-col>
                        <v-col cols="1" class="py-0 mt-2">
                            <v-btn block text v-on:click="show_filter=!show_filter">Filters</v-btn>
                        </v-col>

                        <v-col cols="2" class="py-0 mt-2">
                            <v-btn
                                block
                                outlined
                                v-on:click="get_results()"
                                :disabled="loading==true"
                            >Search</v-btn>
                        </v-col>
                    </v-row>
                    <v-progress-linear
                        :active="loading"
                        :indeterminate="loading"
                        absolute
                        bottom
                        color="primary accent-4"
                    ></v-progress-linear>
                </v-card>
            </v-col>
        </v-row>

        <v-row class="mt-12" v-if="query.search_term === '' && results.length==0">
            <v-col cols="12" class="text-center">
                <p class="title grey--text">Enter something to search!</p>
            </v-col>
        </v-row>
        <v-row class="mt-12" v-if="query.search_term !== '' && results.length == 0">
            <v-col cols="12" class="text-center">
                <p class="title grey--text">
                    Results for
                    <span class="black--text">"{{query.search_term}}"</span>.
                </p>
            </v-col>
        </v-row>
        <v-row class="mb-12" v-if="results.length != 0">
            <v-col cols="12">
                <p class="title py-0 my-0">Search results</p>
            </v-col>
            <v-col v-for="(article,index) in results" :key="index" cols="12">
                <article12 :article="article"></article12>
            </v-col>
            <v-col cols="12">
                <v-btn
                    block
                    outlined
                    color="black"
                    dark
                    tile
                    v-on:click="next_page()"
                    :disabled="next_page_url == null"
                >Load more</v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import article12 from "../common/article_card_12";
export default {
    components: { article12 },
    data() {
        return {
            loading: false,
            dp_start: false,
            dp_end: false,
            show_filter: false,
            next_page_url: null,
            results: [],
            query: {
                search_term: "",
                user_id: "",
                category_id: "",
                date_start: "",
                date_end: "",
                tags: [],
                sort_by: ""
            },
            order_by: [
                { name: "Title", value: "title" },
                { name: "Publishing date", value: "publishing_date" },
                { name: "Authors Name", value: "user_name" },
                { name: "Category Name", value: "category_name" },
                { name: "No. Comments", value: "comments_count" }
            ],
            data_autofill: {
                users: [],
                categories: [],
                tags: []
            },

            query_errors: {
                search_term: ""
            }
        };
    },
    computed: {},
    methods: {
        clear_all() {
            let currentObj = this;
            currentObj.query = {
                search_term: "",
                date_start: "",
                date_end: "",
                category: "",
                user: "",
                tags: [],
                sort_by: ""
            };
            currentObj.query_errors = { search_term: [] };
        },
        get_results() {
            let currentObj = this;
            if (currentObj.query.search_term.length == 0) {
                currentObj.query_errors.search_term = [
                    "The search term field is required."
                ];
                return;
            }
            currentObj.results = [];
            currentObj.query_errors = { search_term: [] };

            let data = {};

            Object.keys(currentObj.query).forEach(function(prop) {
                if (
                    currentObj.query[prop] &&
                    currentObj.query[prop].length !== 0
                ) {
                    data[prop] = currentObj.query[prop];
                }
            });

            axios
                .get(`/guest/search`, {
                    params: data
                })
                .then(function(resp) {
                    currentObj.results = resp.data.results.data;

                    // currentObj.results = resp.data.data;
                    currentObj.next_page_url = resp.data.results.next_page_url;
                    currentObj.query_errors = { search_term: [] };
                })
                .catch(function(resp) {
                    currentObj.query_errors = resp.response.data.errors;
                    console.log(resp);
                });
        },
        next_page() {
            let currentObj = this;
            axios
                .get(currentObj.next_page_url)
                .then(function(resp) {
                    // currentObj.results = currentObj.results.push(resp.data.data);
                    // currentObj.results = currentObj.results.push(resp.data.results.data);
                    currentObj.results = currentObj.results.concat(
                        resp.data.results.data
                    );
                    currentObj.next_page_url = resp.data.results.next_page_url;
                })
                .catch(function(resp) {
                    console.log(resp);
                });
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
            .get(`/guest/search/data_autofill`)
            .then(function(resp) {
                currentObj.data_autofill = resp.data.data_autofill;
                // currentObj.resource_relations = resp.data.resource_relations;
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
