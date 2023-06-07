import API from "../../../api";

export default {
    state: () => ({
        itemsPerPage: 20,
        sortBy: 'game_number',
        sortDesc: true,
        sortDescString: 'desc',
        lastUpdatedAtDate: null,
        stats: [],
        selectedToDelete: [],
        totalRowsFoundInDB: 0,
        allRowsSelected: false,
        filterIsActiveNow: false,
        pageCount: 0,
        page: 1,
        totalPagesPaginatorVisible: 7,
        panel: [],
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
    }),
    getters: {
        itemsPerPage: state => state.itemsPerPage,
        selectedToDelete: state => state.selectedToDelete,
        sortBy: state => state.sortBy,
        sortDesc: state => state.sortDesc,
        sortDescString: state => state.sortDescString,
        panel: state => state.panel,
        lastUpdatedAtDate: state => state.lastUpdatedAtDate,
        stats: state => state.stats,
        totalRowsFoundInDB: state => state.totalRowsFoundInDB,
        allRowsSelected: state => state.allRowsSelected,
        filterIsActiveNow: state => state.filterIsActiveNow,
        pageCount: state => state.pageCount,
        page: state => state.page,
        headers: state => state.headers,
        totalPagesPaginatorVisible: state => state.totalPagesPaginatorVisible,
    },
    mutations: {
        SET_SELECTED_TO_DELETE(state, payload) {
            state.selectedToDelete = payload;
        },
        SET_TOTAL_PAGES_PAGINATOR_VISIBLE(state, payload) {
            state.totalPagesPaginatorVisible = payload;
        },
        RESET_STATE(state) {
            state.itemsPerPage = 20;
            state.sortBy = 'game_number';
            state.sortDesc = true;
            state.sortDescString = 'desc';
            state.lastUpdatedAtDate = null;
            state.stats = [];
            state.totalRowsFoundInDB = 0;
            state.allRowsSelected = false;
            state.filterIsActiveNow = false;
            state.pageCount = 0;
            state.page = 1;
            state.panel = [];
        },
        SET_LAST_UPDATED_AT_DATE(state, payload) {
            state.lastUpdatedAtDate = payload;
        },
        SET_ITEMS_PER_PAGE(state, payload) {
            state.itemsPerPage = payload;
        },
        SET_SORT_BY(state, payload) {
            state.sortBy = payload;
        },
        SET_SORT_DESC(state, payload) {
            state.sortDesc = payload;
        },
        SET_SORT_DESC_STRING(state, payload) {
            state.sortDescString = payload;
        },
        SWITCH_PANEL(state) {
            state.panel = state.panel.length > 0 ? [] : [0];
        },
        SET_PAGE(state, payload) {
            state.page = payload;
        },
        SET_LAST_UPDATED_DATE(state, payload) {
            state.lastUpdatedAtDate = payload;
        },
        SET_STATS(state, payload) {
            state.stats = payload;
        },
        SET_TOTAL_ROWS_FOUND_IN_DB(state, payload) {
            state.totalRowsFoundInDB = payload;
        },
        SET_ALL_ROWS_SELECTED(state, payload) {
            state.allRowsSelected = payload;
        },
        SET_FILTER_IS_ACTIVE_NOW(state, payload) {
            state.filterIsActiveNow = payload;
        },
        SET_PAGE_COUNT(state, payload) {
            state.pageCount = payload;
        },
    },
    actions: {
        getData({state, commit, dispatch, rootGetters}) {
            commit('loading/SET_LOADING', true, {root: true});
            console.log({
                page: state.page,
                sort_by: state.sortBy,
                sort_desc: state.sortDescString,
                itemsPerPage: state.itemsPerPage,
                season_id: rootGetters['selectedSeason'],
                ...rootGetters['filterValues'],
            })
            API.post('/api/auth/index', {
                page: state.page,
                sort_by: state.sortBy,
                sort_desc: state.sortDescString,
                itemsPerPage: state.itemsPerPage,
                season_id: rootGetters['selectedSeason'],
                ...rootGetters['filterValues'],
            })
                .then(res => {
                    if (res.data.hasOwnProperty('data') &&
                        res.data.hasOwnProperty('lastUpdated') &&
                        res.data.data !== null &&
                        res.data.lastUpdated !== null) {

                        commit('SET_LAST_UPDATED_DATE', res.data.lastUpdated);
                        commit('SET_STATS', res.data.data.data);
                        commit('SET_TOTAL_ROWS_FOUND_IN_DB', res.data.data.total);

                        dispatch('setPageVariables', res.data.data);
                    } else if (res.data.hasOwnProperty('data')) {
                        commit('SET_STATS', res.data.data.data);
                        commit('SET_TOTAL_ROWS_FOUND_IN_DB', res.data.data.total);
                        dispatch('setPageVariables', res.data.data);
                    }
                    commit('loading/SET_LOADING', false, {root: true});
                })
                .catch(e => {
                    commit('loading/SET_LOADING', false, {root: true});
                    console.log(e.response)
                })
        },
        deleteData({state, commit, rootGetters}) {
            if (window.confirm("Are you sure want to delete this rows?")) {
                commit('loading/SET_LOADING', true, { root: true });

                let ids = state.selectedToDelete.map(i => i.id);
                commit('SET_SELECTED_TO_DELETE', []);

                let data = {
                    season_id: rootGetters['selectedSeason'],
                };

                data = state.allRowsSelected ?
                    { ...data, delete_all: state.allRowsSelected, } :
                    { ...data, ids: ids, };

                API.post('/api/auth/delete-stats', data)
                    .then(res => {
                        commit('snackbar/SET_SNACKBAR_OPENED', true, { root: true });
                        commit('snackbar/SET_MESSAGE', 'Deleted successfully', { root: true });
                        commit('loading/SET_LOADING', false, { root: true });
                        commit('RESET_STATE');
                        commit('seasons/SET_SELECTED_SEASON', null, { root: true });
                    })
                    .catch(e => {
                        commit('loading/SET_LOADING', false, { root: true });
                        console.log(e.response)
                    })
            }
        },
        setPageVariables({commit}, payload) {
            commit('SET_PAGE', payload.current_page);
            commit('SET_PAGE_COUNT', payload.last_page);
            commit('SET_ITEMS_PER_PAGE', payload.per_page);
        },
        customSort({store, state, commit, dispatch}, payload) {
            commit('SET_SORT_DESC', !state.sortDesc);
            commit('SET_SORT_BY', payload.column);
            commit('SET_SORT_DESC_STRING', state.sortDesc ? 'desc' : 'asc');

            if (payload.column === 'map')
                commit('SET_SORT_BY', 'map_id');
            if (payload.column === 'result')
                commit('SET_SORT_BY', 'result_id');
            if (payload.column === 'matchup')
                commit('SET_SORT_BY', 'enemy_race_id');

            dispatch('getData');
        },
    }
}
