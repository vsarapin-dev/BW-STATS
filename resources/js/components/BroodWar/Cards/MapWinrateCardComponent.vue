<template>
    <v-card v-if="$store.getters['mapsWinrate/mapsWinrate']" tile class="mt-10 custom-elevation-2" :width="$vuetify.breakpoint.xs ? '100%' : '290'">
        <v-toolbar
            dark
            dense
            color="teal"
        >
            <v-toolbar-title>Map Winrates</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-list-item v-for="mapData in $store.getters['mapsWinrate/mapsWinrate']" :key="mapData.map">
                    <v-list-item-content>
                        <v-list-item-title>{{ mapData.map }}</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ mapData.wins }}W - {{ mapData.losses }}L =
                            <span :style="{color: getTextColor(mapData.win_percentage)}">
                                {{ mapData.win_percentage }}%
                            </span>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider class="mt-3 mb-3"></v-divider>
                <v-list-item v-for="(mapData, index) in $store.getters['mapsWinrate/mapsWinrate']" :key="`${mapData.map}-${index}`">
                    <v-list-item-content>
                        <v-list-item-title>{{ mapData.map }} games</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>{{ mapData.games_played }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "MapWinrateCardComponent",
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
