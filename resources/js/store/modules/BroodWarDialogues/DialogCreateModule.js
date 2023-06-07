import API from "../../../api";

export default {
    state: () => ({
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
    }),
    getters: {
        enemyCurrentMmr: state => state.enemyCurrentMmr,
        enemyBestMmr: state => state.enemyBestMmr,
        resultComment: state => state.resultComment,
        globalComment: state => state.globalComment,
        mapSelected: state => state.mapSelected,
        resultSelected: state => state.resultSelected,
        myRaceSelected: state => state.myRaceSelected,
        enemyRandomRaceSelected: state => state.enemyRandomRaceSelected,
        enemyRaceSelected: state => state.enemyRaceSelected,
        enemyNickname: state => state.enemyNickname,
        enemyLogin: state => state.enemyLogin,
        maps: state => state.maps,
        gameResults: state => state.gameResults,
        myRaces: state => state.myRaces,
        enemyRaces: state => state.enemyRaces,

        enemyRaceError: state => state.enemyRaceError,
        mapError: state => state.mapError,
        myRaceError: state => state.myRaceError,
        resultError: state => state.resultError,
        enemyRandomRaceError: state => state.enemyRandomRaceError,
    },
    mutations: {
        SET_ENEMY_RACE_SELECTED(state, value) {
            state.enemyRaceSelected = value;
        },
        SET_ENEMY_CURRENT_MMR(state, value) {
            state.enemyCurrentMmr = value;
        },
        SET_ENEMY_BEST_MMR(state, value) {
            state.enemyBestMmr = value;
        },
        SET_RESULT_COMMENT(state, value) {
            state.resultComment = value;
        },
        SET_GLOBAL_COMMENT(state, value) {
            state.globalComment = value;
        },
        SET_MAP_SELECTED(state, value) {
            state.mapSelected = value;
        },
        SET_RESULT_SELECTED(state, value) {
            state.resultSelected = value;
        },
        SET_MY_RACE_SELECTED(state, value) {
            state.myRaceSelected = value;
        },
        SET_ENEMY_RANDOM_RACE_SELECTED(state, value) {
            state.enemyRandomRaceSelected = value;
        },
        SET_ENEMY_NICKNAME(state, value) {
            state.enemyNickname = value;
        },
        SET_ENEMY_LOGIN(state, value) {
            state.enemyLogin = value;
        },
        SET_MAPS(state, value) {
            state.maps = value;
        },
        SET_GAME_RESULTS(state, value) {
            state.gameResults = value;
        },
        SET_MY_RACES(state, value) {
            state.myRaces = value;
        },
        SET_ENEMY_RACES(state, value) {
            state.enemyRaces = value;
        },
        SET_ENEMY_RACE_ERROR(state, value) {
            state.enemyRaceError = value;
        },
        SET_MAP_ERROR(state, value) {
            state.mapError = value;
        },
        SET_MY_RACE_ERROR(state, value) {
            state.myRaceError = value;
        },
        SET_RESULT_ERROR(state, value) {
            state.resultError = value;
        },
        SET_ENEMY_RANDOM_RACE_ERROR(state, value) {
            state.enemyRandomRaceError = value;
        },
    },
    actions: {
        storeData({rootGetters, dispatch, commit}, data) {
            API.post('/api/auth/store-stats', {
                ...data,
                season_id: rootGetters['selectedSeason'],
            })
                .then(() => {
                    commit('dialogVisibility/SET_CREATE_NEW_DIALOG_VISIBILITY', false, { root: true });
                    dispatch('getData', { root: true });
                })
                .catch(e => {
                    dispatch('showErrors',e);
                    console.log(e);
                })
        },
        storeStats({state, dispatch}) {
            dispatch('resetErrors');
            dispatch('storeData', {
                enemy_current_mmr: state.enemyCurrentMmr,
                enemy_max_mmr: state.enemyBestMmr,
                enemy_nickname: state.enemyNickname,
                enemy_login: state.enemyLogin,
                map_id: state.mapSelected,
                result_comment: state.resultComment,
                global_comment: state.globalComment,
                result_id: state.resultSelected,
                my_race_id: state.myRaceSelected,
                enemy_random_race_id: state.enemyRandomRaceSelected,
                enemy_race_id: state.enemyRaceSelected,
            });
        },
        getResultsFilters({commit}) {
            API.post('/api/auth/filters/results')
                .then(res => {
                    commit('SET_GAME_RESULTS', res.data.results);
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        getMapsFilters({state, commit}) {
            API.post('/api/auth/filters/maps')
                .then(res => {
                    commit('SET_MAPS', res.data.maps);
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        getRacesFilters({commit}) {
            API.post('/api/auth/filters/races')
                .then(res => {
                    commit('SET_MY_RACES', res.data.races);
                    commit('SET_ENEMY_RACES', res.data.races);
                })
                .catch(e => {
                    console.log(e.response)
                })
        },
        showErrors({commit}, error) {
            if (error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                Object.keys(error.response.data.errors).map(i => {
                    if (i === 'enemy_race_id') {
                        commit('SET_ENEMY_RACE_ERROR', "Enemy race is required");
                    }
                    if (i === 'map_id') {
                        commit('SET_MAP_ERROR', "Map is required");
                    }
                    if (i === 'my_race_id') {
                        commit('SET_MY_RACE_ERROR', "Race is required");
                    }
                    if (i === 'result_id') {
                        commit('SET_RESULT_ERROR', error.response.data.errors.result_id[0]);
                    }
                    if (i === 'enemy_random_race_id') {
                        commit('SET_ENEMY_RANDOM_RACE_ERROR', "Race is required if random selected");
                    }
                })
            }
        },
        resetErrors({ commit}) {
            commit('SET_ENEMY_RACE_ERROR', '')
            commit('SET_MAP_ERROR', '')
            commit('SET_MY_RACE_ERROR', '')
            commit('SET_RESULT_ERROR', '')
            commit('SET_ENEMY_RANDOM_RACE_ERROR', '')
        },
        resetValues({dispatch, commit}) {
            dispatch('resetErrors');

            commit('SET_ENEMY_CURRENT_MMR', 0);
            commit('SET_ENEMY_BEST_MMR', 0);
            commit('SET_ENEMY_NICKNAME', '');
            commit('SET_ENEMY_LOGIN', '');
            commit('SET_RESULT_COMMENT', '');
            commit('SET_GLOBAL_COMMENT', '');
            commit('SET_MAPS', []);
            commit('SET_RESULT_SELECTED', '');
            commit('SET_MY_RACE_SELECTED', '');
            commit('SET_ENEMY_RANDOM_RACE_SELECTED', '');
            commit('SET_MAP_SELECTED', '');
            commit('SET_GAME_RESULTS', []);
            commit('SET_MY_RACES', []);
            commit('SET_ENEMY_RACES', []);
            commit('SET_ENEMY_RACE_SELECTED', '');
        }
    }
}
