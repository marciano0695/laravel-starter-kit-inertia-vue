import { router } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

export function initializeFlashToast(): void {
  router.on("flash", (event) => {
    const data = event.detail.flash.toast;

    if (!data) {
      return;
    }

    toast[data.type](data.message);
  });
}
