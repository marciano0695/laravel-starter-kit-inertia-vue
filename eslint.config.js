import { antfu } from "@antfu/eslint-config";

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
      "ts/no-redeclare": "off",
      "ts/no-explicit-any": "off",
      // "ts/consistent-type-definitions": ["error", "type"],
      "no-console": ["warn"],
      "antfu/no-top-level-await": ["off"],
      "antfu/if-newline": ["off"],
      "node/prefer-global/process": ["off"],
      "node/no-process-env": ["error"],
      "perfectionist/sort-imports": [
        "error",
        {
          tsconfig: { rootDir: "." },
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
);
