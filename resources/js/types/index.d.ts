import type { InertiaLinkProps } from "@inertiajs/vue3";
import type { LucideIcon } from "@lucide/vue";

import type { Inertia } from "@/wayfinder/types";

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavItem {
  title: string;
  href: NonNullable<InertiaLinkProps["href"]>;
  icon?: LucideIcon;
  isActive?: boolean;
}

export type BreadcrumbItemType = BreadcrumbItem;

// The backend has no named passkey shape, so derive it from the page that sends one.
export type Passkey = Inertia.Pages.UserPasskey.Index["passkeys"][number];

export interface FlashToast {
  type: "success" | "info" | "warning" | "error";
  message: string;
}
