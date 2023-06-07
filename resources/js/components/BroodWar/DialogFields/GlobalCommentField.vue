<template>
    <v-textarea
        v-model="globalComment"
        label="Global comment"
        no-resize rows="2"
        class="ml-3 mr-3"
        >
    </v-textarea>
</template>
<script>
export default {
    computed: {
        globalComment: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/globalComment`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_GLOBAL_COMMENT`, value) },
        },
    },
}
</script>
