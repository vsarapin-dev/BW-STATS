<template>
    <v-col cols="12" xs="12" md="6">
        <v-text-field label="Enemy best MMR" dense type="number" v-model="enemyBestMmr"></v-text-field>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyBestMmr: {
            get() { return this.$store.getters['dialog/enemyBestMmr'] },
            set(value) { this.$store.commit('dialog/SET_ENEMY_BEST_MMR', value) },
        },
    },
}
</script>