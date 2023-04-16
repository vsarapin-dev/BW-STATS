<template>
    <v-card v-if="generalStats" tile class="mt-10" :width="$vuetify.breakpoint.xs ? '100%' : '270'">
        <v-toolbar
            dark
            dense
            color="teal"
        >
            <v-toolbar-title>General</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Games</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ generalStats.gamesCount }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Stats</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ generalStats.generalStatsCount }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Real stats</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ generalStats.realStatsCount }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Winrate %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.generalWinratePercent) }">
                            {{ generalStats.generalWinratePercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Winrate real %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.winrateRealPercent) }">
                            {{ generalStats.winrateRealPercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Smurfs</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.smurfsCount) }">
                            {{ generalStats.smurfsCount }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Smurfs %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.smurfsPercent) }">
                            {{ generalStats.smurfsPercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>W/O</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.woCount) }">
                            {{ generalStats.woCount }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>W/O %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.woPercent) }">
                            {{ generalStats.woPercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Drop</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.dropCount) }">
                            {{ generalStats.dropCount }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Drop %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.dropPercent) }">
                            {{ generalStats.dropPercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Draw</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.drawCount) }">
                            {{ generalStats.drawCount }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Draw %</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title :style="{color:  getTextColor(generalStats.drawPercent) }">
                            {{ generalStats.drawPercent }}%
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "TotalStatCardComponent",
    props: ['generalStats'],
    watch: {
        generalStats(value) {
            this.generalStats = value;
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
