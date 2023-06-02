<template>
    <v-col cols="12" xs="12" md="6">
        <v-text-field label="Enemy current MMR" dense type="number" v-model="enemyCurrentMmr"></v-text-field>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyCurrentMmr: {
            get() { return this.$store.getters['dialog/enemyCurrentMmr'] },
            set(value) { this.$store.commit('dialog/SET_ENEMY_CURRENT_MMR', value) },
        },
    },
}
</script>