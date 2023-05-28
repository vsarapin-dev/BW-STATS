<template>
    <v-row justify="center">
        <v-dialog
            v-model="show"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5 ml-3">{{ headerName }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xs="12" md="6">
                                <v-text-field label="Enemy nickname" dense v-model="enemyNickname"></v-text-field>
                            </v-col>
                            <v-col cols="12" xs="12" md="6">
                                <v-text-field label="Enemy login" dense v-model="enemyLogin"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" xs="12" md="6">
                                <v-text-field label="Enemy current MMR" dense type="number" v-model="enemyCurrentMmr"></v-text-field>
                            </v-col>
                            <v-col cols="12" xs="12" md="6">
                                <v-text-field label="Enemy best MMR" dense type="number" v-model="enemyBestMmr"></v-text-field>
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
                        v-if="!updateRow"
                        color="blue darken-1"
                        text
                        @click="storeStats"
                    >
                        Save
                    </v-btn>
                    <v-btn
                        v-if="updateRow"
                        color="blue darken-1"
                        text
                        @click="storeStats"
                    >
                        Update
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import API from "../../../api";

export default {
    name: "CreateNewGameStatRowDialogue",
    props: ['visible', 'headerName', 'updateRow'],
    data() {
        return {
            enemyCurrentMmr: 0,
            enemyBestMmr: 0,
            resultComment: '',
            globalComment: '',
            mapSelected: '',
            resultSelected: '',
            myRaceSelected: '',
            enemyRandomRaceSelected: '',
            enemyRaceSelected: '',
            enemyNickname: '',
            enemyLogin: '',
            maps: [],
            gameResults: [],
            myRaces: [],
            enemyRaces: [],

            enemyRaceError: '',
            mapError: '',
            myRaceError: '',
            resultError: '',
            enemyRandomRaceError: '',
        }
    },
    computed: {
        show: {
            get() {
                if (this.visible === true) {
                    this.resetValues();
                    this.getMapsFilters();
                    this.getRacesFilters();
                    this.getResultsFilters();
                }
                return this.visible
            },
            set(value) {
                if (!value) {
                    this.$emit('close')
                }
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
            set(value) {},
        },
    },
    methods: {
        storeStats() {
            this.resetErrors();
            this.$parent.storeData({
                enemy_current_mmr: this.enemyCurrentMmr,
                enemy_max_mmr: this.enemyBestMmr,
                enemy_nickname: this.enemyNickname,
                enemy_login: this.enemyLogin,
                map_id: this.mapSelected,
                result_comment: this.resultComment,
                global_comment: this.globalComment,
                result_id: this.resultSelected,
                my_race_id: this.myRaceSelected,
                enemy_random_race_id: this.enemyRandomRaceSelected,
                enemy_race_id: this.enemyRaceSelected,
            });
        },
        getResultsFilters() {
            API.post('/api/auth/filters/results')
                .then(res => {
                    this.gameResults = res.data.results;
                    console.log(this.gameResults)
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        getMapsFilters() {
            API.post('/api/auth/filters/maps')
                .then(res => {
                    this.maps = res.data.maps;
                    console.log(this.maps)
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
        showErrors(error) {
            if (error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                Object.keys(error.response.data.errors).map(i => {
                    if (i === 'enemy_race_id') {
                        this.enemyRaceError = "Enemy race is required";
                    }
                    if (i === 'map_id') {
                        this.mapError = "Map is required";
                    }
                    if (i === 'my_race_id') {
                        this.myRaceError = "Race is required";
                    }
                    if (i === 'result_id') {
                        this.resultError = error.response.data.errors.result_id[0];
                    }
                    if (i === 'enemy_random_race_id') {
                        this.enemyRandomRaceError = "Race is required if random selected";
                    }
                })
            }
        },
        resetErrors() {
            this.enemyRaceError = '';
            this.mapError = '';
            this.myRaceError = '';
            this.resultError = '';
            this.enemyRandomRaceError = '';
        },
        resetValues() {
            this.resetErrors();

            this.enemyCurrentMmr = 0;
            this.enemyBestMmr = 0;
            this.enemyNickname = '';
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
        }
    },

}
</script>

<style scoped>
>>>.v-list-item__title{
    font-size: 12px !important;
}
>>>.v-select__selections{
    font-size: 12px !important;
}
</style>
