<template>
    <div>
        <Loader v-if="loadingData"/>
        <CreateNewGameStatRowDialogue
            ref="createNewGameStatRowDialogue"
            :visible="isOpenedCreateNewDialog"
            @close="isOpenedCreateNewDialog = false"
        />
        <FilterGameStartDialog
            :filterVisible="isOpenedFilterDialog"
            :seasonId="selectedSeason"
            @close="isOpenedFilterDialog = false"
        />

        <v-row>
            <TotalStatCardComponent :totals="totals" :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : 'ml-3'"/>
            <BestStatCardComponent :bestDataResults="bestDataResults" :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : 'ml-3'"/>
            <FinalStatCardComponent :finalResults="finalResults" :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : 'ml-5'"/>
        </v-row>
        <v-data-table
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :page-count="pageCount"
            :loading="loadingData"
            loading-text="Loading... Please wait"
            v-model="selectedToDelete"
            :headers="headers"
            :items="stats"
            item-key="id"
            item-value="id"
            hide-default-footer
            show-select
            dense
            class="mt-10 justify-center elevation-1"
        >
            <template v-slot:top>
                <v-container>
                    <v-row class="mt-2">
                        <v-col class="pb-0">
                            <div class="d-flex">
                                <v-btn
                                    small
                                    class="ml-3"
                                    color="secondary"
                                    height="40"
                                    width="95"
                                    @click="openCreateNewDialog"
                                >
                                    Add game
                                </v-btn>
                                <v-btn
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
                                        :items="seasons"
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
                            </div>
                        </v-col>
                        <v-col class="d-flex justify-end">
                            <v-btn
                                v-if="selectedToDelete.length > 0"
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
                            <span class="caption ml-3" style="vertical-align: top">Last Updated: <span
                                style="color: #e2e2e2">{{ lastUpdatedAtDate }}</span></span>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.result="{ item }">
                <span
                    :style="{color: getResultTextColor(item.result)}"
                >
                    {{ item.result }}
                </span>
            </template>
        </v-data-table>
        <div class="text-center pt-2">
            <v-pagination
                v-model="page"
                :length="pageCount"
                :total-visible="totalVisible"
                @input="pageChanged"
            ></v-pagination>
        </div>
    </div>

</template>

<script>
import API from "../api";
import CreateNewGameStatRowDialogue from "./Dialogues/CreateNewGameStatRowDialogue";
import FilterGameStartDialog from "./Dialogues/FilterGameStatDialogue";
import TotalStatCardComponent from "./Cards/TotalStatCardComponent";
import FinalStatCardComponent from "./Cards/FinalStatCardComponent";
import BestStatCardComponent from "./Cards/BestStatCardComponent";
import Loader from "./Loader";

