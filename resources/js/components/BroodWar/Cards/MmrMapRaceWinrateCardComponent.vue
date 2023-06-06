<template>
    <v-card v-if="mmrMapRaceWinrate" tile class="mt-10 custom-elevation-2" width="100%">
        <v-toolbar dark dense color="teal" class="d-flex justify-center">
            <v-toolbar-title>MMR/Map/Race Winrates</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-row>
                    <v-col>
                        <v-row v-for="(rankData, index) in mmrMapRaceRanks" :key="`${rankData}-${index}`">
                            <v-col cols="2" class="d-flex justify-center align-center">
                                <v-toolbar dark dense color="blue darken-3">
                                    <v-toolbar-title>{{ rankData.rank }}</v-toolbar-title>
                                </v-toolbar>
                            </v-col>
                            <v-col cols="1"></v-col>
                            <v-col cols="1">
                                <div class="d-flex" v-for="(map, j) in rankData.maps" :key="`${rankData.maps}-${j}`">
                                    <span>
                                        <span class="body-2">{{ Object.keys(map)[0] }}</span>
                                    </span>
                                </div>
                            </v-col>
                            <v-col cols="8">
                                <div class="d-flex" v-for="(map, j) in rankData.maps" :key="`${rankData.maps}-${j}`">
                                    <span v-for="(matchups, i) in map" :key="`${map}-${i}`">
                                        <span v-for="(matchup, idex) in matchups" :key="`${matchup}-${idex}`"
                                              style="display: inline-block !important; width: 120px !important; cursor: pointer" class="tooltip text-sm-body-2">
                                            {{ matchup.matchupShorthand }}
                                            <span :style="{color: getTextColor(matchup.winPercentage)}" style="cursor: pointer" class="ml-2 text-sm-body-2">
                                                {{ matchup.winPercentage }} {{
                                                    matchup.winPercentage !== '-' ? '%' : ''
                                                }}
                                            </span>
                                            <span class="ml-4 tooltiptext">{{ matchup.totalWins }}W - {{ matchup.totalLosses }}L</span>
                                        </span>
                                    </span>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "MmrMapRaceWinrateCardComponent",
    computed: {
        mmrMapRaceWinrate() {
            return this.$store.getters['mmrMapRace/mmrMapRaceWinrate'];
        },
        mmrMapRaceRanks() {
            return this.$store.getters['mmrMapRace/ranks'];
        },
    },
    methods: {
        getTextColor(value) {
            switch (true) {
                case value <= 45:
                    return 'red';
                case value > 45 && value < 55:
                    return '#a98600';
                case value >= 55:
                    return 'green';
            }
        },
    },
}
</script>

<style scoped>
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    top: 100%;
    left: 0%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>
