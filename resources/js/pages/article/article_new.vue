<template>
    <div>
        <v-card class="pb-5" tile outlined>
            <v-card-title>
                <v-icon left>mdi-newspaper-plus</v-icon>Add new article
                <div class="flex-grow-1"></div>
            </v-card-title>
            <!-- {{data_autofill}} -->
            <v-row class="mx-5">
                <v-container class="pa-1">
                        <v-row class="mb-7 mx-5 mt-1">
                            <v-col cols="12" class="mb-12 text-center">
                                <!-- <v-card tile outlined class="fill-height ma-3"> -->
                    <form enctype="multipart/form-data" ref="image_form">
                                <picture-input
                                    ref="pictureInput"
                                    margin="16"
                                    width="600"
                                    height="400"
                                    accept="image/jpeg, image/png"
                                    size="10"
                                    :removable="true"
                                    button-class="v-btn"
                                    removeButtonClass="v-btn"
                                    :custom-strings="{
        drag: 'Drag here or click to upload article cover photo'
      }"                            name="image"
                                    @change="onChange"
                                ></picture-input>
                    </form>
                                <!-- <strong class="red--text caption" v-if="saving_errors.cover_photo!=undefined">{{saving_errors.cover_photo}}</strong> -->
                                <!-- </v-card> -->
                            </v-col>
                            <v-col cols="9">
                                <v-text-field
                                    label="Title:"
                                    type="text"
                                    v-model="new_article.title"
                                    :error="saving_errors.title != undefined"
                                    :error-messages="saving_errors.title"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="3">
                                <v-autocomplete
                                    label="Category:"

                                    :items="data_autofill.categories"
                                    item-text="name"
                                    item-value="id"
                                    v-model="new_article.category_id"
                                    :error="saving_errors.category_id != undefined"
                                    :error-messages="saving_errors.category_id"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="9">
                                <v-autocomplete
                                    v-model="new_article.tags"
                                    :items="data_autofill.tags"
                                    item-value="id"
                                    item-text="name"
                                    deletable-chips
                                    small-chips
                                    clearable
                                    label="Tags:"

                                    multiple
                                    hide-selected
                                    clearabld
                                    :error="saving_errors.tags != undefined"
                                    :error-messages="saving_errors.tags"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="3">
                                <v-btn block outlined small class="mt-5">Add new tag</v-btn>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="new_article.summary"
                                    outlined
                                    label="Article summary:"

                                    :error="saving_errors.summary != undefined"
                                    :error-messages="saving_errors.summary"
                                    :rows="10"
                                    :auto-grow="true"
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <div class="ml-1">
                            <strong
                                class="red--text caption"
                                v-if="saving_errors.body!=undefined"
                            >{{saving_errors.body}}</strong>
                        </div>
                        <v-card outlined tile>
                            <!-- <v-card-title class="pt-1 pl-2">Article</v-card-title> -->

                            <!-- <v-row class="pa-7"> -->
                            <!-- <v-col cols="12"> -->

                            <tiptap-vuetify
                                placeholder="Enter article body here..."
                                v-model="new_article.body"
                                :extensions="extensions"
                                :toolbar-attributes="{ extended: true }"
                                :card-props="{style:'min-height:90vh',tile:true,flat:true}"
                            />

                            <!-- </v-col> -->
                            <!-- <div v-html="new_article.body"></div> -->
                            <!-- </v-row> -->
                        </v-card>
                </v-container>
            </v-row>
            <v-row class="mx-5">
                <v-col cols="12">
                    <v-btn
                        color="primary"
                        block
                        large
                        class="mt-5"
                        v-on:click="submit_data"
                    >Submit article</v-btn>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
import {
    TiptapVuetify,
    Heading,
    Bold,
    Italic,
    Strike,
    Underline,
    Code,
    Paragraph,
    BulletList,
    OrderedList,
    ListItem,
    Link,
    Blockquote,
    HardBreak,
    HorizontalRule,
    History
} from "tiptap-vuetify";
import PictureInput from "vue-picture-input";
import { stringify } from 'querystring';
// import axios from '@websanova/vue-auth/drivers/http/axios.1.x';
export default {
    components: { TiptapVuetify, PictureInput },

    data() {
        return {
            extensions: [
                History,
                Blockquote,
                Link,
                Underline,
                Strike,
                Italic,
                ListItem,
                BulletList,
                OrderedList,
                [
                    Heading,
                    {
                        options: {
                            levels: [1, 2, 3]
                        }
                    }
                ],
                Bold,
                Link,
                Code,
                HorizontalRule,
                Paragraph,
                HardBreak
            ],

            new_article: {
                title: "asdasdasdasd",
                summary: "asdasdasdasdasdas",
                body: "asdasdasdasdsada",
                category_id: "",
                tags: [],
                cover_photo: null
            },
            saving_errors: [],

            data_autofill: {
                tags: [],
                categories: []
            }
        };
    },
    computed: {},
    methods: {
        set_body(text) {
            this.new_article.body = text;
        },
        onChange(image) {
            console.log("New picture selected!");
            if (image) {
                console.log("Picture loaded.");
                this.image = image;
            } else {
                console.log(
                    "FileReader API not supported: use the <form>, Luke!"
                );
            }
        },
        submit_data() {

            console.log(this.new_article.tags);
            // return;
            let currentObj = this;
            // currentObj.new_article.cover_photo = currentObj.image;
            let formdata = new FormData(this.$refs['image_form']);
            formdata.append("body", currentObj.new_article.body);
            formdata.append("title", currentObj.new_article.title);
            formdata.append("summary", currentObj.new_article.summary);
            currentObj.new_article.tags.forEach(tag => {
                
                formdata.append("tags[]", tag);
            });
                

            formdata.append("category_id", currentObj.new_article.category_id);

            axios
                .post("/article",formdata , {
                    headers: {
                        "content-type": "multipart/form-data",
                    }
                })
                .then(function(resp) {
                    // console.log(this.data.resource_id);
                    // currentObj.$router.push('/article/'+resp.data.resource_slug)
                })
                .catch(function(resp) {
                    currentObj.saving_errors = resp.response.data.errors;
                });
        }
    },
    beforeMount: function() {
        let currentObj = this;
        axios
            .get("/article/create")
            .then(function(resp) {
                currentObj.data_autofill = resp.data.data_autofill;
            })
            .catch(function(resp) {
                console.log(resp);
            });
    }
};
</script>
