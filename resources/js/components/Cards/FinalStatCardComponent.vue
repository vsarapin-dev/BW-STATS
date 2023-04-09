<template>
    <v-card v-if="finalResults.percents && finalResults.maxStreaks" class="mt-10"
            :width="$vuetify.breakpoint.xs ? '100%' : '550'">
        <SnackBar :visible="showSnackBar" :message="'Stats updated successfully'" @close="showSnackBar = false"/>
        <v-container>
            <v-row>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="minSeasonMmr"
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Min MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="maxSeasonMmr"
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Max MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="finalSeasonMmr"
                            @input="inputUpdated"
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
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Season started"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="seasonEnded"
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Season ended"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-3 pb-0">
                        <v-text-field
                            v-model="placementMatches"
                            @input="inputUpdated"
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
        <div class="d-flex justify-end mr-2 mb-2">
            <v-btn v-if="showButton" x-small color="success" @click="updateFinalStartCard">Update</v-btn>
        </div>
    </v-card>
</template>

<script>
import API from "../../api";
import SnackBar from "../SnackBar";

export default {
    name: "FinalStatCardComponent",
    props: ['finalResults'],
    data() {
        return {
            showSnackBar: false,
            showButton: false,
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
    components: {
        SnackBar,
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
        inputUpdated() {
            this.showButton = true;
        },
        updateFinalStartCard() {
            this.showButton = false;
            API.post('/api/auth/result-cards/update', {
                'minSeasonMmr': this.minSeasonMmr,
                'maxSeasonMmr': this.maxSeasonMmr,
                'finalSeasonMmr': this.finalSeasonMmr,
                'placementMatches': this.placementMatches,
                'seasonStarted': this.seasonStarted,
                'seasonEnded': this.seasonEnded,
            })
            .then(res => {
                this.showSnackBar = true
            })
            .catch(e => {
                console.log(e);
            })
        },
    }
}
</script>
