import type { DefineComponent } from "vue";

import { createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { i18nVue } from "laravel-vue-i18n";
import { createSSRApp, h } from "vue";
import { renderToString } from "vue/server-renderer";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createServer(
  (page) =>
    createInertiaApp({
      page,
      render: renderToString,
      title: (title) => (title ? `${title} - ${appName}` : appName),
      resolve: (name) =>
        resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>("./pages/**/*.vue")),
      setup: ({ App, props, plugin }) =>
        createSSRApp({ render: () => h(App, props) })
          .use(plugin)
          .use(i18nVue, {
            lang: "pt",
            resolve: (lang: string) => {
              const langs = import.meta.glob<{ default: unknown }>("../../lang/*.json", { eager: true });
              return langs[`../../lang/${lang}.json`]?.default;
            },
          }),
    }),
  { cluster: true },
);
