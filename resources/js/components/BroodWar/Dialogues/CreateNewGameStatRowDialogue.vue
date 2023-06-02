<template>
    <v-row justify="center">
        <v-dialog
            v-model="show"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5 ml-3">{{ $store.getters['dialogVisibility/dialogHeaderName'] }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <enemy-nickname-field></enemy-nickname-field>
                            <enemy-login-field></enemy-login-field>
                        </v-row>
                        <v-row>
                            <enemy-current-mmr-field></enemy-current-mmr-field>
                            <enemy-best-mmr-field></enemy-best-mmr-field>
                        </v-row>
                        <v-row>
                            <map-field></map-field>
                            <game-results-field></game-results-field>
                            <my-race-selected-field></my-race-selected-field>
                            <enemy-race-selected-field></enemy-race-selected-field>
                            <enemy-random-race-selected-field></enemy-random-race-selected-field>
                        </v-row>
                        <v-row>
                            <result-comment-field></result-comment-field>
                        </v-row>
                        <v-row>
                            <global-comment-field></global-comment-field>
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
                        v-if="!$store.getters['dialogVisibility/shouldUpdateTableRow']"
                        color="blue darken-1"
                        text
                        @click="storeStats"
                    >
                        Save
                    </v-btn>
                    <v-btn
                        v-if="$store.getters['dialogVisibility/shouldUpdateTableRow']"
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
import EnemyNicknameField from '../DialogFields/EnemyNicknameField.vue';
import EnemyLoginField from '../DialogFields/EnemyLoginField.vue';
import enemyCurrentMmrField from '../DialogFields/EnemyCurrentMmrField.vue';
import enemyBestMmrField from '../DialogFields/EnemyBestMmrField.vue';
import MapField from '../DialogFields/MapField.vue';
import GameResultsField from '../DialogFields/GameResultsField.vue';
import MyRaceSelectedField from '../DialogFields/MyRaceSelectedField.vue';
import EnemyRaceSelectedField from '../DialogFields/EnemyRaceSelectedField.vue';
import EnemyRandomRaceSelectedField from '../DialogFields/EnemyRandomRaceSelectedField.vue';
import ResultCommentField from '../DialogFields/ResultCommentField.vue';
import GlobalCommentField from '../DialogFields/GlobalCommentField.vue';

export default {
    name: "CreateNewGameStatRowDialogue",
    components: {
        EnemyNicknameField,
        EnemyLoginField,
        enemyCurrentMmrField,
        enemyBestMmrField,
        MapField,
        GameResultsField,
        MyRaceSelectedField,
        EnemyRaceSelectedField,
        EnemyRandomRaceSelectedField,
        ResultCommentField,
        GlobalCommentField,
    },
    computed: {
        show: {
            get() {
                if (this.$store.getters['dialogVisibility/isOpenedCreateNewDialog'] === true) {
                    this.resetValues();
                    this.getMapsFilters();
                    this.getRacesFilters();
                    this.getResultsFilters();
                }
                return this.$store.getters['dialogVisibility/isOpenedCreateNewDialog'];
            },
            set(value) {
                if (!value) {
                    this.$store.commit('dialogVisibility/SET_CREATE_NEW_DIALOG_VISIBILITY', false);
                }
            }
        },

    },
    methods: {
        storeStats() {
            this.$store.dispatch('dialog/storeStats');
        },
        getResultsFilters() {
            this.$store.dispatch('dialog/getResultsFilters');
        },
        getMapsFilters() {
            this.$store.dispatch('dialog/getMapsFilters');
        },
        getRacesFilters() {
            this.$store.dispatch('dialog/getRacesFilters');
        },
        showErrors(error) {
            this.$store.dispatch('dialog/showErrors', error);
        },
        resetErrors() {
            this.$store.dispatch('dialog/resetErrors');
        },
        resetValues() {
            this.$store.dispatch('dialog/resetValues');
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
