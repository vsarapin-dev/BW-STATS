<template>
    <div>
        <MmrMapRaceWinrateCardComponent :class="classForCard"/>
        <MapRaceWinrateCardComponent :class="classForCard"/>
        <v-row class="mt-5">
            <TotalStatCardComponent :class="classForCard"/>
            <MapWinrateCardComponent :class="classForCard"/>
            <RaceWinrateCardComponent :class="classForCard"/>
            <MmrWinrateCardComponent :class="classForCard"/>
            <BestMapsCardComponent :class="classForCard"/>
<!--            <FinalStatCardComponent :class="classForCard"/>-->
        </v-row>
    </div>
</template>

<script>
import MmrMapRaceWinrateCardComponent from "./Cards/MmrMapRaceWinrateCardComponent";
import MapRaceWinrateCardComponent from "./Cards/MapRaceWinrateCardComponent";
import TotalStatCardComponent from "./Cards/TotalStatCardComponent";
import MapWinrateCardComponent from "./Cards/MapWinrateCardComponent";
import RaceWinrateCardComponent from "./Cards/RaceWinrateCardComponent";
import MmrWinrateCardComponent from "./Cards/MmrWinrateCardComponent";
import BestMapsCardComponent from "./Cards/BestMapsCardComponent";
import FinalStatCardComponent from "./Cards/FinalStatCardComponent";

export default {
    name: "BaseTotals",
    components: {
        MmrMapRaceWinrateCardComponent,
        MapRaceWinrateCardComponent,
        TotalStatCardComponent,
        MapWinrateCardComponent,
        RaceWinrateCardComponent,
        MmrWinrateCardComponent,
        BestMapsCardComponent,
        FinalStatCardComponent
    },
    computed: {
        classForCard() {
            return this.$vuetify.breakpoint.smAndUp ? ['ml-2', 'mr-2'] : ['ml-1', 'mr-1'];
        },
    },
}
</script>
