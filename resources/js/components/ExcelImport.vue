<template>
    <div>
        <Loader v-if="loading"/>
        <v-card max-width="344" class="mx-auto">
            <SnackBar
                :visible="snackBarOpened"
                @close="snackBarOpened = false"
            />
            <v-card-text>
                <v-autocomplete
                    :items="seasons"
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
    </div>
</template>

<script>
import API from "../api";
import SnackBar from "./SnackBar";
import Loader from "./Loader";

export default {
    name: "ExcelImport",
    data() {
        return {
            file: null,
            seasons: null,
            selectedSeason: null,
            snackBarOpened: false,
            loading: false,
        }
    },
    components: {
        SnackBar,
        Loader,
    },
    mounted() {
        this.getSeasons();
    },
    methods: {
        getSeasons() {
            API.post('/api/auth/seasons')
                .then(res => {
                    this.seasons = res.data.seasons.map(i => i.season);
                    this.selectedSeason = localStorage.hasOwnProperty('selectedSeason') ?
                        this.seasons[parseInt(localStorage.getItem('selectedSeason')) - 10]:
                        this.seasons[0];
                })
                .catch(e => {
                    console.log(e)
                })
        },
        changeSeason() {
            localStorage.setItem('selectedSeason', this.selectedSeason);
        },
        importFile() {
            if (this.file === null) return;
            this.loading = true;

            let formData = new FormData();
            formData.append('excel_file', this.file);
            formData.append('season_number', this.selectedSeason);
            API.post('/api/auth/import-excel-file', formData)
                .then(res => {
                    console.log(res);
                    this.loading = false;
                    this.snackBarOpened = true;
                    this.file = null;
                })
                .catch(e => {
                    this.loading = false;
                    console.log(e.response.data)
                })
        },
    },
}
</script>

<style scoped>
>>> th, >>> tr {
    background-color: black !important;
}
</style>
