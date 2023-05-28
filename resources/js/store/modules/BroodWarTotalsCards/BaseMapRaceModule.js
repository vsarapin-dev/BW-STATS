import API from "../../../api";

export default {
    state: () => ({
        mapRace: null,
    }),
    getters: {
        mapRace: state => state.mapRace,
    },
    mutations: {
        SET_MAP_RACE(state, payload) {
            if (Array.isArray(payload)) {
                state.mapRace = payload;
            } else {
                state.mapRace = payload;
            }

            Object.keys(state.mapRace).map(i => {
                return state.mapRace[i].sort(function (a, b) {
                    if (a.matchupShorthand === 'PvR') {
                        return 1;
                    } else if (b.matchupShorthand === 'PvR') {
                        return -1;
                    } else {
                        return a.matchupShorthand.localeCompare(b.matchupShorthand);
                    }
                });
            })
        },
        RESET_STATE(state) {
            state.mapRace = null;
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/map-race', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('map_race') &&
                        res.data.map_race !== null) {
                        commit('SET_MAP_RACE', res.data.map_race);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
