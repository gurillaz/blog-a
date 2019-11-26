<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Tags</span>
                <p class="caption">Tags(all): {{total}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    v-on:click="add_new_resource=true"
                >Add new tag</v-btn>
            </v-col>
        </v-row>

        <v-row>
            <!-- <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>-->
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="tags_headers"
                        :items="resources"
                        :hide-default-footer="true"
                        :loading="loading"
                    >
                        <template v-slot:item.desc="{ item }">
                            <span>{{item.description!=null&&item.description.length>30?item.description.substr(0,30)+'...':item.description}}</span>

                            <!-- <span>{{item.description.substr(0,30)}}...</span> -->
                        </template>
                                      <template v-slot:item.action="{ item }">

                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="red"
                                    v-on:click="delete_resource(item.id)"
                                >Delete</v-btn>
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="orange"
                                    v-on:click="delete_resource(item.id)"
                                >Edit</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn
                                    tile
                                    text
                                    small
                                    link
                                    :to="`/admin/user/${item.id}`"
                                    target="_blank"
                                >Show</v-btn>
                            </v-row>
                        </template>
                    </v-data-table>
                    <v-pagination
                        class="mt-5 mb-5 ml-auto"
                        v-model="page"
                        :length="pageCount"
                        @input="onPageChange"
                    ></v-pagination>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            add_new_resource: false,
            loading: false,
            search: "",
            page: 1,
            pageCount: 1,
            itemsPerPage: 1,
            total: 0,
            tags_headers: [
                { text: "Name", value: "name" },
                { text: "Description", value: "desc" },
                { text: "Articles No", value: "articles_count" },
                { text: "Created by", value: "user.name" },
                { text: "Created at", value: "created_at" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],

            resources: [],
            saving_errors: []
        };
    },
    computed: {},

    methods: {
        delete_resource(resource_id) {
            let currentObj = this;
            axios
                .delete(`/user/${user_id}`)
                .then(function(resp) {
                    currentObj.resources = currentObj.resources.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        onPageChange() {
            let currentObj = this;
            axios
                .get(`/tag?page=${currentObj.page}`)
                .then(function(resp) {
                    currentObj.resources = resp.data.data.data;
                    currentObj.total = resp.data.data.total;
                    currentObj.pageCount = resp.data.data.last_page;
                    currentObj.itemsPerPage = resp.data.data.per_page;
                    currentObj.page = resp.data.data.current_page;

                    // console.log(currentObj.pageCount);
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
            .get("/tag")
            .then(function(resp) {
                currentObj.resources = resp.data.data.data;
                currentObj.total = resp.data.data.total;

                currentObj.pageCount = resp.data.data.last_page;
                currentObj.itemsPerPage = resp.data.data.per_page;
                currentObj.page = resp.data.data.current_page;

                // console.log(currentObj.pageCount);
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
