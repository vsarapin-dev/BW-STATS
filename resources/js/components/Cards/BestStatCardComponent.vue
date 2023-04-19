<template>
    <v-card v-if="allResults" class="custom-elevation-2 mt-10" :width="$vuetify.breakpoint.xs ? '100%' : '250'">
        <v-container>
            <v-row>
                <v-col sm="4">
                    <v-row v-for="(dataResult, index) in allResults" :key="`${dataResult.name}-${index}`">
                        <v-card-text class="text-truncate">
                            <span class="caption">{{ dataResult.map_name }}</span>
                            <v-divider class="mt-1" v-if="allResults.length - 1 > index"></v-divider>
                        </v-card-text>
                    </v-row>
                </v-col>
                <v-divider vertical class="mt-4 mb-4"></v-divider>
                <v-col sm="4">
                    <v-row v-for="(dataResult, index) in allResults" :key="`${dataResult.cell_color}-${index}`">
                        <v-card-text class="text-center" :style="{backgroundColor: dataResult.cell_color}">
                            <span class="caption">{{ dataResult.win_percentage }}%</span>
                        </v-card-text>
                    </v-row>
                </v-col>
                <v-divider vertical class="mt-4 mb-4"></v-divider>
                <v-col sm="4">
                    <v-row v-for="(dataResult, index) in allResults" :key="`${dataResult.win_percentage}-${index}`">
                        <v-card-text class="text-center">
                            <span class="caption">{{ dataResult.wins }} - {{ dataResult.losses }}</span>
                            <v-divider class="mt-1" v-if="allResults.length - 1 > index"></v-divider>
                        </v-card-text>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "BestStatCardComponent",
    props: ['finalSeasonDataResults'],
    computed: {
        allResults() {
            if (!this.finalSeasonDataResults) return;

            return this.finalSeasonDataResults.map(i => {
                let parsedWinPercentage = parseFloat(i.win_percentage);
                return {
                    ...i,
                    win_percentage: parsedWinPercentage.toFixed(2),
                    cell_color: this.getColor(parsedWinPercentage),
                }
            });
        },
    },
    methods: {
        getColor(value) {
            switch (true) {
                case value < 45:
                    return '#a70000';
                case value >= 45 && value < 55:
                    return '#a98600';
                case value >= 55:
                    return '#607c3c';
            }
        }
    },
}
</script>

<style scoped>

</style>
