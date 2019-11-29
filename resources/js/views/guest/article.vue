<template>
    <div>
        <v-row>
            <v-col cols="12">
                <v-card tile outlined>
                    <v-card-title class="serif-font mx-5" style="font-size:28px">{{resource.title}}</v-card-title>

                    <v-card-subtitle class="my-1 mx-5">
                        <router-link
                            class="blue--text subtitle"
                            :to="`/user/${resource.user.id}`"
                        >{{resource.user.name}}</router-link>
                        <span>-</span>
                        <span class="black--text">{{resource.publishing_date}}</span>
                    </v-card-subtitle>
                    <v-card tile color="indigo lighten-5" flat class="mx-5">
                        <v-card-text
                            class="black--text serif-font"
                            style="font-size:16px; line-height:1.3; letter-spacing:1px;"
                            v-html="resource.summary"
                        >
                            <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                        </v-card-text>
                        <v-card-text>
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="white"
                                :to="`/category/${resource.category.id}`"
                            >
                                <span class="black--text">{{resource.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="white"
                                v-for="(tag, index) in resource.tags"
                                :key="index"
                                :to="`/tag/${tag.id}`"
                            >
                                <span class="black--text">#{{tag.name}}</span>
                            </v-chip>
                        </v-card-text>
                    </v-card>
                    <v-card-text>
                        <v-img
                            class="px-1n"
                            width="auto"
                            height="auto"
                            :src="`/storage/images/${resource.image_path}`"
                        ></v-img>
                        <div class="mx-5 my-12">
                            <v-card-text
                                class="black--text serif-font"
                                style="font-size:16px; line-height:1.3; letter-spacing:1px"
                                v-html="resource.body"
                            >
                                <!-- <div>Whitsunday Island, Whitsunday Islands</div> -->
                            </v-card-text>
                        </div>
                        <v-divider class="mt-12 mx-12"></v-divider>
                        <v-card-subtitle class="my-1 mx-5">
                            <router-link
                                class="blue--text subtitle"
                                :to="`/user/${resource.user.id}`"
                            >{{resource.user.name}}</router-link>
                            <span>-</span>
                            <span class="black--text">{{resource.publishing_date}}</span>
                        </v-card-subtitle>
                        <v-card-text class="mx-5">
                            <p class="caption mb-0 pb-0">Category:</p>
                            <v-chip
                                label
                                class="ma-1"
                                color="indigo lighten-5"
                                :to="`/category/${resource.category.id}`"
                            >
                                <span class="black--text">{{resource.category.name}}</span>
                            </v-chip>
                            <p class="caption mt-2">Tags:</p>
                            <v-chip
                                small
                                label
                                class="ma-1"
                                color="indigo lighten-5"
                                v-for="(tag, index) in resource.tags"
                                :key="index"
                                :to="`/tag/${tag.id}`"
                            >
                                <span class="black--text">#{{tag.name}}</span>
                            </v-chip>
                        </v-card-text>
                        <!-- <v-card-actions>
                                        <v-btn
                                            color="orange"
                                            text
                                            :to="`/article/${resource.slug}`"
                                        >Read</v-btn>

                                        <v-btn
                                            depressed
                                            small
                                            color="white"
                                            fab
                                            class="ml-auto mr-2 mb-2"
                                        >
                                            <v-icon>mdi-bookmark-outline</v-icon>
                                        </v-btn>
                        </v-card-actions>-->
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <!-- <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Featured</p>
            </v-col>-->
            <v-col cols="12">
                <v-card class="mx-auto" tile outlined>
                    <v-card-text>
                        <v-container fluid>
                            <v-row>
                                <v-col cols="12">
                                    <p
                                        class="pb-0 mb-0 headline black--text font-weight-bold"
                                    >Comments</p>
                                </v-col>
                            </v-row>
                            <v-row v-if="$auth.check()==false">
                                <v-col cols="12" class="pb-0 mb-0">
                                    <v-card tile outlined class="mb-6 py-5">
                                        <v-card-text class="text-center">
                                            <router-link class="font-weight-bold" to="/login">Login</router-link>
                                            <span class="font-weight-bold">or</span>
                                            <router-link
                                                class="font-weight-bold"
                                                to="/register"
                                            >Register</router-link>
                                            <span class="font-weight-bold">to add comment.</span>
                                            <!-- <v-btn text small color="orange"></v-btn> -->
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col cols="11" class="pb-0 mb-0">
                                    <v-textarea
                                        v-model="new_comment.body"
                                        outlined
                                        label="Add new comment"
                                    ></v-textarea>
                                </v-col>

                                <v-col cols="1">
                                    <v-btn
                                        block
                                        color="primary"
                                        small
                                        dark
                                        @click="add_new_comment()"
                                    >Save</v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <v-card
                                        outlined
                                        v-for="(comment,index) in sorted_comments"
                                        :key="index"
                                        class="my-3"
                                    >
                                        <v-card-subtitle
                                            class="pl-3 pt-3 font-weight-bold black--text"
                                        >
                                            <!-- User - 12/12/12 12:12 -->
                                            <template v-if="comment.user!=null">
                                                {{comment.user.name}}
                                                <span
                                                    class="mx-1"
                                                    v-if="comment.user.id == resource.id || comment.user.role == 'admin' "
                                                >
                                                    <span
                                                        v-if="comment.user.id == resource.id"
                                                        class="green--text small"
                                                    >OP</span>
                                                    <span
                                                        v-if="comment.user.role == 'admin'"
                                                        class="red--text small"
                                                    >Admin</span>
                                                </span>
                                            </template>
                                            <template v-else>User</template>
                                            <span
                                                class="caption grey--text"
                                            >- {{comment.created_at}}</span>
                                        </v-card-subtitle>
                                        <v-card-text>
                                            <p class="black--text">{{comment.body}}</p>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-btn
                                                text
                                                small
                                                class="ml-auto"
                                                :disabled="!(comment.user_id == $auth.user().id || $auth.user().role == 'admin') "
                                                v-on:click="show_reply_form_name = comment.id"
                                            >Reply</v-btn>
                                            <v-btn
                                                text
                                                small
                                                class="mr-1"
                                                :disabled="!(comment.user_id == $auth.user().id || $auth.user().role == 'admin') || comment.user == null "
                                                v-on:click="delete_comment(comment.id)"
                                            >Delete</v-btn>
                                            <v-btn
                                                text
                                                small
                                                class="mr-1"
                                                :disabled="!(comment.user_id == $auth.user().id || $auth.user().role == 'admin') || comment.user == null "
                                                v-on:click="show_edit_comment_dialog(comment)"
                                            >Edit</v-btn>
                                        </v-card-actions>
                                        <v-row class="mx-3" v-if="show_reply_form_name==comment.id">
                                            <v-col cols="11" class="pb-0 mb-0">
                                                <v-textarea
                                                    v-model="new_reply.body"
                                                    outlined
                                                    label="Add new reply"
                                                ></v-textarea>
                                            </v-col>

                                            <v-col cols="1">
                                                <v-btn
                                                    block
                                                    color="primary"
                                                    small
                                                    dark
                                                    @click="add_new_reply(comment.id)"
                                                >Save</v-btn>
                                                <v-btn
                                                    class="mt-3"
                                                    block
                                                    outlined
                                                    color="red"
                                                    small
                                                    dark
                                                    @click="show_reply_form_name = ''"
                                                >Cancel</v-btn>
                                            </v-col>
                                        </v-row>
                                        <v-card
                                            outlined
                                            color="grey lighten-4"
                                            v-for="(reply,index) in comment.replies"
                                            :key="index"
                                            class="my-3 ml-5 mr-3"
                                        >
                                            <v-card-subtitle
                                                class="pl-3 pt-3 font-weight-bold black--text"
                                            >
                                                <!-- User - 12/12/12 12:12 -->
                                                <template v-if="reply.user!=null">
                                                    {{reply.user.name}}
                                                    <span
                                                        class="mx-1"
                                                        v-if="reply.user.id == resource.id || reply.user.role == 'admin' "
                                                    >
                                                        <span
                                                            v-if="reply.user.id == resource.id"
                                                            class="green--text small"
                                                        >OP</span>
                                                        <span
                                                            v-if="reply.user.role == 'admin'"
                                                            class="red--text small"
                                                        >Admin</span>
                                                    </span>
                                                    <span
                                                        class="caption grey--text"
                                                    >- {{reply.created_at}}</span>
                                                </template>
                                                <template v-else>User</template>
                                            </v-card-subtitle>
                                            <v-card-text>
                                                <p class="black--text">{{reply.body}}</p>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-btn
                                                    text
                                                    small
                                                    class="ml-auto"
                                                    :disabled="!(reply.user_id == $auth.user().id || $auth.user().role == 'admin') || reply.user == null "
                                                    v-on:click="delete_reply(comment.id,reply.id)"
                                                >Delete</v-btn>
                                                <v-btn
                                                    text
                                                    small
                                                    class="mr-1"
                                                    :disabled="!(reply.user_id == $auth.user().id || $auth.user().role == 'admin') || reply.user == null "
                                                    v-on:click="show_edit_reply_dialog(reply,comment.id)"
                                                >Edit</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog v-model="edit_comment_dialog" persistent max-width="50vw">
            <v-card>
                <v-container>
                    <v-row class="mx-3">
                        <v-col cols="10" class="pb-0 mb-0">
                            <v-textarea v-model="edit_comment.body" outlined label="Edit comment"></v-textarea>
                        </v-col>

                        <v-col cols="2">
                            <v-btn block color="primary" small dark @click="update_comment()">Save</v-btn>
                            <v-btn
                                class="mt-3"
                                block
                                outlined
                                color="red"
                                small
                                dark
                                @click="edit_comment_dialog=false"
                            >Cancel</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="edit_replay_dialog" persistent max-width="50vw">
            <v-card>
                <v-container>
                    <v-row class="mx-3">
                        <v-col cols="10" class="pb-0 mb-0">
                            <v-textarea v-model="edit_comment.body" outlined label="Edit comment"></v-textarea>
                        </v-col>

                        <v-col cols="2">
                            <v-btn block color="primary" small dark @click="update_reply()">Save</v-btn>
                            <v-btn
                                class="mt-3"
                                block
                                outlined
                                color="red"
                                small
                                dark
                                @click="edit_replay_dialog=false"
                            >Cancel</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    props: ["slug"],
    data() {
        return {
            new_comment: {
                body: "",
                commentable_id: "",
                commentable_type: ""
            },

            show_reply_form_name: "",
            new_reply: {
                body: "",
                commentable_id: "",
                commentable_type: ""
            },

            // resource: [],

            resource: {
                id: "",
                slug: "",
                title: "",
                summary: "",
                body: "",
                image_path: "",
                category_id: "",
                user_id: "",
                publishing_date: "",
                claps: "",
                meta_status: "",
                "meta_is-feature": null,
                "meta_open-new-window": null,
                meta_list_place: null,
                deleted_at: null,
                created_at: "",
                updated_at: "",
                user: [
                    {
                        id: "",
                        name: ""
                    }
                ],
                category: {
                    id: "",
                    name: ""
                },
                comments: [],
                tags: []
            },

            edit_comment_dialog: false,
            edit_replay_dialog: false,
            edit_comment: {
                parent_comment_id: "",
                id: "",
                body: ""
            }
        };
    },
    computed: {
        sorted_comments() {
            return this.resource.comments.sort(function(a, b) {
                let a_date = Date.parse(a.created_at);
                let b_date = Date.parse(b.created_at);
                return b_date - a_date;
            });
        }
    },
    methods: {
        show_edit_comment_dialog(comment) {
            let currentObj = this;
            currentObj.edit_comment.body = comment.body;
            currentObj.edit_comment.id = comment.id;
            currentObj.edit_comment_dialog = true;
        },
        update_comment: function() {
            let currentObj = this;

            axios
                .put(
                    `/comment/${currentObj.edit_comment.id}`,
                    currentObj.edit_comment
                )
                .then(function(resp) {
                    currentObj.resource.comments.filter(
                        comment => comment.id == currentObj.edit_comment.id
                    )[0].body = currentObj.edit_comment.body;

                    currentObj.edit_comment = {
                        id: "",
                        body: ""
                    };
                    currentObj.edit_comment_dialog = false;
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        show_edit_reply_dialog(reply, parent_comment_id) {
            let currentObj = this;
            currentObj.edit_comment.parent_comment_id = parent_comment_id;
            currentObj.edit_comment.body = reply.body;
            currentObj.edit_comment.id = reply.id;
            currentObj.edit_replay_dialog = true;
        },
        update_reply: function() {
            let currentObj = this;

            axios
                .put(
                    `/comment/${currentObj.edit_comment.id}`,
                    currentObj.edit_comment
                )
                .then(function(resp) {
                    currentObj.resource.comments
                        .filter(
                            comment =>
                                comment.id ==
                                currentObj.edit_comment.parent_comment_id
                        )[0]
                        .replies.filter(
                            rep => rep.id == currentObj.edit_comment.id
                        )[0].body = currentObj.edit_comment.body;

                    currentObj.edit_comment = {
                        parent_comment_id: "",
                        id: "",
                        body: ""
                    };
                    currentObj.edit_replay_dialog = false;
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        add_new_comment() {
            let currentObj = this;
            currentObj.new_comment.user_id = currentObj.$auth.user().id;
            currentObj.new_comment.commentable_type = "App\\Article";
            currentObj.new_comment.commentable_id = currentObj.resource.id;

            // alert(currentObj.new_comment);

            axios
                .post(`/comment`, currentObj.new_comment)
                .then(function(resp) {
                    // currentObj.saving_errors = false;

                    // console.log(resp.data.comment);
                    currentObj.resource.comments.push(resp.data.comment);
                    currentObj.new_comment = {
                        body: "",
                        commentable_id: "",
                        commentable_type: ""
                    };

                    // currentObj.$store.dispatch("showSnackbar", {
                    //     color: "success",
                    //     text: "Te dhenat u ruajten!"
                    // });
                })
                .catch(function(resp) {
                    console.log(resp.data);
                });
        },
        add_new_reply(parent_comment_id) {
            let currentObj = this;
            currentObj.new_reply.user_id = currentObj.$auth.user().id;
            currentObj.new_reply.commentable_type = "App\\Comment";
            currentObj.new_reply.commentable_id = parent_comment_id;

            // console.log(currentObj.new_reply);
            // return;
            // alert(currentObj.new_comment);

            axios
                .post(`/comment`, currentObj.new_reply)
                .then(function(resp) {
                    // currentObj.saving_errors = false;
                    // console.log(resp.data.comment)
                    // console.log(resp.data.comment);
                    currentObj.resource.comments
                        .filter(comm => comm.id == parent_comment_id)[0]
                        .replies.unshift(resp.data.comment);
                    currentObj.new_reply = {
                        body: "",
                        commentable_id: "",
                        commentable_type: ""
                    };
                    currentObj.show_reply_form_name = "";

                    // currentObj.$store.dispatch("showSnackbar", {
                    //     color: "success",
                    //     text: "Te dhenat u ruajten!"
                    // });
                })
                .catch(function(resp) {
                    console.log(resp.data);
                });
        },
        delete_reply(parent_comment_id, reply_id) {
            let currentObj = this;
            if (confirm("Delete comment?") === false) {
                return;
            }
            axios(`/comment/${reply_id}`, {
                method: "delete"
            })
                .then(function(resp) {
                    let comment = currentObj.resource.comments
                        .filter(comment => comment.id == parent_comment_id)[0]
                        .replies.filter(rep => rep.id == reply_id);
                    comment.user = null;
                    comment.body = "[Deleted]";
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        },
        delete_comment(deleted_comment_id) {
            let currentObj = this;
            if (confirm("Delete comment?") === false) {
                return;
            }
            axios(`/comment/${deleted_comment_id}`, {
                method: "delete"
            })
                .then(function(resp) {
                    let comment = currentObj.resource.comments.filter(
                        comment => comment.id == deleted_comment_id
                    )[0];
                    comment.user = null;
                    comment.body = "[Deleted]";
                })
                .catch(function(resp) {
                    console.log(resp);
                });
        }
    },
    beforeMount: function() {
        // console.log(this.$auth.user())
        let currentObj = this;
        axios
            .get(`/guest/article/slug/${currentObj.slug}`)
            .then(function(resp) {
                // console.log(resp.data);

                currentObj.resource = resp.data.resource;

                // currentObj.belongs_to = resp.data.belongs_to;
                // currentObj.created_by = resp.data.created_by;
                // currentObj.model = resp.data.model;
            })
            .catch(function(resp) {
                console.log(resp);
                // currentObj.$router.push({name:'main.error'});
            });
    }
};
</script>
