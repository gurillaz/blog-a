<template>
    <v-container>
        <v-row class>
            <v-col cols="10">
                <span class="headline">Users</span>
                <p class="caption">Users(all): {{total}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <!-- <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    v-on:click="user_settings_dialog=true"
                >User settings</v-btn>
            </v-col>-->
        </v-row>

        <v-row>
            <!-- <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>-->
            <v-col cols="12">
                <v-card>
                    <v-data-table
                        :headers="users_header"
                        :items="resources"
                        :search="search"
                        :hide-default-footer="true"
                        :loading="loading"
                    >
                        <template v-slot:item.action="{ item }">
                            <v-row class="text-right">
                                <v-btn
                                    tile
                                    text
                                    small
                                    color="red"
                                    v-on:click="delete_user(item.id)"
                                >Delete</v-btn>
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
            loading: false,
            search: "",
            page: 1,
            pageCount: 1,
            itemsPerPage: 1,
            total:0,
            users_header: [
                { text: "Name", value: "name" },
                { text: "Email", value: "email" },
                { text: "Articles no", value: "articles_count" },
                { text: "Comments no", value: "comments_count" },
                { text: "Role", value: "role" },
                { text: "Created at", value: "created_at" },
                // { text: "Date", value: "updated_at" },
                // { text: "Status", value: "meta_status" },
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
        delete_user(user_id) {
            let currentObj = this;
            axios
                .delete(`/user/${user_id}`)
                .then(function(resp) {
                    currentObj.resources = currentObj.resources.filter(user => {
                        return user.id != user_id;
                    });
                    // console.log(currentObj.pageCount);
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        onPageChange() {
            let currentObj = this;
            axios
                .get(`/user?page=${currentObj.page}`)
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
            .get("/user")
            .then(function(resp) {
                currentObj.resources = resp.data.data.data;
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
