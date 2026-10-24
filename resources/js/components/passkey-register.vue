<script setup lang="ts">
import { usePasskeyRegister } from "@laravel/passkeys/vue";
import { ref } from "vue";

import InputError from "@/components/input-error.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const emit = defineEmits<{
  success: [];
}>();

// Suggests a name like "Chrome on Mac" so the passkey is easy to recognise later.
function getDefaultPasskeyName(): string {
  if (typeof navigator === "undefined") {
    return "";
  }

  const userAgent = navigator.userAgent;

  // Order matters: Edge and Opera user agents also contain "Chrome", and Chrome's contains "Safari".
  const browser = [
    { pattern: /Edg|Edge/, name: "Edge" },
    { pattern: /OPR|Opera|OPiOS/, name: "Opera" },
    { pattern: /Firefox|FxiOS/, name: "Firefox" },
    { pattern: /Chrome|CriOS/, name: "Chrome" },
    { pattern: /Safari/, name: "Safari" },
  ].find(({ pattern }) => pattern.test(userAgent))?.name;

  const os = [
    { pattern: /iPhone/, name: "iPhone" },
    { pattern: /iPad|Macintosh(?=.*Mobile)/, name: "iPad" },
    { pattern: /Android/, name: "Android" },
    { pattern: /Mac/, name: "Mac" },
    { pattern: /Windows/, name: "Windows" },
  ].find(({ pattern }) => pattern.test(userAgent))?.name;

  return [browser, os].filter(Boolean).join(" on ");
}

const name = ref(getDefaultPasskeyName());
const showForm = ref(false);

const { register, isLoading, error, isSupported } = usePasskeyRegister({
  onSuccess: () => {
    name.value = "";
    showForm.value = false;
    emit("success");
  },
});

async function handleSubmit() {
  if (!name.value.trim()) {
    return;
  }

  await register(name.value);
}

function handleCancel() {
  showForm.value = false;
  name.value = "";
}
</script>

<template>
  <div v-if="!isSupported" class="text-sm text-muted-foreground">Passkeys are not supported in this browser.</div>

  <Button v-else-if="!showForm" variant="outline" data-test="add-passkey-button" @click="showForm = true">
    Add passkey
  </Button>

  <form v-else class="space-y-4 rounded-lg border border-border bg-muted/50 p-4" @submit.prevent="handleSubmit">
    <div class="grid gap-2">
      <Label for="passkey-name">Passkey name</Label>
      <Input
        id="passkey-name"
        v-model="name"
        type="text"
        placeholder="e.g., MacBook Pro, iPhone"
        class="mt-1 block w-full border-foreground/20"
        v-focus
      />
      <p class="text-xs text-muted-foreground">A name helps you identify this passkey later.</p>
    </div>

    <InputError v-if="error" :message="error" />

    <div class="flex gap-2">
      <Button type="submit" :disabled="isLoading || !name.trim()">
        {{ isLoading ? "Registering..." : "Register passkey" }}
      </Button>
      <Button type="button" variant="ghost" @click="handleCancel">Cancel</Button>
    </div>
  </form>
</template>
