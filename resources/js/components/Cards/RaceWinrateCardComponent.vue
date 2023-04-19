<template>
    <v-card v-if="raceWinrate" tile class="mt-10 custom-elevation-2" :width="$vuetify.breakpoint.xs ? '100%' : '250'" height="450">
        <v-toolbar
            dark
            dense
            color="teal"
        >
            <v-toolbar-title>Race Winrates</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-list-item v-for="raceData in raceWinrate" :key="raceData.map">
                    <v-list-item-content>
                        <v-list-item-title>Winrate {{ raceData.matchupShorthand }}</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>
                            <span  :style="{color: getTextColor(raceData.winPercentage)}">
                            {{ raceData.winPercentage }}%
                        </span>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider class="mt-3 mb-3"></v-divider>
                <v-list-item v-for="(raceData, index) in raceWinrate" :key="`${raceData.map}-${index}`">
                    <v-list-item-content>
                        <v-list-item-title>{{ raceData.matchupShorthand }} games</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ raceData.gamesPlayed }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "RaceWinrateCardComponent",
    props: ['raceWinrate'],
    watch: {
        raceWinrate(value) {
            this.raceWinrate = value;
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
