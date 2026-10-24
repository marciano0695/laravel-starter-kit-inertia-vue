import type { Directive } from "vue";

// The autofocus attribute only works on a full page load, so client-side Inertia visits need this instead.
export const vFocus: Directive<HTMLElement, boolean | undefined> = {
  mounted(element, binding) {
    if (binding.value === false) {
      return;
    }

    // Wrapper components such as password-input render a div, so focus the field inside them.
    const field = element.matches("input, textarea, select")
      ? element
      : element.querySelector<HTMLElement>("input, textarea, select");

    field?.focus();
  },
};