export default {
    name: "GameStatsTable",
    components: {
        CreateNewGameStatRowDialogue,
        FilterGameStartDialog,
        Loader,
        FinalStatCardComponent,
        TotalStatCardComponent,
        BestStatCardComponent,
    },
    data() {
        return {
            page: 1,
            pageCount: 0,
            itemsPerPage: 20,
            totalVisible: 7,
            loadingData: false,
            isOpenedCreateNewDialog: false,
            isOpenedFilterDialog: false,
            seasons: [],
            bestDataResults: [],
            finalResults: [],
            selectedSeason: null,
            lastUpdatedAtDate: null,
            selectedToDelete: [],
            totals: {
                raw: {
                    total_games: 0,
                    real_wins: 0,
                    general_wins: 0,
                },
                percents: {
                    real_wins: 0,
                    general_wins: 0,
                },
            },
            headers: [
                {text: 'Game #', value: 'game_number', width: 100},
                {text: 'Map', value: 'map'},
                {text: 'Matchup', value: 'matchup'},
                {text: 'Enemy current mmr', value: 'enemy_current_mmr'},
                {text: 'Enemy max mmr', value: 'enemy_max_mmr'},
                {text: 'Result', value: 'result'},
                {text: 'Result comment', value: 'result_comment'},
                {text: 'Enemy nickname', value: 'enemy_nickname'},
                {text: 'Enemy login', value: 'enemy_login'},
                {text: 'Global Comment', value: 'global_comment'},
            ],
            stats: [],
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        openCreateNewDialog() {
            this.isOpenedCreateNewDialog = !this.isOpenedCreateNewDialog;
        },
        openAddNewMapDialog() {

        },
        openFilterDialog() {
            this.isOpenedFilterDialog = !this.isOpenedFilterDialog;
        },
        getResultTextColor(result) {
            switch (result) {
                case 'W':
                    return 'green';
                case 'L':
                    return 'red';
                default:
                    return 'white';
            }
        },
        filterData(valuesToFilter) {
            console.log(valuesToFilter);
        },
        deleteData() {
            if (window.confirm("Are you sure want to delete this rows?")) {
                this.loadingData = true;

                let ids = this.selectedToDelete.map(i => i.id);
                this.selectedToDelete = [];

                API.post('/api/auth/delete-stats', {
                    ids: ids,
                })
                    .then(res => {
                        this.loadingData = false;
                        this.resetVariables();
                    })
                    .catch(e => {
                        this.loadingData = false;
                        console.log(e.response)
                    })
                this.getData();
            }
        },
        getData() {
            this.loadingData = true;
            API.post('/api/auth/index', {
                page: this.page,
                itemsPerPage: this.itemsPerPage,
                season_id: this.selectedSeason,
            })
                .then(res => {
                    this.resetVariables();
                    if (res.data.hasOwnProperty('gameStatFilterResult') &&
                        res.data.hasOwnProperty('gameStatTotalValueResult') &&
                        res.data.hasOwnProperty('currentSeason') &&
                        res.data.hasOwnProperty('availableSeasons') &&
                        res.data.hasOwnProperty('lastUpdated') &&
                        res.data.hasOwnProperty('bestMaps') &&
                        res.data.gameStatFilterResult !== null &&
                        res.data.gameStatTotalValueResult !== null &&
                        res.data.availableSeasons !== null &&
                        res.data.currentSeason !== null &&
                        res.data.lastUpdated !== null &&
                        res.data.bestMaps !== null)
                    {
                        this.bestDataResults = res.data.bestMaps;
                        this.lastUpdatedAtDate = res.data.lastUpdated;
                        this.stats = res.data.gameStatFilterResult.data;
                        this.totals.raw = res.data.gameStatTotalValueResult;
                        this.seasons = res.data.availableSeasons;
                        this.selectedSeason = res.data.currentSeason.id;
                        this.setPageVariables(res.data.gameStatFilterResult);
                        this.computeWinPercents(this.totals);
                    }
                    this.loadingData = false;
                    console.log(res.data.bestMaps)
                })
                .catch(e => {
                    this.loadingData = false;
                    console.log(e.response)
                })
        },
        storeData(data) {
            API.post('/api/auth/store-stats', {
                ...data,
                season_id: this.selectedSeason,
            })
                .then(res => {
                    this.isOpenedCreateNewDialog = false;
                    this.getData();
                })
                .catch(e => {
                    this.$refs.createNewGameStatRowDialogue.showErrors(e);
                    console.log(e.response);
                })
        },
        changeSeason() {
            this.getData();
        },
        pageChanged(page) {
            this.page = page;
            this.getData();
        },
        setPageVariables(meta) {
            this.page = meta.current_page;
            this.pageCount = meta.last_page;
        },
        resetVariables() {
            this.resetTotals();
            this.finalResults = [];
            this.bestDataResults = [];
            this.lastUpdatedAtDate = null;
            this.stats = [];
            this.page = 0;
            this.pageCount = 0;
        },
        resetTotals() {
            this.totals = {
                raw: {
                    total_games: 0,
                    real_wins: 0,
                    general_wins: 0,
                },
                percents: {
                    real_wins: 0,
                    general_wins: 0,
                },
            };
        },
        computeWinPercents(totals) {
            if (totals.raw.total_games > 0 &&
                totals.raw.real_wins > 0 &&
                totals.raw.general_wins > 0
            ) {
                totals.percents.real_wins = (totals.raw.real_wins / totals.raw.total_games * 100);
                totals.percents.general_wins = (totals.raw.general_wins / totals.raw.total_games * 100);

                if (totals.percents.real_wins % 1 !== 0) {
                    totals.percents.real_wins = totals.percents.real_wins.toFixed(2);
                }
                if (totals.percents.general_wins % 1 !== 0) {
                    totals.percents.general_wins = totals.percents.general_wins.toFixed(2);
                }
            }
        },
    }
}
</script>
