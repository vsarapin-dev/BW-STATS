import Vue from "vue";
import router from "./router";
import vuetify from "./plugins/vuetify";
import store from './store';
import * as Sentry from "@sentry/vue";
import Index from "./components/Index";

require('./bootstrap');

Sentry.init({
    Vue,
    // dsn: null,
    dsn: "https://39c58b4e9b85481d815916e12679922c@o4505234817351680.ingest.sentry.io/4505234818859008",
    integrations: [
        new Sentry.BrowserTracing({
            routingInstrumentation: Sentry.vueRouterInstrumentation(router),
        }),
        new Sentry.Replay(),
    ],

    // Set tracesSampleRate to 1.0 to capture 100%
    // of transactions for performance monitoring.
    // We recommend adjusting this value in production
    tracesSampleRate: 1.0,

    // Capture Replay for 10% of all sessions,
    // plus for 100% of sessions with an error
    replaysSessionSampleRate: 0.1,
    replaysOnErrorSampleRate: 1.0,
});

new Vue({
    el: '#app',
    components: {
         Index,
     },
    router,
    store,
    vuetify,
});
