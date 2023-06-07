<template>
    <div>
        <v-row>
            <v-expansion-panels
                class="mt-10"
                :class="$vuetify.breakpoint.smAndUp ? ['ml-3', 'mr-3'] : ['ml-1', 'mr-1']"
                v-model="updatePanel"
                multiple
            >
                <v-expansion-panel :disabled="!selectedSeason">
                    <v-expansion-panel-header>Full Stats</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <BaseTotals/>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-row>
        <v-data-table
            :page.sync="page"
            :items-per-page="this.$store.getters['statistic/itemsPerPage']"
            :page-count="$store.getters['statistic/pageCount']"
            :loading="$store.getters['statistic/loadingData']"
            loading-text="Loading... Please wait"
            v-model="selectedToDelete"
            :headers="$store.getters['statistic/headers']"
            :items="stats"
            disable-sort
            item-key="id"
            item-value="id"
            hide-default-footer
            show-select
            checkbox-color="red"
            dense
            class="mt-10 justify-center elevation-1"
        >

            <template v-slot:header.game_number="{ header }">
                <span style="cursor: pointer" @click="customSort('game_number')">
                    Game #
                </span>
            </template>
            <template v-slot:header.map="{ header }"><span style="cursor: pointer" @click="customSort('map')">Map</span>
            </template>
            <template v-slot:header.matchup="{ header }"><span style="cursor: pointer" @click="customSort('matchup')">Matchup</span>
            </template>
            <template v-slot:header.enemy_current_mmr="{ header }"><span style="cursor: pointer"
                                                                         @click="customSort('enemy_current_mmr')">Enemy current mmr</span>
            </template>
            <template v-slot:header.enemy_max_mmr="{ header }"><span style="cursor: pointer"
                                                                     @click="customSort('enemy_max_mmr')">Enemy max mmr</span>
            </template>
            <template v-slot:header.result="{ header }"><span style="cursor: pointer" @click="customSort('result')">Result</span>
            </template>
            <template v-slot:header.result_comment="{ header }"><span style="cursor: pointer"
                                                                      @click="customSort('result_comment')">Result comment</span>
            </template>
            <template v-slot:header.enemy_nickname="{ header }"><span style="cursor: pointer"
                                                                      @click="customSort('enemy_nickname')">Enemy nickname</span>
            </template>
            <template v-slot:header.enemy_login="{ header }"><span style="cursor: pointer"
                                                                   @click="customSort('enemy_login')">Enemy login</span>
            </template>
            <template v-slot:header.global_comment="{ header }"><span style="cursor: pointer"
                                                                      @click="customSort('global_comment')">Global Comment</span>
            </template>
            <template v-slot:header.data-table-select>
                <v-checkbox
                    v-model="allRowsSelected"
                    color="red"
                    hide-details
                >
                    <template v-slot:label>
                        <span class="caption">Delete all</span>
                    </template>
                </v-checkbox>
            </template>
            <template v-slot:top>
                <v-container>
                    <v-row class="ml-4 mt-1">
                        <span class="caption">{{ $store.getters['statistic/totalRowsFoundInDB'] }} rows found</span>
                    </v-row>
                    <v-row class="mt-2">
                        <v-col class="pb-0">
                            <div class="d-flex">
                                <v-btn
                                    :disabled="!selectedSeason"
                                    small
                                    class="ml-3"
                                    color="secondary"
                                    height="40"
                                    width="95"
                                    @click="openCreateNewOrEditDialog('Add new game')"
                                >
                                    Add game
                                </v-btn>
                                <v-btn
                                    :disabled="!selectedSeason"
                                    small
                                    class="ml-3"
                                    color="secondary"
                                    height="40"
                                    width="95"
                                    @click="openFilterDialog"
                                >
                                    Filters
                                </v-btn>
                                <div style="width: 100px">
                                    <v-select
                                        :disabled="!selectedSeason"
                                        :items="$store.getters['seasons/availableSeasons']"
                                        v-model="selectedSeason"
                                        @change="changeSeason"
                                        label="Season"
                                        item-text="season"
                                        item-value="id"
                                        class="ml-3"
                                        outlined
                                        dense
                                    >
                                        <template slot="selection" slot-scope="data">
                                            {{ data.item.season }}
                                        </template>
                                    </v-select>
                                </div>
                                <v-btn
                                    :disabled="!selectedSeason"
                                    small
                                    class="ml-3"
                                    color="error"
                                    height="40"
                                    width="95"
                                    @click="resetState"
                                >
                                    Reset all
                                </v-btn>
                            </div>
                        </v-col>
                        <v-col class="d-flex justify-end">
                            <v-btn
                                v-if="selectedToDelete.length > 0 || allRowsSelected === true"
                                small
                                class="ml-3"
                                color="red"
                                @click="deleteData"
                            >
                                Delete
                            </v-btn>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col class="pt-0">
                            <span class="caption ml-4" style="vertical-align: top">Last Updated: <span
                                style="color: #e2e2e2">{{ $store.getters['statistic/lastUpdatedAtDate'] }}</span></span>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.result="{ item }">
                <span
                    :style="{color: item.result_color}"
                >
                    {{ item.result }}
                </span>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
            </template>
        </v-data-table>
        <div class="text-center pt-2">
            <v-pagination
                v-model="page"
                :length="$store.getters['statistic/pageCount']"
                :total-visible="$store.getters['statistic/totalPagesPaginatorVisible']"
                @input="pageChanged"
            ></v-pagination>
        </div>
    </div>

