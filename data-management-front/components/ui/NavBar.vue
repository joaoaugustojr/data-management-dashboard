<script setup lang="ts">
import type { NavItem } from "~/types/navbar";

const themeStore = useThemeStore();

const navItems: NavItem[] = [
  {
    name: "Github",
    to: "https://github.com/joaoaugustojr",
    external: true,
    icon: "hugeicons:github",
  },
  {
    name: "Theme",
    icon: "",
    action: () => themeStore.toggleTheme(),
  },
];

const themeIcon = computed(() => {
  return themeStore.theme === "light"
    ? "line-md:moon-alt-to-sunny-outline-loop-transition"
    : "line-md:sunny-filled-loop-to-moon-filled-loop-transition";
});
</script>

<template>
  <ClientOnly>
    <nav class="flex justify-end items-center h-full gap-4 mx-4">
      <ul class="flex justify-center gap-1">
        <li v-for="item in navItems" :key="item.name">
          <NuxtLink
            class="flex hover:bg-highlight rounded-full p-2"
            active-class="bg-highlight [&>_span]:text-theme"
            :to="item.to"
            :target="item.external ? '_blank' : ''"
            :title="item.name"
            @click.stop="item.action && item.action()"
          >
            <Icon :name="item.icon || themeIcon" size="17" />
          </NuxtLink>
        </li>
      </ul>
    </nav>
  </ClientOnly>
</template>
