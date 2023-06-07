import Vue from "vue";
import Vuex from 'vuex';
import BaseStatisticModule from "./modules/BroodWarDatatables/DatatableStatisticModule";
import BaseGeneralTotalsModule from "./modules/BroodWarTotalsCards/BaseGeneralTotalsModule";
import BaseSeasonsModule from "./modules/BroodWarTotalsCards/BaseSeasonsModule";
import BaseBestMapsModule from "./modules/BroodWarTotalsCards/BaseBestMapsModule";
import BaseMapWinrateModule from "./modules/BroodWarTotalsCards/BaseMapWinrateModule";
import BaseRacesWinrateModule from "./modules/BroodWarTotalsCards/BaseRacesWinrateModule";
import BaseMmrWinrateModule from "./modules/BroodWarTotalsCards/BaseMmrWinrateModule";
import BaseMapRaceModule from "./modules/BroodWarTotalsCards/BaseMapRaceModule";
import BaseMmrMapRaceModule from "./modules/BroodWarTotalsCards/BaseMmrMapRaceModule";
import CommonLoaderModule from "./modules/Сommon/CommonLoaderModule";
import BaseExcelImportModule from "./modules/Excel/BaseExcelImportModule";
import CommonSnackBarModule from "./modules/Сommon/CommonSnackBarModule";
import CommonDialoguesVisibilityModule from "./modules/Сommon/CommonDialoguesVisibilityModule";
import DialogCreateModule from "./modules/BroodWarDialogues/DialogCreateModule";
import DialogFilterModule from "./modules/BroodWarDialogues/DialogFilterModule";

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: true,
    modules: {
        statistic: { namespaced: true, ...BaseStatisticModule },

        generalTotals: { namespaced: true, ...BaseGeneralTotalsModule },
        seasons: { namespaced: true, ...BaseSeasonsModule },
        bestMaps: { namespaced: true, ...BaseBestMapsModule },
        mapsWinrate: { namespaced: true, ...BaseMapWinrateModule },
        racesWinrate: { namespaced: true, ...BaseRacesWinrateModule },
        mmrWinrate: { namespaced: true, ...BaseMmrWinrateModule },
        mapRace: { namespaced: true, ...BaseMapRaceModule },
        mmrMapRace: { namespaced: true, ...BaseMmrMapRaceModule },

        excel: { namespaced: true, ...BaseExcelImportModule },

        loading: { namespaced: true, ...CommonLoaderModule },
        snackbar: { namespaced: true, ...CommonSnackBarModule },
        dialogVisibility: { namespaced: true, ...CommonDialoguesVisibilityModule },
        dialog: { namespaced: true, ...DialogCreateModule },
        dialogFilter: { namespaced: true, ...DialogFilterModule },
    },
    getters: {
        selectedSeason: state => state.seasons.selectedSeason,
        filterValues: state => state.dialogFilter.filterValues,
        bwDialogModule: state => state.dialog.isOpenedCreateNewDialog ? 'dialog' : 'dialogFilter',
    },
    actions: {
        getData({ dispatch }) {
            dispatch('statistic/getData');
        },
        resetState({ commit }){
            commit('statistic/RESET_STATE');
            commit('generalTotals/RESET_STATE');
            commit('seasons/RESET_STATE');
            commit('bestMaps/RESET_STATE');
            commit('mapsWinrate/RESET_STATE');
            commit('racesWinrate/RESET_STATE');
            commit('mmrWinrate/RESET_STATE');
            commit('mapRace/RESET_STATE');
            commit('mmrMapRace/RESET_STATE');
        }
    }
})

export default store;
