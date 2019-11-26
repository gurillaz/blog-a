<template>
    <v-row>
        <h1>Admin dashboard</h1>
    </v-row>
</template>

<script>
export default {
    data() {
        return {

        };
    },
    computed: {

    },
    methods: {

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
