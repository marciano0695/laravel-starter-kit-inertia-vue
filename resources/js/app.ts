import { createInertiaApp } from "@inertiajs/vue3";
import { i18nVue } from "laravel-vue-i18n";

import "../css/app.css";
import { initializeTheme } from "./composables/use-appearance";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  withApp(app, { ssr }) {
    // Client side
    if (!ssr) {
      app.use(i18nVue, {
        resolve: async (lang: string) => {
          const langs = import.meta.glob("../../lang/*.json");
          return await langs[`../../lang/${lang}.json`]();
        },
      });
    } else {
      // SSR Side
      app.use(i18nVue, {
        lang: "pt",
        resolve: (lang: string) => {
          const langs = import.meta.glob("../../lang/*.json", { eager: true });
          return (langs[`../../lang/${lang}.json`] as { default: unknown }).default;
        },
      });
    }
  },
});

// This will set light / dark mode on page load...
initializeTheme();
