import API from "../../../api";

export default {
    state: () => ({
        mmrMapRaceWinrate: null,
        ranks: [],
    }),
    getters: {
        mmrMapRaceWinrate: state => state.mmrMapRaceWinrate,
        ranks: state => state.ranks,
    },
    mutations: {
        SET_MMR_MAP_RACE(state, payload) {
            if (Array.isArray(payload)) {
                state.mmrMapRaceWinrate = payload;
            } else {
                state.mmrMapRaceWinrate = payload;
            }

            if (state.mmrMapRaceWinrate) {
                let valueClone = {...state.mmrMapRaceWinrate};
                let allMatchups = [];
                Object.keys(valueClone).map(i => {
                    Object.keys(state.mmrMapRaceWinrate[i]).map(j => {
                        valueClone[i][j].map(g => {
                            allMatchups.push(g.matchupShorthand);
                        })
                    });
                });
                allMatchups = allMatchups.filter((item, index) => allMatchups.indexOf(item) === index);
                allMatchups.sort(function (a, b) {
                    if (a === 'PvR') {
                        return 1;
                    } else if (b.includes('vR')) {
                        return -1;
                    } else {
                        return a.localeCompare(b);
                    }
                });

                state.ranks = [];
                Object.keys(state.mmrMapRaceWinrate).map(i => {
                    let mapsWithValues = Object.keys(state.mmrMapRaceWinrate[i]).map(j => {
                        allMatchups.map(l => {
                            if (state.mmrMapRaceWinrate[i][j].some(item => item.matchupShorthand == l) == false) {
                                state.mmrMapRaceWinrate[i][j].push({
                                    matchupShorthand: l,
                                    winPercentage: '-',
                                })
                            }
                        })
                        return {
                            [j]: state.mmrMapRaceWinrate[i][j],
                        }
                    })
                    mapsWithValues.map(k => {
                        return Object.keys(k).map(f => {
                            return k[f].sort(function (a, b) {
                                if (a.matchupShorthand === 'PvR') {
                                    return 1;
                                } else if (b.matchupShorthand === 'PvR') {
                                    return -1;
                                } else {
                                    return a.matchupShorthand.localeCompare(b.matchupShorthand);
                                }
                            });
                        });
                    })
                    state.ranks.push({rank: i, maps: mapsWithValues});
                })
            }
        },
        RESET_STATE(state) {
            state.mmrMapRaceWinrate = null;
            state.ranks = [];
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/mmr-map-race', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('mmr_map_race') &&
                        res.data.mmr_map_race !== null) {
                        commit('SET_MMR_MAP_RACE', res.data.mmr_map_race);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
