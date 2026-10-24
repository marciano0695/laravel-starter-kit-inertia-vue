<script setup lang="ts">
defineProps<{
  // Referenced by the password field's aria-describedby so screen readers read the rules with it.
  id: string;
  hints: string[];
}>();
</script>

<template>
  <ul v-if="hints.length" :id="id" class="list-inside list-disc space-y-0.5 text-xs text-muted-foreground">
    <li v-for="hint in hints" :key="hint">{{ hint }}</li>
  </ul>
</template>
