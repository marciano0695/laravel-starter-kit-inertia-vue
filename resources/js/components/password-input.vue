<script setup lang="ts">
import type { HTMLAttributes } from "vue";

import { Eye, EyeOff } from "@lucide/vue";
import { ref, useTemplateRef } from "vue";

import { Input } from "@/components/ui/input";
import { cn } from "@/lib/utils";

// Attributes such as name, autocomplete and passwordrules belong on the field itself, not the wrapper.
defineOptions({ inheritAttrs: false });

const props = defineProps<{
  class?: HTMLAttributes["class"];
}>();

const showPassword = ref(false);
const input = useTemplateRef<InstanceType<typeof Input>>("input");

defineExpose({
  focus: () => (input.value?.$el as HTMLInputElement | undefined)?.focus(),
});
</script>

<template>
  <div class="relative">
    <Input ref="input" v-bind="$attrs" :type="showPassword ? 'text' : 'password'" :class="cn('pr-10', props.class)" />
    <!-- Out of the tab order so keyboard users move straight from the field to the next one -->
    <button
      type="button"
      class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-3 text-muted-foreground hover:text-foreground focus-visible:ring-[3px] focus-visible:ring-ring focus-visible:outline-none"
      :aria-label="showPassword ? 'Hide password' : 'Show password'"
      :tabindex="-1"
      data-test="toggle-password-visibility"
      @click="showPassword = !showPassword"
    >
      <EyeOff v-if="showPassword" class="size-4" />
      <Eye v-else class="size-4" />
    </button>
  </div>
</template>