</template>

<script>
import API from "../../../api";
import BaseTotals from "../BaseTotals";
import anime from 'animejs';

export default {
    name: "GameStatsTable",
    components: {
        BaseTotals
    },
    data() {
        return {
            filterValues: {},
        }
    },
    watch: {
        "$store.state.seasons.selectedSeason": {
            handler: function (newSeasonValue) {
                if (newSeasonValue !== null) {
                    this.loadData(newSeasonValue);
                } else {
                    this.loadData(null);
                }
            },
        },
        "$store.state.statistic.stats": {
            handler: function (stats) {
                if (stats !== null) {
                    this.animateDatatable();
                }
            },
        },
        selectedToDelete(value) {
            if (value.length > 0) {
                this.allRowsSelected = false;
            }
        },
        allRowsSelected(value) {
            if (value === true) {
                this.selectedToDelete = [];
            }
        },
    },
    mounted() {
        this.$store.dispatch('seasons/getData');
    },
    computed: {
        selectedToDelete: {
            get() { return this.$store.getters['statistic/selectedToDelete'] },
            set(value) { this.$store.commit('statistic/SET_SELECTED_TO_DELETE', value) },
        },
        allRowsSelected: {
            get() { return this.$store.getters['statistic/allRowsSelected'] },
            set(value) { this.$store.commit('statistic/SET_ALL_ROWS_SELECTED', value) },
        },
        page: {
            get() { return this.$store.getters['statistic/page'] },
            set(value) { this.$store.commit('statistic/SET_PAGE', value) },
        },
        isOpenedCreateNewDialog: {
            get() { return this.$store.getters['dialogVisibility/isOpenedCreateNewDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_CREATE_NEW_DIALOG_VISIBILITY', value) },
        },
        isOpenedFilterDialog: {
            get() { return this.$store.getters['dialogVisibility/isOpenedFilterDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_FILTER_DIALOG_VISIBILITY', value) },
        },
        stats: {
            get() { return this.$store.getters['statistic/stats'] },
        },
        updatePanel: {
            get() { return this.$store.getters['statistic/panel'] },
            set() { this.$store.commit('statistic/SWITCH_PANEL') },
        },
        selectedSeason: {
            get() { return this.$store.getters['seasons/selectedSeason'] },
            set(value) { this.$store.commit('seasons/SET_SELECTED_SEASON', value) },
        }
    },
    methods: {
        animateDatatable() {
            this.$nextTick(() => {
                const rows = document.querySelectorAll('.v-data-table tbody tr');
                rows.forEach((row, index) => {
                    anime({
                        targets: row,
                        translateY: [500, 0],
                        opacity: [0, 1],
                        duration: 10,
                        easing: 'easeOutExpo',
                        delay: 20 * index,
                        complete: function(anim) {
                            anime({
                                targets: anim.animatables[0].target.children,
                                translateY: [-50, 0],
                                opacity: [0, 1],
                                duration: 10,
                                easing: 'easeOutElastic',
                                delay: function(el, i, l) {
                                    return 50 * i;
                                }
                            });
                        }
                    });
                });
            });
            // this.$nextTick(() => {
            //     const elements = document.querySelectorAll('.text-start');
            //     elements.forEach((element) => {
            //         const initialValue = 0;
            //         const finalValue = parseInt(element.textContent);
            //         const duration = 1200;
            //
            //         if (!isNaN(finalValue)) {
            //             anime({
            //                 targets: element,
            //                 textContent: [initialValue, finalValue],
            //                 round: 1,
            //                 easing: 'linear',
            //                 duration: duration,
            //                 round: 10
            //             });
            //         }
            //     });
            // });
        },
        loadData(seasonId) {
            if (seasonId === null) {
                this.$store.dispatch('seasons/getData');
            } else {
                this.$store.dispatch('generalTotals/getData', seasonId);
                this.$store.dispatch('bestMaps/getData', seasonId);
                this.$store.dispatch('mapsWinrate/getData', seasonId);
                this.$store.dispatch('racesWinrate/getData', seasonId);
                this.$store.dispatch('mmrWinrate/getData', seasonId);
                this.$store.dispatch('mapRace/getData', seasonId);
                this.$store.dispatch('mmrMapRace/getData', seasonId);
                this.$store.dispatch('statistic/getData');
            }
        },
        customSort(column) {
            let payload = { column: column, seasonId: this.selectedSeason };
            this.$store.dispatch('statistic/customSort', payload);
        },
        editItem(item) {
            let editedIndex = this.$store.getters['statistic/stats'].indexOf(item);
            let editedItem = Object.assign({}, item);
            this.$store.commit('dialogVisibility/SET_SHOULD_UPDATE_TABLE_ROW', true);
            this.openCreateNewOrEditDialog('Edit game');
        },
        openCreateNewOrEditDialog(headerText) {
            this.$store.commit('dialogVisibility/SET_DIALOG_HEADER_NAME', headerText);
            this.isOpenedCreateNewDialog = !this.isOpenedCreateNewDialog;
        },
        openFilterDialog() {
            this.isOpenedFilterDialog = !this.isOpenedFilterDialog;
        },
        filteredDataObject(valuesToFilter) {
            if (this.filterIsActiveNow === false) {
                this.filterIsActiveNow = true;
                valuesToFilter.page = 1;
            } else {
                valuesToFilter.page = this.$store.getters('statistic/page');
            }
            valuesToFilter.itemsPerPage = this.$store.getters['statistic/itemsPerPage'];
            valuesToFilter.season_id = this.selectedSeason;
            valuesToFilter.sort_by = this.sortBy;
            valuesToFilter.sort_desc = this.sortDescString;

            this.filterValues = valuesToFilter;
            this.$store.dispatch('statistic/getData');
        },
        deleteData() {
            this.$store.dispatch('statistic/deleteData');
        },
        resetState() {
            this.$store.dispatch('resetState');
            this.loadData(null);
        },
        changeSeason(value) {
            this.$store.commit('seasons/SET_SELECTED_SEASON', value);
            this.$store.commit('statistic/SET_PAGE', 1);
            this.$store.dispatch('statistic/getData');
        },
        pageChanged(page) {
            this.page = page;
            this.$store.dispatch('statistic/getData');
        },
    }
}
</script>
