<template>
    <v-col cols="4">
        <v-autocomplete
            v-model="myRaceSelected"
            :items="myRaces"
            :error-messages="myRaceError.length > 0 ? [myRaceError] : []"
            label="My race"
            item-text="name"
            item-value="id"
            dense
            >
            <template v-slot:selection="{ item, index }">
                <span v-if="index === 0">
                    <span>{{ item.name }}</span>
                </span>
                <span v-if="index === 1" class="grey--text text-caption ml-2 mt-1">
                    (+{{ myRaceSelected.length - 1 }} others)
                </span>
            </template>
        </v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        myRaceSelected: {
            get() { return this.$store.getters['dialog/myRaceSelected'] },
            set(value) { this.$store.commit('dialog/SET_MY_RACE_SELECTED', value) },
        },
        myRaces: {
            get() { return this.$store.getters['dialog/myRaces'] },
        },
        myRaceError: {
            get() { return this.$store.getters['dialog/myRaceError'] },
            set(value) { this.$store.commit('dialog/SET_MY_RACE_ERROR', value) },
        },
    },
}
</script>
