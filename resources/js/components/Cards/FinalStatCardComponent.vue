<template>
    <v-card v-if="finalResults.percents && finalResults.maxStreaks" class="mt-10"
            :width="$vuetify.breakpoint.xs ? '100%' : '550'">
        <SnackBar :visible="showSnackBar" :message="'Stats updated successfully'" @close="showSnackBar = false"/>
        <v-container>
            <v-row>
                <v-col xs="12">
                    <v-card-text class="pt-0 pb-0">
                        <v-text-field
                            v-model="minSeasonMmr"
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Min MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0">
                        <v-text-field
                            v-model="maxSeasonMmr"
                            @input="inputUpdated"
                            dense
                            class="caption"
                            label="Max MMR"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0">
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
                    <v-card-text class="pt-0 pb-0">
                        <v-menu
                            ref="menuSeasonStarted"
                            v-model="menuSeasonStarted"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="computedDateOfStartSeasonFormatted"
                                    label="Season started"
                                    v-bind="attrs"
                                    v-on="on"
                                    class="caption"
                                    readonly
                                    dense
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="dateSeasonStarted"
                                no-title
                                @input="inputUpdated"
                            ></v-date-picker>
                        </v-menu>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0">
                        <v-menu
                            ref="menuSeasonStarted"
                            v-model="menuSeasonEnded"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="computedDateOfEndSeasonFormatted"
                                    label="Season ended"
                                    v-bind="attrs"
                                    v-on="on"
                                    class="caption"
                                    readonly
                                    dense
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="dateSeasonEnded"
                                no-title
                                @input="inputUpdated"
                            ></v-date-picker>
                        </v-menu>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0">
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
                    <v-card-text class="pt-0 pb-0">
                        <v-text-field
                            disabled
                            v-model="smurfPercent"
                            dense
                            class="caption"
                            label="Smurfs"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0">
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
                    <v-card-text class="pt-0 pb-0 caption">
                        <v-text-field
                            disabled
                            v-model="winStreak"
                            dense
                            class="caption"
                            label="Win streak"
                        >
                        </v-text-field>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-0 caption">
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
    props: ['finalResults', 'selectedSeason'],
    data() {
        return {
            dateSeasonStarted: null,
            dateSeasonEnded: null,
            menuSeasonStarted: false,
            menuSeasonEnded: false,
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
    computed: {
        computedDateOfStartSeasonFormatted () {
            return this.formatDate(this.dateSeasonStarted)
        },
        computedDateOfEndSeasonFormatted () {
            return this.formatDate(this.dateSeasonEnded)
        },
    },
    methods: {
        formatDate(date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${day}-${month}-${year}`
        },
        parseDate(date) {
            if (!date) return null

            const [day, month, year] = date.split('-')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
        },
        inputUpdated() {
            this.menuSeasonEnded = false;
            this.menuSeasonStarted = false;
            this.showButton = true;
        },
        updateFinalStartCard() {
            this.showButton = false;
            let dataToSend = {
                'season_id': this.selectedSeason,
                'min_season_mmr': this.minSeasonMmr,
                'max_season_mmr': this.maxSeasonMmr,
                'final_season_mmr': this.finalSeasonMmr,
                'placement_matches': this.placementMatches,
                'season_started': this.parseDate(this.formatDate(this.dateSeasonStarted)),
                'season_ended': this.parseDate(this.formatDate(this.dateSeasonEnded)),
            };
            dataToSend = Object.fromEntries(Object.entries(dataToSend).filter(([_, v]) => v != null))

            console.log(dataToSend)

            API.post('/api/auth/total-values/update', dataToSend)
            .then(res => {
                console.log(res.data)
            })
            .catch(e => {
                console.log(e.response);
            })
        },
    }
}
</script>
