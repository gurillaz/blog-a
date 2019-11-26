<template>
    <v-container>
        <v-row class="mb-12">
            <v-col cols="10">
                <span class="headline">{{resource.name}}</span>
                <p
                    class="caption"
                >Articles: {{resource_relations.articles.length}} Comments: {{resource_relations.comments.length}}</p>
                <!-- <span class="title">{{$auth.user().name}}</span> -->
            </v-col>
            <v-col cols="2">
                <v-btn
                    block
                    small
                    tile
                    color="primary"
                    dark
                    v-on:click="user_settings_dialog=true"
                >User settings</v-btn>
            </v-col>
        </v-row>
        <v-row
            v-if="resource_relations.articles.length != 0 || resource_relations.latest_article.length !=0"
        >
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Latest Article</p>
            </v-col>
            <v-col
                cols="12"
                v-for="(article,index) in resource_relations.latest_article"
                :key="index"
            >
                <article12main :article="article"></article12main>
            </v-col>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Posted Articles</p>
            </v-col>

            <v-col cols="12">
                <v-data-table
                    :headers="articles_headers"
                    :items="resource_relations.articles"
                    class="elevation-1"
                >
                    <template v-slot:item.action="{ item }">
                        <v-row>
                            <v-btn tile text small v-on:click="alert('edit')">Delete</v-btn>
                            <v-btn tile text small :to="`/article/${item.id}/edit`">Edit</v-btn>

                            <v-btn
                                tile
                                text
                                small
                                link
                                :to="`/article/${item.slug}`"
                                target="_blank"
                            >Show</v-btn>
                        </v-row>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Articles</p>
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="subtitle grey--text my-5">User hasn't posted any articles yet.</p>
            </v-col>
        </v-row>

        <v-row v-if="resource_relations.comments.length !=0">
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>
            <v-col cols="12">
                <v-data-table
                    :headers="comments_headers"
                    :items="resource_relations.comments"
                    class="elevation-1"
                >
                    <template v-slot:item.comment_body="{ item }">
                        <span>{{item.body.substr(0,30)}}...</span>
                    </template>
                    <template v-slot:item.comment_type="{ item }">
                        <span v-if="item.commentable_type=='App\\Comment'">Reply</span>
                        <span v-else>Comment</span>
                    </template>
                    <template v-slot:item.article_title="{ item }">
                        <span>{{item.article.title.substr(0,30)}}...</span>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-row class="text-right">
                            <v-btn tile text small v-on:click="delete_comment(item.id)">Delete</v-btn>
                            <!-- <v-btn tile text small v-on:click="alert('edit')">Edit</v-btn> -->

                            <v-btn
                                tile
                                text
                                small
                                link
                                :to="`/article/${item.article.slug}`"
                                target="_blank"
                            >Show</v-btn>
                        </v-row>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-row v-else>
            <v-col cols="12">
                <p class="pb-0 mb-0 subtitle font-weight-bold">Comments</p>
            </v-col>
            <v-col cols="12" class="text-center">
                <p class="subtitle grey--text my-5">User hasn't posted any comments yet.</p>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-dialog v-model="user_settings_dialog" persistent max-width="50vw">
                <v-card>
                    <v-card-title>
                        <span class="headline">User settings</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" class="py-0">
                                    <p class="text-uppercase caption py-0 my-0">Name and email</p>
                                </v-col>
                                <v-col cols="4">
                                    <v-text-field
                                        v-model="edit_resource.name"
                                        label="Name*"
                                        :error="saving_errors.name != undefined"
                                        :error-messages="saving_errors.name"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="8">
                                    <v-text-field
                                        v-model="edit_resource.email"
                                        label="Email*"
                                        :error="saving_errors.email != undefined"
                                        :error-messages="saving_errors.email"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" class="py-0 mt-5">
                                    <p class="text-uppercase caption py-0 my-0">Change password</p>
                                </v-col>
                                <v-col cols="12" class="pt-0">
                                    <v-text-field
                                        label="Passwoord:"
                                        v-model="password"
                                        type="password"
                                        :error="saving_errors.password != undefined"
                                        :error-messages="saving_errors.password"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" class="pt-0">
                                    <v-text-field
                                        label="Repeat password:"
                                        v-model="password_r"
                                        type="password"
                                        :error="password_error"
                                        :error-messages="password_error?'Password does not match!':''"
                                        :success-messages="!password_error && password_r!=null?'Password does match!':''"
                                        :success="password_r!=null && password==password_r"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                        <small>*indicates required field</small>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="user_settings_dialog = false"
                        >Close</v-btn>
                        <v-btn color="blue darken-1" text @click="update_resource">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </v-container>
