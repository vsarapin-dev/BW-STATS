<template>
    <v-card v-if="finalResults.percents && finalResults.maxStreaks" class="mt-10" :width="$vuetify.breakpoint.xs ? '100%' : '550'">
        <v-container>
            <v-row>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="minSeasonMmr"
                            @input="checkLength(minSeasonMmr)"
                            dense
                            class="caption"
                            label="Min MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="maxSeasonMmr"
                            @input="checkLength(maxSeasonMmr)"
                            dense
                            class="caption"
                            label="Max MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="finalSeasonMmr"
                            @input="checkLength(finalSeasonMmr)"
                            dense
                            class="caption"
                            label="Final MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="seasonStarted"
                            dense
                            class="caption"
                            label="Season started"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="seasonEnded"
                            dense
                            class="caption"
                            label="Season ended"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="placementMatches"
                            dense
                            class="caption"
                            label="Placement matches"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            disabled
                            v-model="smurfPercent"
                            dense
                            class="caption"
                            label="Smurfs"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            disabled
                            v-model="woPercent"
                            dense
                            class="caption"
                            label="W/O"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0 caption">
                        <v-text-field
                            disabled
                            v-model="winStreak"
                            dense
                            class="caption"
                            label="Win streak"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0 caption">
                        <v-text-field
                            disabled
                            v-model="loseStreak"
                            dense
                            class="caption"
                            label="Lose streak"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "FinalStatCardComponent",
    props: ['finalResults'],
    data() {
        return {
            minSeasonMmr: null,
            maxSeasonMmr: null,
            finalSeasonMmr: null,
            winStreak: null,
            loseStreak: null,
            placementMatches: null,
            smurfPercent: null,
            woPercent: null,
            seasonStarted: null,
            seasonEnded: null,
        }
    },
    watch: {
        finalResults(value) {
            this.woPercent = value?.percents?.woPercent + '%';
            this.smurfPercent = value?.percents?.smurfPercent + '%';
            this.winStreak = value?.maxStreaks?.maxDefeats;
            this.loseStreak = value?.maxStreaks?.maxWins;
        },
    },
    methods: {
        checkLength(value) {
            if (this[value].length > 4) {
                this.$nextTick(() => {
                    this[value] = this[value].slice(0, 4);
                })
            }
        },
    }
}
</script>

<style scoped>

</style>
