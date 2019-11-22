<template>
    <div>
        <v-row>
            <p>asdasdasdad</p>
        </v-row>
        <v-row>
            <!-- <v-col cols="12">
                <p class="pb-0 mb-0 headline font-weight-bold">Featured</p>
            </v-col>-->
            <v-col cols="12">
                <v-card class="fill-height"></v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>

export default {
    data() {
        return {

            resources: [],
            data_autofill: []
        };
    },
    computed: {

    },
    methods: {

    },
    beforeMount: function() {
        // console.log(this.$auth.user())
        let currentObj = this;
        // axios
        //     .get(`/article/slug/${currentObj.slug}`)
        //     .then(function(resp) {
        //         // console.log(resp.data);

        //         currentObj.resource = resp.data.resource;
        //         currentObj.resource_relations = resp.data.resource_relations;
        //         currentObj.data_autofill = resp.data.data_autofill;

        //         // currentObj.belongs_to = resp.data.belongs_to;
        //         // currentObj.created_by = resp.data.created_by;
        //         // currentObj.model = resp.data.model;
        //     })
        //     .catch(function(resp) {
        //         console.log(resp);
        //     });
    }
};
</script>
