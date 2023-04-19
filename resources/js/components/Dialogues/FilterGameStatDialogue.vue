<template>
    <v-row justify="center">
        <v-dialog
            v-model="show"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5 ml-3">Filters</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xs="12">
                                <v-autocomplete
                                    v-model="enemyLogin"
                                    :items="enemyLogins"
                                    item-text="enemy_login"
                                    label="Enemy login"
                                    dense
                                    :search-input.sync="enemyLoginSearch"
                                    :loading="findLoginLoader"
                                ></v-autocomplete>
                            </v-col>
                        </v-row>
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
                        <v-row>
                            <v-col
                                cols="12"
                                xs="12"
                                md="6"
                            >
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
                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption ml-2 mt-1"
                                        >
                                          (+{{ mapSelected.length - 1 }} others)
                                        </span>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col
                                cols="12"
                                xs="12"
                                md="6"
                            >
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
                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption ml-2 mt-1"
                                        >
                                          (+{{ myRaceSelected.length - 1 }} others)
                                        </span>
                                    </template>
                                </v-autocomplete>
                            </v-col>
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
                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption ml-2 mt-1"
                                        >
                                          (+{{ enemyRaceSelected.length - 1 }} others)
                                        </span>
                                    </template>
                                </v-autocomplete>
                            </v-col>
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
                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption ml-2"
                                        >
                                          (+{{ enemyRandomRaceSelected.length - 1 }} others)
                                        </span>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-textarea
                                v-model="resultComment"
                                label="Result comment"
                                no-resize
                                rows="2"
                                class="ml-3 mr-3"
                            >
                            </v-textarea>
                        </v-row>
                        <v-row>
                            <v-textarea
                                v-model="globalComment"
                                label="Global comment"
                                no-resize
                                rows="2"
                                class="ml-3 mr-3"
                            >
                            </v-textarea>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="show = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="filterStats"
                    >
                        Filter
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import API from "../../api";

export default {
    name: "FilterGameStatDialogue",
    props: ['filterVisible', 'seasonId'],
    data() {
        return {
            findNicknameLoader: false,
            findLoginLoader: false,
            enemyMinMmr: 0,
            enemyMaxMmr: 0,
            resultComment: '',
            enemyLoginSearch: '',
            globalComment: '',
            mapSelected: '',
            resultSelected: '',
            myRaceSelected: '',
            enemyRandomRaceSelected: '',
            enemyRaceSelected: '',
            enemyLogin: '',
            maps: [],
            gameResults: [],
            enemyLogins: [],
            myRaces: [],
            enemyRaces: [],

            enemyRaceError: '',
            mapError: '',
            myRaceError: '',
            resultError: '',
            enemyRandomRaceError: '',
        }
    },
    watch: {
        enemyLoginSearch(val) {
            this.findLoginLoader = true;
            this.getLogin(val);
        },
    },
    computed: {
        show: {
            get() {
                if (this.filterVisible === true) {
                    this.resetValues();
                    this.getMapsFilters();
                    this.getRacesFilters();
                    this.getResultsFilters();
                }
                return this.filterVisible
            },
            set(value) {
                if (!value) {
                    this.$emit('close')
                }
            }
        },
        season: {
            get() {
                return this.seasonId
            },
            set(value) {
                this.seasonId = value;
            }
        },
        shouldEnableRandomRace: {
            get() {
                if ((this.enemyRaceSelected !== null &&
                    this.enemyRaces.filter(i => i.name === 'Random').length > 0 &&
                    this.enemyRaceSelected === this.enemyRaces.filter(i => i.name === 'Random')[0].id) === false) {

                    this.enemyRandomRaceSelected = '';

                    return true;
                }
                return false;
            },
            set(value) {
            },
        },
    },
    methods: {
        getLogin(value) {
            API.post('/api/auth/filters/find-logins', {
                enemy_login: value,
                season_id: this.season,
            })
                .then(res => {
                    this.findLoginLoader = false;
                    this.enemyLogins = res.data.enemy_logins;
                })
                .catch(e => {
                    this.findLoginLoader = false;
                    console.log(e.response);
                })
        },
        filterStats() {
            let data = {
                enemy_min_mmr: this.enemyMinMmr,
                enemy_max_mmr: this.enemyMaxMmr,
                enemy_login: this.enemyLogin,
                map_id: this.mapSelected,
                result_comment: this.resultComment,
                global_comment: this.globalComment,
                result_id: this.resultSelected,
                my_race_id: this.myRaceSelected,
                enemy_random_race_id: this.enemyRandomRaceSelected,
                enemy_race_id: this.enemyRaceSelected,
            };

            data = this.removeEmptyOnFilteredData(data);
            data = this.checkForZerosInMmrFilter(data);
            data = this.convertMmrToNumbers(data);
            this.$parent.filteredDataObject(data);
            this.show = false;
        },
        removeEmptyOnFilteredData(data) {
            let resData = {};
            for (const [key, value] of Object.entries(data)) {
                if (value !== null && value !== "")
                {
                    resData[key] = value;
                }
            }
            return resData;
        },
        checkForZerosInMmrFilter(data)
        {
            if (data.enemy_max_mmr === 0)
            {
                data.enemy_max_mmr = 10000;
            }
            return data;
        },
        convertMmrToNumbers(data)
        {
            data.enemy_min_mmr = Number(data.enemy_min_mmr);
            data.enemy_max_mmr = Number(data.enemy_max_mmr);

            return data;
        },
        getResultsFilters() {
            API.post('/api/auth/filters/results')
                .then(res => {
                    this.gameResults = res.data.results;
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        getMapsFilters() {
            API.post('/api/auth/filters/maps')
                .then(res => {
                    this.maps = res.data.maps;
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        getRacesFilters() {
            API.post('/api/auth/filters/races')
                .then(res => {
                    this.myRaces = res.data.races;
                    this.enemyRaces = res.data.races;
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        resetValues() {
            this.enemyLoginSearch = '';
            this.enemyMinMmr = 0;
            this.enemyMaxMmr = 0;
            this.enemyLogin = '';
            this.resultComment = '';
            this.globalComment = '';
            this.mapSelected = '';
            this.resultSelected = '';
            this.myRaceSelected = '';
            this.enemyRandomRaceSelected = '';
            this.enemyRaceSelected = '';
            this.maps = [];
            this.gameResults = [];
            this.myRaces = [];
            this.enemyRaces = [];
            this.enemyLogins = [];
        }
    },

}
</script>

<style scoped>
>>> .v-list-item__title {
    font-size: 12px !important;
}

>>> .v-select__selections {
    font-size: 12px !important;
}
</style>
