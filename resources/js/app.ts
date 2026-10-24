import { createInertiaApp } from "@inertiajs/vue3";
import { i18nVue } from "laravel-vue-i18n";

import "../css/app.css";
import { initializeTheme } from "./composables/use-appearance";
import { initializeFlashToast } from "./lib/flash-toast";
import { vFocus } from "./lib/focus";

type LanguageFile = { default: Record<string, string> };

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

void createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  withApp(app, { ssr }) {
    app.directive("focus", vFocus);

    // Client side
    if (!ssr) {
      app.use(i18nVue, {
        resolve: async (lang: string) => {
          const langs = import.meta.glob<LanguageFile>("../../lang/*.json");
          return await langs[`../../lang/${lang}.json`]();
        },
      });
    } else {
      // SSR Side
      app.use(i18nVue, {
        lang: "pt",
        resolve: (lang: string) => {
          const langs = import.meta.glob<LanguageFile>("../../lang/*.json", { eager: true });
          return langs[`../../lang/${lang}.json`].default;
        },
      });
    }
  },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