</template>

<script>
import article12main from "./article_card_12_main";
export default {
    components: { article12main },
    data() {
        return {
            user_settings_dialog: false,
            password: null,
            password_r: null,
            comments_headers: [
                { text: "Comment", value: "comment_body" },
                { text: "Article", value: "article_title" },
                { text: "Date", value: "updated_at" },
                { text: "Status", value: "meta_status" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],
            articles_headers: [
                { text: "Title", value: "title" },
                // { text: "Slug", value: "slug" },
                { text: "Created at", value: "created_at" },
                { text: "Published at", value: "publishing_date" },
                { text: "Status", value: "meta_status" },
                {
                    text: "Action",
                    // align: "right",
                    sortable: false,
                    value: "action"
                }
            ],
            edit_resource: {},
            resource: {},
            resource_relations: {
                latest_article: [],
                articles: [],
                comments: []
            },
            saving_errors: []
        };
    },
    computed: {
        password_error() {
            return this.password_r != null && this.password != this.password_r;
        }
    },
    methods: {
        update_resource: function() {
            let currentObj = this;

            if (currentObj.password != currentObj.password_r) {
                alert("Password does not match!");
                return;
            }

            let data = {};
            Object.keys(currentObj.edit_resource).forEach(function(prop) {
                if (
                    currentObj.edit_resource[prop] != "" ||
                    currentObj.edit_resource[prop] != null
                ) {
                    data[prop] = currentObj.edit_resource[prop];
                }
            });

            if (data["email"] == currentObj.resource.email) {
                delete data["email"];
            }
            if (currentObj.password != null || currentObj.password != "") {
                data["password"] = currentObj.password;
            }
            console.log(data);
            axios
                .put(`/auth/edit/${currentObj.resource.id}`, data)
                .then(function(resp) {
                    currentObj.saving_errors = [];
                    currentObj.resource = resp.data.user;

                    /* Using JSON.parse to copy object, since just asignin resp.data.note only references data
                    note end edit_note keep changing when used as vue v-model
                    Based on: https://scotch.io/bar-talk/copying-objects-in-javascript
                     */
                    currentObj.edit_resource = JSON.parse(
                        JSON.stringify(resp.data.user)
                    );
                    currentObj.user_settings_dialog = false;
                    // currentObj.user_settings_dialog = false;
                })
                .catch(function(resp) {
                    currentObj.saving_errors = resp.response.data.errors;
                    console.log(resp);
                });
        },
        delete_comment(comment_id) {
            let currentObj = this;

            if (confirm("Confirm comment deletion!") === false) {
                return;
            }
            axios(`/comment/${comment_id}`, {
                method: "delete"
            })
                .then(function(resp) {
                    currentObj.resource_relations.comments = currentObj.resource_relations.comments.filter(
                        comment => comment.id != comment_id
                    );

                    alert("Comment deleted!");
                })
                .catch(function(resp) {
                    alert("Comment not deleted!");
                });
        }
    },
    beforeMount: function() {
        let currentObj = this;
        axios
            .get("/auth_user")
            .then(function(resp) {
                currentObj.resource = resp.data.resource;
                currentObj.resource_relations = resp.data.resource_relations;
                currentObj.edit_resource = JSON.parse(
                    JSON.stringify(resp.data.resource)
                );
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
