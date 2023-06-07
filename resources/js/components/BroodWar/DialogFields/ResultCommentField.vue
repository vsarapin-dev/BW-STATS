<template>
    <v-textarea
        v-model="resultComment"
        label="Result comment"
        no-resize rows="2"
        class="ml-3 mr-3"
        >
    </v-textarea>
</template>
<script>
export default {
    computed: {
        resultComment: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/resultComment`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_RESULT_COMMENT`, value) },
        },
    },
}
</script>
