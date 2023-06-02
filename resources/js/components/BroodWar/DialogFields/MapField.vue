<template>
    <v-col cols="12" xs="12" md="6">
        <v-autocomplete
            v-model="mapSelected"
            :items="maps"
            :error-messages="mapError.length > 0 ? [mapError] : []"
            label="Map"
            item-text="name"
            item-value="id"
            dense
            >
            <template v-slot:selection="{ item, index }">
                <span v-if="index === 0">
                    <span>{{ item.name }}</span>
                </span>
                <span v-if="index === 1" class="grey--text text-caption ml-2 mt-1">
                    (+{{ mapSelected.length - 1 }} others)
                </span>
            </template>
        </v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        mapSelected: {
            get() { return this.$store.getters['dialog/mapSelected'] },
            set(value) { this.$store.commit('dialog/SET_MAP_SELECTED', value) },
        },
        maps: {
            get() { return this.$store.getters['dialog/maps'] },
        },
        mapError: {
            get() { return this.$store.getters['dialog/mapError'] },
            set(value) { this.$store.commit('dialog/SET_MAP_ERROR', value) },
        },
    },
}
</script>