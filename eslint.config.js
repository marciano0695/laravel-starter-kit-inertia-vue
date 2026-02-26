import { antfu } from "@antfu/eslint-config";
import {
  defineConfigWithVueTs,
  vueTsConfigs,
} from "@vue/eslint-config-typescript";
import prettier from "eslint-config-prettier/flat";
import vue from "eslint-plugin-vue";

export default antfu(
  {
    type: "app",
    vue: true,
    typescript: true,
    formatters: true,
    stylistic: false,

    ignores: [
      "**/*.{css,md}",
      "*{composer,package,tsconfig,components,pint,boost,mcp}.json",
      ".{cursor,github,junie}/*",
      "resources/js/components/ui/*",
      "vendor",
      "node_modules",
      "public",
      "bootstrap/ssr",
      "tailwind.config.js",
      ".vscode/extensions.json",
      "resources/js/wayfinder/**",
    ],
  },
  {
    rules: {
      "vue/max-attributes-per-line": [
        "error",
        {
          singleline: {
            max: 2,
          },
          multiline: {
            max: 1,
          },
        },
      ],
      "ts/no-redeclare": "off",
      // "ts/consistent-type-definitions": ["error", "type"],
      "no-console": ["warn"],
      "antfu/no-top-level-await": ["off"],
      "antfu/if-newline": ["off"],
      "node/prefer-global/process": ["off"],
      "node/no-process-env": ["error"],
      "perfectionist/sort-imports": [
        "error",
        {
          tsconfigRootDir: ".",
        },
      ],
      "unicorn/filename-case": [
        "error",
        {
          case: "kebabCase",
          ignore: ["README.md"],
        },
      ],
    },
  },
).append(
  defineConfigWithVueTs(
    vue.configs["flat/essential"],
    vueTsConfigs.recommended,
    {
      rules: {
        "vue/multi-word-component-names": "off",
        "@typescript-eslint/no-explicit-any": "off",
      },
    },
    prettier,
  ),
);
