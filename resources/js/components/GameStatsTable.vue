<template>
    <div>
        <Loader v-if="loadingData"/>
        <CreateNewGameStatRowDialogue
            ref="createNewGameStatRowDialogue"
            :visible="isOpenedCreateNewDialog"
            :headerName="createEditHeaderName"
            :updateRow="shouldUpdateRow"
            @close="isOpenedCreateNewDialog = false; shouldUpdateRow = false"
        />
        <FilterGameStartDialog
            :filterVisible="isOpenedFilterDialog"
            :seasonId="selectedSeason"
            @close="isOpenedFilterDialog = false"
        />

        <v-row>
            <v-expansion-panels
                class="mt-10"
                :class="$vuetify.breakpoint.smAndUp ? ['ml-3', 'mr-3'] : ['ml-1', 'mr-1']"
                v-model="panel"
                multiple
            >
                <v-expansion-panel>
                    <v-expansion-panel-header>Full Stats</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <MmrMapRaceWinrateCardComponent
                            :mmrMapRaceWinrate="mmrMapRaceWinrate"
                            :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                        <MapRaceWinrateCardComponent
                            :mapRaceWinrate="mapRaceWinrate"
                            :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                        <v-row class="mt-5">
                            <TotalStatCardComponent
                                :generalStats="generalStats"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                            <MapWinrateCardComponent
                                :mapWinrate="mapWinrate"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                            <RaceWinrateCardComponent
                                :raceWinrate="raceWinrate"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                            <MmrWinrateCardComponent
                                :mmrWinrate="mmrWinrate"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                            <BestStatCardComponent
                                :bestMaps="bestMaps"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                            <FinalStatCardComponent
                                :finalResults="bestMaps"
                                :selectedSeason="selectedSeason"
                                :class="$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1']"/>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
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
            disable-sort
            item-key="id"
            item-value="id"
            hide-default-footer
            show-select
            checkbox-color="red"
            dense
            class="mt-10 justify-center elevation-1"
        >

            <template v-slot:header.game_number="{ header }"><span style="cursor: pointer" @click="customSort('game_number')">Game #</span></template>
            <template v-slot:header.map="{ header }"><span style="cursor: pointer" @click="customSort('map')">Map</span></template>
            <template v-slot:header.matchup="{ header }"><span style="cursor: pointer" @click="customSort('matchup')">Matchup</span></template>
            <template v-slot:header.enemy_current_mmr="{ header }"><span style="cursor: pointer" @click="customSort('enemy_current_mmr')">Enemy current mmr</span></template>
            <template v-slot:header.enemy_max_mmr="{ header }"><span style="cursor: pointer" @click="customSort('enemy_max_mmr')">Enemy max mmr</span></template>
            <template v-slot:header.result="{ header }"><span style="cursor: pointer" @click="customSort('result')">Result</span></template>
            <template v-slot:header.result_comment="{ header }"><span style="cursor: pointer" @click="customSort('result_comment')">Result comment</span></template>
            <template v-slot:header.enemy_nickname="{ header }"><span style="cursor: pointer" @click="customSort('enemy_nickname')">Enemy nickname</span></template>
            <template v-slot:header.enemy_login="{ header }"><span style="cursor: pointer" @click="customSort('enemy_login')">Enemy login</span></template>
            <template v-slot:header.global_comment="{ header }"><span style="cursor: pointer" @click="customSort('global_comment')">Global Comment</span></template>
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
                        <span class="caption">{{ totalRowsFoundInDB }} rows found</span>
                    </v-row>
                    <v-row class="mt-2">
                        <v-col class="pb-0">
                            <div class="d-flex">
                                <v-btn
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
import MapWinrateCardComponent from "./Cards/MapWinrateCardComponent";
import RaceWinrateCardComponent from "./Cards/RaceWinrateCardComponent";
import MmrWinrateCardComponent from "./Cards/MmrWinrateCardComponent";
import MapRaceWinrateCardComponent from "./Cards/MapRaceWinrateCardComponent";
import MmrMapRaceWinrateCardComponent from "./Cards/MmrMapRaceWinrateCardComponent";
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
        MapWinrateCardComponent,
        RaceWinrateCardComponent,
        MapRaceWinrateCardComponent,
        MmrWinrateCardComponent,
        MmrMapRaceWinrateCardComponent,
    },
    data() {
        return {
            options: {},
            sortBy: 'game_number',
            sortDesc: true,
            sortDescString: 'desc',
            panel: [],
            page: 1,
            pageCount: 0,
            itemsPerPage: 20,
            totalRowsFoundInDB: 0,
            totalVisible: 7,
            loadingData: false,
            shouldUpdateRow: false,
            allRowsSelected: false,
            isOpenedCreateNewDialog: false,
            isOpenedFilterDialog: false,
            seasons: [],
            bestMaps: [],
            selectedSeason: null,
            lastUpdatedAtDate: null,
            selectedToDelete: [],
            generalStats: null,
            mapWinrate: null,
            mapRaceWinrate: null,
            mmrMapRaceWinrate: null,
            raceWinrate: null,
            mmrWinrate: null,
            createEditHeaderName: null,
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
                {text: '', value: 'actions', sortable: false},
            ],
            stats: [],
        }
    },
    watch: {
        selectedToDelete(value) {
            if (value.length > 0)
            {
                this.allRowsSelected = false;
            }
        },
        allRowsSelected(value) {
            if (value === true)
            {
                this.selectedToDelete = [];
            }
        },
    },
    mounted() {
        this.getData();
        this.updatePanel();
    },
    methods: {
        customSort(column) {

            this.sortDesc = !this.sortDesc;
            this.sortBy = column;
            this.sortDescString = this.sortDesc ? 'desc' : 'asc';

            if (column === 'map')
                this.sortBy = 'map_id';
            if (column === 'result')
                this.sortBy = 'result_id';
            if (column === 'matchup')
                this.sortBy = 'enemy_race_id';


            this.getData();
            return this.stats;
        },
        editItem (item) {
            this.editedIndex = this.stats.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.shouldUpdateRow = true;
            this.openCreateNewOrEditDialog('Edit game');
            console.log("EDIT")
        },
        openCreateNewOrEditDialog(headerText) {
            this.createEditHeaderName = headerText;
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

                let data = {
                    season_id: this.selectedSeason,
                };

                data = this.allRowsSelected ?
                    {
                        ...data,
                        delete_all: this.allRowsSelected,
                    } :
                    {
                        ...data,
                        ids: ids,
                    };

                API.post('/api/auth/delete-stats', data)
                    .then(res => {
                        this.loadingData = false;
                        this.resetVariables();
                    })
                    .catch(e => {
                        this.loadingData = false;
                        console.log(e.response)
                    })
                this.selectedSeason = null;
                this.getData();
            }
        },
        getData() {
            this.loadingData = true;
            this.resetVariables();
            API.post('/api/auth/index', {
                page: this.page,
                sort_by: this.sortBy,
                sort_desc: this.sortDescString,
                itemsPerPage: this.itemsPerPage,
                season_id: this.selectedSeason,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('mapWinrate'))
                        this.mapWinrate = res.data.mapWinrate;
                    if (res.data.hasOwnProperty('raceWinrate'))
                        this.raceWinrate = res.data.raceWinrate;
                    if (res.data.hasOwnProperty('mmrWinrate'))
                        this.mmrWinrate = res.data.mmrWinrate;
                    if (res.data.hasOwnProperty('mapRace'))
                        this.mapRaceWinrate = res.data.mapRace;
                    if (res.data.hasOwnProperty('mmrMapRace'))
                        this.mmrMapRaceWinrate = res.data.mmrMapRace;


                    if (res.data.hasOwnProperty('data') &&
                        res.data.hasOwnProperty('generalStats') &&
                        res.data.hasOwnProperty('currentSeason') &&
                        res.data.hasOwnProperty('availableSeasons') &&
                        res.data.hasOwnProperty('lastUpdated') &&
                        res.data.hasOwnProperty('bestMaps') &&
                        res.data.data !== null &&
                        res.data.generalStats !== null &&
                        res.data.availableSeasons !== null &&
                        res.data.currentSeason !== null &&
                        res.data.lastUpdated !== null &&
                        res.data.bestMaps !== null)
                    {
                        this.bestMaps = res.data.bestMaps;
                        this.lastUpdatedAtDate = res.data.lastUpdated;
                        this.stats = res.data.data.data;
                        this.generalStats = res.data.generalStats;
                        this.seasons = res.data.availableSeasons;
                        this.selectedSeason = res.data.currentSeason.id;
                        this.totalRowsFoundInDB = res.data.data.total;
                        this.setPageVariables(res.data.data);

                    }
                    this.loadingData = false;
                    console.log(res.data)
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
        updatePanel() {
            this.panel = [0];
            setTimeout(i => {
                this.panel = [];
            }, 70)
        },
        changeSeason() {
            this.getData();
            this.page = 0;
            this.updatePanel();
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
            this.seasons = null;
            this.selectedSeason = null;
            this.totalRowsFoundInDB = null;
            this.allRowsSelected = false;
            this.mmrWinrate = null;
            this.mmrMapRaceWinrate = null;
            this.mapWinrate = null;
            this.mapRaceWinrate = null;
            this.raceWinrate = null;
            this.generalStats = null;
            this.bestMaps = [];
            this.lastUpdatedAtDate = null;
            this.stats = [];
            this.pageCount = 0;
        },
    }
}
</script>
