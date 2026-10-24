import type { vFocus } from "@/lib/focus";
import type { AppPageProps, FlashToast } from "@/types/index";

// Extend ImportMeta interface for Vite...
declare module "vite/client" {
  interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    [key: string]: string | boolean | undefined;
  }

  interface ImportMeta {
    readonly env: ImportMetaEnv;
    readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
  }
}

declare module "@inertiajs/core" {
  interface PageProps extends InertiaPageProps, AppPageProps {}

  // Merges with the Wayfinder-generated InertiaConfig to type Inertia::flash() data.
  interface InertiaConfig {
    flashDataType: {
      toast?: FlashToast;
    };
  }
}

declare module "vue" {
  interface ComponentCustomProperties {
    $inertia: typeof Router;
    $page: Page;
    $headManager: ReturnType<typeof createHeadManager>;
  }

  interface GlobalDirectives {
    vFocus: typeof vFocus;
  }
}

declare module "*.vue" {
  import type { DefineComponent } from "vue";
  const component: DefineComponent<object, object, any>;
  export default component;
}
