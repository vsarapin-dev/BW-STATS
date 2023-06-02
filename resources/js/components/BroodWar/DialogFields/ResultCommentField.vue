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
            get() { return this.$store.getters['dialog/resultComment'] },
            set(value) { this.$store.commit('dialog/SET_RESULT_COMMENT', value) },
        },
    },
}
</script>