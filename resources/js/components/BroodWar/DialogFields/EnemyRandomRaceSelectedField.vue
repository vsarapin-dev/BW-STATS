<template>
    <v-col cols="4">
        <v-autocomplete
            :disabled="shouldEnableRandomRace"
            v-model="enemyRandomRaceSelected"
            :items="enemyRaces.filter(i => i.name !== 'Random')"
            :error-messages="enemyRandomRaceError.length > 0 ? [enemyRandomRaceError] : []"
            label="Random race"
            item-text="name"
            item-value="id"
            dense
            >
            <template v-slot:selection="{ item, index }">
                <span v-if="index === 0">
                    <span>{{ item.name }}</span>
                </span>
                <span v-if="index === 1" class="grey--text text-caption ml-2">
                    (+{{ enemyRandomRaceSelected.length - 1 }} others)
                </span>
            </template>
        </v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyRaces: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/enemyRaces`] },
        },
        enemyRandomRaceSelected: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/enemyRandomRaceSelected`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_ENEMY_RANDOM_RACE_SELECTED`, value) },
        },
        enemyRandomRaceError: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/enemyRandomRaceError`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_ENEMY_RANDOM_RACE_ERROR`, value) },
        },
        enemyRaceSelected: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/enemyRaceSelected`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_ENEMY_RACE_SELECTED`, value) },
        },
        shouldEnableRandomRace: {
            get() {
                if ((this.enemyRaceSelected !== null &&
                    this.enemyRaces.filter(i => i.name === `Random`).length > 0 &&
                    this.enemyRaceSelected === this.enemyRaces.filter(i => i.name === `Random`)[0].id) === false) {

                        this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_ENEMY_RANDOM_RACE_SELECTED`, ``);
                        return true;
                }
                return false;
            },
            set(value) {},
        },
    },
}
</script>
