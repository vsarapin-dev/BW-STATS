<template>
    <v-card class="mt-10" :width="$vuetify.breakpoint.xs ? '100%' : '500'">
        <v-container>
            <v-row>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0" v-for="statColumn in bestStatColumnNames"
                                 :key="statColumn.modelValue"
                                 v-if="statColumn.columnNumber === 1">
                        <v-text-field
                            v-model="models[statColumn.modelValue]"
                            @input="checkLength(statColumn.modelValue)"
                            dense
                            class="caption"
                            :label="statColumn.name"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0 caption" v-for="statColumn in bestStatColumnNames"
                                 :key="statColumn.modelValue"
                                 v-if="statColumn.columnNumber === 2">
                        <v-text-field
                            v-model="models[statColumn.modelValue]"
                            @input="checkLength(statColumn.modelValue)"
                            dense
                            class="caption"
                            :label="statColumn.name"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0" v-for="statColumn in bestStatColumnNames"
                                 :key="statColumn.modelValue"
                                 v-if="statColumn.columnNumber === 3">
                        <v-text-field
                            v-model="models[statColumn.modelValue]"
                            @input="checkLength(statColumn.modelValue)"
                            dense
                            class="caption"
                            :label="statColumn.name"
                        >
                        </v-text-field>
                    </v-card-text>
                </v-col>
                <v-divider vertical class="mt-2 mb-2"></v-divider>
                <v-col xs="12">
                    <v-card-text class="pt-3 pb-0" v-for="statColumn in bestStatColumnNames"
                                 :key="statColumn.modelValue"
                                 v-if="statColumn.columnNumber === 4">
                        <v-text-field
                            v-model="models[statColumn.modelValue]"
                            @input="checkLength(statColumn.modelValue)"
                            dense
                            class="caption"
                            :label="statColumn.name"
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
            models: [],
            totals: {
                raw: {
                    total_games: 0,
                    real_wins: 0,
                    general_wins: 0,
                },
                percents: {
                    real_wins: 0,
                    general_wins: 0,
                },
            },
            bestStatColumnNames: [
                {name: 'Min MMR', columnNumber: 1, rule: 'counter', modelValue: 'minSeasonMmr'},
                {name: 'Max MMR', columnNumber: 1, rule: 'counter', modelValue: 'maxSeasonMmr'},
                {name: 'Final MMR', columnNumber: 1, rule: 'counter', modelValue: 'finalSeasonMmr'},
                {name: 'Win streak', columnNumber: 2},
                {name: 'Lose streak', columnNumber: 2},
                {name: 'Placement matches', columnNumber: 3},
                {name: 'Smurf %', columnNumber: 3},
                {name: 'W/O %', columnNumber: 3},
                {name: 'Season started', columnNumber: 4},
                {name: 'Season ended', columnNumber: 4},
            ],
        }
    },
    computed: {
        vModelArray() {
            return this.bestStatColumnNames
                .filter(i => 'modelValue' in i)
                .map(i => i.modelValue);
        }
    },
    created() {
        this.models = this.vModelArray;

    },
    watch: {
        finalResults(value) {
            this.finalResults = value;
        },
    },
    methods: {
        checkLength(value) {
            if (this.models[value].length > 4) {
                this.$nextTick(() => {
                    this.models[value] = this.models[value].slice(0, 4);
                })
            }
        },
    }
}
</script>

<style scoped>

</style>
