<template>
    <v-row>
        <v-col cols="12" xs="12" md="6">
            <v-text-field label="From MMR" dense type="number"
                          v-model="enemyMinMmr"></v-text-field>
        </v-col>
        <v-col cols="12" xs="12" md="6">
            <v-text-field label="To MMR" dense type="number"
                          v-model="enemyMaxMmr"></v-text-field>
        </v-col>
    </v-row>
</template>
<script>
export default {
    computed: {
        enemyMinMmr: {
            get() { return this.$store.getters['dialogFilter/enemyMinMmr']},
            set(value) { this.$store.commit('dialogFilter/SET_ENEMY_MIN_MMR', value) },
        },
        enemyMaxMmr: {
            get() { return this.$store.getters['dialogFilter/enemyMaxMmr']},
            set(value) { this.$store.commit('dialogFilter/SET_ENEMY_MAX_MMR', value) },
        },
    },
}
</script>
