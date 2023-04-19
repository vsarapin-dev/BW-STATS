<template>
    <v-card v-if="mapRaceWinrate" tile class="mt-10 custom-elevation-2" width="100%">
        <v-toolbar dark dense color="teal" class="d-flex justify-center">
            <v-toolbar-title>Map Race Winrates</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-row>
                    <v-col v-for="(keyName, index) in Object.keys(mapRaceWinrate)"
                           :key="`${keyName}-${index}`">
                        <div class="mt-5">
                            <v-toolbar dark dense color="blue darken-3">
                                <v-toolbar-title>{{ keyName }}</v-toolbar-title>
                            </v-toolbar>

                            <v-list-item v-for="mapRaceData in mapRaceWinrate[keyName]"
                                         :key="`${mapRaceData.matchupShorthand}-${index}`" dense>
                                <v-list-item-content>
                                    <v-list-item-title>{{ mapRaceData.matchupShorthand }}</v-list-item-title>
                                </v-list-item-content>

                                <v-list-item-content>
                                    <v-list-item-title>
                                                <span :style="{color: getTextColor(mapRaceData.winPercentage)}">
                                                {{ mapRaceData.winPercentage }}%
                                            </span>
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </div>
                    </v-col>
                </v-row>
            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "MapRaceWinrateCardComponent",
    props: ['mapRaceWinrate'],
    watch: {
        mapRaceWinrate(value) {
            if (value)
            {
                Object.keys(value).map(i => {
                    return value[i].sort(function(a, b) {
                        if (a.matchupShorthand === 'PvR') {
                            return 1;
                        } else if (b.matchupShorthand === 'PvR') {
                            return -1;
                        } else {
                            return a.matchupShorthand.localeCompare(b.matchupShorthand);
                        }
                    });
                })
            }
            this.mapRaceWinrate = value;
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

</style>
