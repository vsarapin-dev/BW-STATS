<template>
    <v-col cols="4">
        <v-autocomplete
            v-model="enemyRaceSelected"
            :items="enemyRaces"
            :error-messages="enemyRaceError.length > 0 ? [enemyRaceError] : []"
            label="Enemy race"
            item-text="name"
            item-value="id"
            dense
            >
            <template v-slot:selection="{ item, index }">
                <span v-if="index === 0">
                    <span>{{ item.name }}</span>
                </span>
                <span v-if="index === 1" class="grey--text text-caption ml-2 mt-1">
                    (+{{ enemyRaceSelected.length - 1 }} others)
                </span>
            </template>
        </v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyRaceSelected: {
            get() { return this.$store.getters['dialog/enemyRaceSelected'] },
            set(value) { this.$store.commit('dialog/SET_ENEMY_RACE_SELECTED', value) },
        },
        enemyRaces: {
            get() { return this.$store.getters['dialog/enemyRaces'] },
        },
        enemyRaceError: {
            get() { return this.$store.getters['dialog/enemyRaceError'] },
            set(value) { this.$store.commit('dialog/SET_ENEMY_RACE_ERROR', value) },
        },
    },
}
</script>