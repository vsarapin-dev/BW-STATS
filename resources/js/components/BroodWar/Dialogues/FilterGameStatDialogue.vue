<template>
    <v-row justify="center">
        <v-dialog
            v-model="filterVisible"
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
                            <enemy-logins-field></enemy-logins-field>
                        </v-row>
                        <enemy-min-max-mmr-field></enemy-min-max-mmr-field>
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
                        @click="filterVisible = false"
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
import MapField from "../DialogFields/MapField";
import GameResultsField from "../DialogFields/GameResultsField";
import MyRaceSelectedField from "../DialogFields/MyRaceSelectedField";
import EnemyRaceSelectedField from "../DialogFields/EnemyRaceSelectedField";
import EnemyRandomRaceSelectedField from "../DialogFields/EnemyRandomRaceSelectedField";
import ResultCommentField from "../DialogFields/ResultCommentField";
import GlobalCommentField from "../DialogFields/GlobalCommentField";
import EnemyMinMaxMmrField from "../DialogFields/EnemyMinMaxMmrField";
import EnemyLoginsField from "../DialogFields/EnemyLoginsField";

export default {
    name: "FilterGameStatDialogue",
    components: {
        MapField,
        GameResultsField,
        MyRaceSelectedField,
        EnemyRaceSelectedField,
        EnemyRandomRaceSelectedField,
        ResultCommentField,
        GlobalCommentField,
        EnemyMinMaxMmrField,
        EnemyLoginsField,
    },
    watch: {
        "$store.state.dialogFilter.enemyLoginSearch": {
            handler: function (value) {
                this.findLoginLoader = true;
                this.getLogins(value);
            },
        },
        filterVisible: {
            handler: function (value) {
                if (value === true) {
                    this.resetValues();
                    this.getMapsFilters();
                    this.getRacesFilters();
                    this.getResultsFilters();
                }
            },
        },
    },
    computed: {
        filterVisible: {
            get() { return this.$store.getters['dialogVisibility/isOpenedFilterDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_FILTER_DIALOG_VISIBILITY', value) }
        },
        findLoginLoader: {
            get() { return this.$store.getters['dialogFilter/findLoginLoader'] },
            set(value) { this.$store.commit('dialogFilter/SET_FIND_LOGIN_LOADER', value) }
        },
    },
    methods: {
        getLogins(value) {
            this.$store.dispatch('dialogFilter/getLogins', value);
        },
        filterStats() {
            this.$store.dispatch('dialogFilter/filterStats');
            this.filterVisible = false;
        },
        getResultsFilters() {
            this.$store.dispatch('dialogFilter/getResultsFilters');
        },
        getMapsFilters() {
            this.$store.dispatch('dialogFilter/getMapsFilters');
        },
        getRacesFilters() {
            this.$store.dispatch('dialogFilter/getRacesFilters');
        },
        resetValues() {
            this.$store.dispatch('dialogFilter/resetValues');
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
