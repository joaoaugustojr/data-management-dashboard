<script setup lang="ts">
const themeStore = useThemeStore();

useHead({
  htmlAttrs: {
    class: () => themeStore.theme,
  },
});
</script>

<template>
  <div class="h-dvh relative overflow-hidden">
    <div class="flex flex-col">
      <header class="h-[70px] bg-foreground/ border-b border-highlight">
        <UiNavBar />
      </header>
      <main class="p-4 max-h-[calc(100dvh-70px)] overflow-y-auto flex flex-col">
        <slot />
      </main>
    </div>
  </div>
</template>
