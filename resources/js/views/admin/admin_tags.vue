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
                    v-on:click="new_resource_dialog=true"
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
                                    v-on:click="show_resource_edit(item.id)"
                                >Edit</v-btn>
                                <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                                <v-btn
                                    tile
                                    text
                                    small
                                    link
                                    v-on:click="show_resource_view(item.id)"
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
        <v-row justify="center" v-if="show_resource_dialog">
            <v-dialog v-model="show_resource_dialog" max-width="70vw">
                <v-card outlined>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <p class="title py-0 my-0">Tag</p>
                                <span
                                    class="headline font-weight-bold py-0 my-0"
                                >#{{show_resource.name}}</span>
                                <p
                                    class="caption"
                                >Articles: {{show_resource_relations.articles.length}}</p>
                                <!-- <span class="title">{{$auth.user().name}}</span> -->
                            </v-col>
                            <v-col cols="12" class="text-center">
                                <p class="font-weight-bold">{{show_resource.description}}</p>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="10"></v-col>
                            <v-col cols="2">
                                <v-btn
                                    tile
                                    small
                                    block
                                    v-on:click="show_resource_dialog = false"
                                >Close</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-row>
        <v-row justify="center">
            <v-dialog v-model="edit_resource_dialog" max-width="50vw">
                <v-card outlined>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="edit_resource.name"
                                    label="Name:*"
                                    :error="saving_errors.name != undefined"
                                    :error-messages="saving_errors.name"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    rows="10"
                                    outlined
                                    v-model="edit_resource.description"
                                    label="Description:"
                                    :error="saving_errors.description != undefined"
                                    :error-messages="saving_errors.description"
                                    required
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="8"></v-col>
                            <v-col cols="2">
                                <v-btn
                                    tile
                                    small
                                    block
                                    v-on:click="edit_resource_dialog = false"
                                >Close</v-btn>
                            </v-col>
                            <v-col cols="2">
                                <v-btn
                                    tile
                                    small
                                    color="green"
                                    block
                                    v-on:click="update_resource()"
                                >Save</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-row>
        <v-row justify="center">
            <v-dialog v-model="new_resource_dialog" max-width="50vw">
                <v-card outlined>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="new_resource.name"
                                    label="Name:*"
                                    :error="saving_errors.name != undefined"
                                    :error-messages="saving_errors.name"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    rows="10"
                                    outlined
                                    v-model="new_resource.description"
                                    label="Description:"
                                    :error="saving_errors.description != undefined"
                                    :error-messages="saving_errors.description"
                                    required
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="8"></v-col>
                            <v-col cols="2">
                                <v-btn
                                    tile
                                    small
                                    block
                                    v-on:click="new_resource_dialog = false"
                                >Close</v-btn>
                            </v-col>
                            <v-col cols="2">
                                <v-btn
                                    tile
                                    small
                                    color="green"
                                    block
                                    v-on:click="create_resource()"
                                >Save</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-dialog>
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
            saving_errors: [],

            show_resource: [],
            show_resource_realtions: [],
            show_resource_dialog: false,

            edit_resource_dialog: false,
            edit_resource: {
                name: "",
                description: ""
            },

            new_resource_dialog: false,
            new_resource: {
                name: "",
                description: ""
            }
        };
    },
    computed: {},

    methods: {
        show_resource_edit(resource_id) {
            let currentObj = this;
            axios
                .get(`/admin/tag/${resource_id}`)
                .then(function(resp) {
                    // console.log(resp.data);

                    currentObj.edit_resource = resp.data.resource;

                    currentObj.edit_resource_dialog = true;

                    // currentObj.belongs_to = resp.data.belongs_to;
                    // currentObj.created_by = resp.data.created_by;
                    // currentObj.model = resp.data.model;
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                });
        },
        show_resource_view(resource_id) {
            let currentObj = this;
            axios
                .get(`/admin/tag/${resource_id}`)
                .then(function(resp) {
                    // console.log(resp.data);

                    currentObj.show_resource = resp.data.resource;
                    currentObj.show_resource_relations =
                        resp.data.resource_relations;
                    currentObj.show_resource_dialog = true;

                    // currentObj.belongs_to = resp.data.belongs_to;
                    // currentObj.created_by = resp.data.created_by;
                    // currentObj.model = resp.data.model;
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                });
        },
        delete_resource(resource_id) {
            let currentObj = this;

            if (!confirm("Delete tag?")) {
                return;
            }
            axios
                .delete(`/admin/tag/${resource_id}`)
                .then(function(resp) {
                    // currentObj.resources =
                    currentObj.resources = currentObj.resources.filter(
                        resource => {
                            return resource.id != resource_id;
                        }
                    );
                })
                .catch(function(resp) {
                    alert("Something went wrong!");
                    console.log(resp);
                });
        },
        update_resource() {
            let currentObj = this;

            if (!confirm("Update tag?")) {
                return;
            }
            axios
                .put(
                    `/admin/tag/${currentObj.edit_resource.id}`,
                    currentObj.edit_resource
                )
                .then(function(resp) {
                    // currentObj.resources =
                    currentObj.resources.forEach(function(resource, index) {
                        if (resource.id == currentObj.edit_resource.id) {
                            currentObj.resources[index].name =
                                currentObj.edit_resource.name;
                            currentObj.resources[index].description =
                                currentObj.edit_resource.description;
                        }
                    });

                    currentObj.edit_resource = {
                        name: "",
                        description: ""
                    };
                    currentObj.edit_resource_dialog = false;
                })
                .catch(function(resp) {
                    currentObj.saving_errors = resp.response.data.errors;

                    console.log(resp);
                });
        },
        create_resource() {
            let currentObj = this;
            currentObj.saving_errors = [];
            axios
                .post(`/admin/tag`, currentObj.new_resource)
                .then(function(resp) {
                    // currentObj.resources =
                    currentObj.resources.unshift(resp.data.resource);

                    currentObj.new_resource = {
                        name: "",
                        description: ""
                    };
                    currentObj.new_resource_dialog = false;
                })
                .catch(function(resp) {
                    currentObj.saving_errors = resp.response.data.errors;

                    console.log(resp);
                });
        },
        onPageChange() {
            let currentObj = this;
            axios
                .get(`/admin/tag?page=${currentObj.page}`)
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
            .get("/admin/tag")
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
