<template>
    <v-container>
        <v-row class="justify-center">
            <v-card width="500" class="ml-5 mr5 mt-5">
                <v-card-title>
                    Unity commands for dedicated server
                </v-card-title>
                <v-card-text>
                    <p class="text-body-2"><code>scp -r "D:\BUILD UNITY HERE" root@157.230.210.197:/root/FIGHTERS</code></p>
                    <p class="text-body-2"><code>chmod +x fighter.x86_64</code></p>
                    <p class="text-body-2"><code>./fighter.x86_64</code></p>
                </v-card-text>
            </v-card>
            <v-card width="500" class="ml-5 mr5 mt-5">
                <v-card-title>
                    PHP send telegram message from code
                </v-card-title>
                <v-card-text>
                        <code>
                            $apiToken = "6221023312:AAFIK-MmYO8aTvvTabPNNdRT2DsTAnn2bUU";<br>
                            $data = [<br>
                                <span class="tab-2">'chat_id' => '-1001930869363',</span><br>
                                <span class="tab-2">'text' => json_encode($quickReplies)</span><br>
                            ];<br>
                            $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );
                        </code>
                </v-card-text>
            </v-card>
        </v-row>
        <v-row class="justify-center">
            <v-card  width="500" class="ml-5 mr5 mt-5">
                <v-card-title>
                    Links
                </v-card-title>
                <v-card-text>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://www.digitalocean.com/community/tutorials/opening-a-port-on-linux" target="_blank">How To Open a Port on Linux  | DigitalOcean</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://github.com/rakutentech/laravel-request-docs" target="_blank">Effortless API documentation tool for Laravel applications</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://steamdb.info/tech/" target="_blank">SteamDB Technologies</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://docs.phpdoc.org/" target="_blank">PHP DOC block</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://github.com/Nutlope/aicommits" target="_blank">AICommit</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://forum.unity.com/threads/how-do-i-make-an-aiming-reticle-that-follows-objects.905063/" target="_blank">How do I make an aiming reticle that follows objects? - Unity Forum</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://answers.unity.com/questions/1815284/how-to-check-if-player-is-inside-enemy-field-of-vi.html" target="_blank">How to check if player is inside enemy field of view without RenderTexture? </a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://ecosoft.ua/ua/komplekt-kartridzhey-ecosoft-dlya-troynogo-filtra/" target="_blank">Ecosoft cartridge</a><br><br>
                    <a class="text-body-2 blue-accent-4--text text--lighten-5" href="https://www.pcgamingwiki.com/wiki/Home" target="_blank">PCGamingWiki aims to list fixes and workarounds for every single PC game.</a><br><br>
                </v-card-text>
            </v-card>
            <v-card  width="500" class="ml-5 mr5 mt-5">
                <v-card-title>
                    Redis
                </v-card-title>
                <v-card-text>
                    <p  class="text-body-2"><code>redis-server --port 6379 --protected-mode no</code></p>
                    <p  class="text-body-2"><code>redis-cli -h localhost -p 6379</code></p>
                    <p  class="text-body-2"><code>CONFIG SET protected-mode no</code></p>
                </v-card-text>
            </v-card>
            <v-card  width="500" class="ml-5 mr5 mt-5">
                <v-card-title>
                    DataTables.Net
                </v-card-title>
                <v-card-text>
                    <span class="text-body-2">
                        <code>
                        let datatable = jQuery(this).DataTable();<br>
                        console.log(datatable)<br>

                        jQuery(document)<br>
                            <span class="tab-1">.on('draw.dt', function() {</span><br>
                            <span class="tab-3">setTimeout(() => {</span><br>
                            <span class="tab-3">window.scrollTo({</span><br>
                            <span class="tab-3">top: 100,</span><br>
                            <span class="tab-3">left: 100,</span><br>
                            <span class="tab-3">behavior: "smooth",</span><br>
                            <span class="tab-2">});</span><br>
                            <span class="tab-1">}, 10);</span><br>
                        });<br><br>

                        Please note that, as with all DataTables emitted events, this event is triggered with the dt namespace. As such, to listen for this event, you must also use the dt namespace by simply appending .dt to your event name, as shown in the example below.
                        </code>
                    </span>
                </v-card-text>
            </v-card>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Notes"
}
</script>

<style scoped>
.tab-1 {
    display: inline-block;
    margin-left: 20px;
}
.tab-2 {
    display: inline-block;
    margin-left: 40px;
}
.tab-3 {
    display: inline-block;
    margin-left: 60px;
}
</style>
