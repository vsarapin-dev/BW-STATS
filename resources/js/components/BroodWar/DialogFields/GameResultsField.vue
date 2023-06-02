<template>
    <v-col cols="12" xs="12" md="6">
        <v-autocomplete
            v-model="resultSelected"
            :items="gameResults"
            :error-messages="resultError.length > 0 ? [resultError] : []"
            label="Result"
            item-text="name"
            item-value="id"
            dense
            ></v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        resultSelected: {
            get() { return this.$store.getters['dialog/resultSelected'] },
            set(value) { this.$store.commit('dialog/SET_RESULT_SELECTED', value) },
        },
        gameResults: {
            get() { return this.$store.getters['dialog/gameResults'] },
        },
        resultError: {
            get() { return this.$store.getters['dialog/resultError'] },
            set(value) { this.$store.commit('dialog/SET_RESULT_ERROR', value) },
        },
    },
}
</script>