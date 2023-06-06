<template>
    <div>
        <v-card max-width="344" class="mx-auto">
            <v-card-text>
                <v-autocomplete
                    :items="$store.getters['excel/seasons']"
                    v-model="selectedSeason"
                    @change="changeSeason"
                    label="Season"
                    outlined
                    dense
                ></v-autocomplete>
                <v-file-input
                    v-model="file"
                    accept=".xlsx"
                    placeholder="Upload your documents"
                    label="File input"
                    prepend-icon="mdi-paperclip"
                >
                    <template v-slot:selection="{ text }">
                        <v-chip
                            small
                            label
                            color="primary"
                        >
                            {{ text }}
                        </v-chip>
                    </template>
                </v-file-input>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="success" @click="importFile">Import</v-btn>
            </v-card-actions>
        </v-card>
        <v-card class="mt-5">
            <v-card-text>
                <v-img
                    src="/img/example.png"
                ></v-img>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    name: "ExcelImport",
    mounted() { this.getSeasons(); },
    computed: {
        selectedSeason: {
            get() { return this.$store.getters['excel/selectedSeason']; },
            set(value) { this.$store.commit('excel/SET_SELECTED_SEASON', value); }
        },
        file: {
            get() { return this.$store.getters['excel/file']; },
            set(value) { this.$store.commit('excel/SET_FILE', value); }
        },
    },
    methods: {
        getSeasons() { this.$store.dispatch('excel/getSeasons'); },
        changeSeason() {  localStorage.setItem('selectedSeason', this.selectedSeason); },
        importFile() { this.$store.dispatch('excel/importFile'); },
    },
}
</script>

<style scoped>
>>> th, >>> tr {
    background-color: black !important;
}
</style>
