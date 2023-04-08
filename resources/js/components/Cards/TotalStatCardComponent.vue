<template>
    <v-card class="mt-10" :width="$vuetify.breakpoint.xs ? '100%' : '320'">
        <v-container>
            <v-row>
                <v-col sm="4">
                    <v-row>
                        <v-card-text class="caption text-truncate">
                            Total games
                            <v-divider class="mt-1"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-truncate">
                            Real wins
                            <v-divider class="mt-1"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-truncate">
                            General wins
                        </v-card-text>
                    </v-row>
                </v-col>
                    <v-divider class="mt-2 mb-2" vertical></v-divider>
                <v-col sm="4">
                    <v-row>
                        <v-card-text class="caption text-center">
                            {{ totals.raw.total_games }}
                            <v-divider class="mt-1"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-center">
                            {{ totals.raw.real_wins }}
                            <v-divider class="mt-1"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-center">
                            {{ totals.raw.general_wins }}
                        </v-card-text>
                    </v-row>
                </v-col>
                    <v-divider class="mt-2 mb-2" vertical></v-divider>
                <v-col sm="4">
                    <v-row>
                        <v-card-text class="caption text-center">
                            <v-divider class="mt-6"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-center"
                                     :style="{color:  totals.percents.real_wins >= 50 ? 'green' : 'red'}">
                            {{ totals.percents.real_wins }}%
                            <v-divider class="mt-1"></v-divider>
                        </v-card-text>
                    </v-row>
                    <v-row>
                        <v-card-text class="caption text-center"
                                     :style="{color: totals.percents.general_wins >= 50 ? 'green' : 'red'}">
                            {{ totals.percents.general_wins }}%
                        </v-card-text>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "TotalStatCardComponent",
    props: ['totals'],
    watch: {
        totals(value) {
          this.totals = value;
        },
    }
}
</script>

<style scoped>

</style>
