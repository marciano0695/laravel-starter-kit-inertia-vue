import inertia from "@inertiajs/vite";
import { wayfinder } from "@laravel/vite-plugin-wayfinder";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import i18n from "laravel-vue-i18n/vite";
import { defineConfig } from "vite-plus";

const generatedPaths = [
  "resources/js/components/ui/**",
  "resources/js/actions/**",
  "resources/js/routes/**",
  "resources/js/wayfinder/**",
];

export default defineConfig({
  lint: {
    options: {
      typeAware: true,
      typeCheck: true,
    },
    plugins: ["eslint", "typescript", "unicorn", "oxc", "vue", "node"],
    rules: {
      "no-console": "warn",
      "node/no-process-env": "error",
      "unicorn/filename-case": ["error", { case: "kebabCase" }],
    },
    ignorePatterns: ["vite.config.ts", ...generatedPaths],
  },
  fmt: {
    printWidth: 80,
    tabWidth: 2,
    useTabs: false,
    semi: true,
    singleQuote: false,
    overrides: [
      {
        files: ["**/*.vue", "**/*.ts"],
        options: {
          printWidth: 120,
        },
      },
    ],
    sortTailwindcss: {
      functions: ["clsx", "cn", "cva"],
      stylesheet: "resources/css/app.css",
    },
    // Same grouping as the previous perfectionist (ESLint) setup, so existing imports stay put.
    sortImports: {
      groups: [
        "type-import",
        ["value-builtin", "value-external"],
        "type-internal",
        "value-internal",
        ["type-parent", "type-sibling", "type-index"],
        ["value-parent", "value-sibling", "value-index"],
        "unknown",
      ],
    },
    ignorePatterns: [...generatedPaths, "resources/views/mail/**"],
  },
  server: {
    watch: {
      // Blade view cache churns on every request and would trigger needless reloads.
      ignored: ["**/storage/framework/views/**"],
    },
  },
  plugins: [
    laravel({
      input: ["resources/js/app.ts"],
      refresh: true,
    }),
    inertia(),
    tailwindcss(),
    wayfinder({
      command: "php -d memory_limit=512M artisan wayfinder:generate",
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    i18n(),
  ],
});
