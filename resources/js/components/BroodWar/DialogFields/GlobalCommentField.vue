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
            get() { return this.$store.getters['dialog/globalComment'] },
            set(value) { this.$store.commit('dialog/SET_GLOBAL_COMMENT', value) },
        },
    },
}
</script>