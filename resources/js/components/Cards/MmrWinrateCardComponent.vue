<template>
    <v-card v-if="mmrWinrate" tile class="mt-10 custom-elevation-2" :width="$vuetify.breakpoint.xs ? '100%' : '250'" max-height="200">
        <v-toolbar
            dark
            dense
            color="teal"
        >
            <v-toolbar-title>MMR Winrates</v-toolbar-title>
        </v-toolbar>
        <v-container>
            <v-list subheader dense>
                <v-list-item v-for="mmrData in mmrWinrate" :key="mmrData.map">
                    <v-list-item-content>
                        <v-list-item-title>{{ mmrData.from }} {{ mmrData.to > 5000 ? '+' : `- ${mmrData.to}` }}</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-content>
                        <v-list-item-title>
                            <span  :style="{color: getTextColor(mmrData.winPercentage)}">
                            {{ mmrData.rankName }} - {{ mmrData.winPercentage }}%
                        </span>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "MmrWinrateCardComponent",
    props: ['mmrWinrate'],
    watch: {
        mmrWinrate(value) {
            this.mmrWinrate = value;
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
