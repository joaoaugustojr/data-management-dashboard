// https://nuxt.com/docs/api/configuration/nuxt-config

import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-05-15",
  devtools: { enabled: true },

  modules: ["@pinia/nuxt", "@nuxtjs/google-fonts", "@nuxt/icon", "@nuxt/ui"],

  css: ["~/assets/css/main.css"],

  vite: {
    plugins: [tailwindcss()],
  },

  googleFonts: {
    display: "block",
    families: {
      Roboto: {
        wght: "100..700",
        ital: "100..700",
      },
      "Open Sans": {
        wght: "200..900",
        ital: "200..700",
      },
      Poppins: [100, 200, 300, 400, 500, 600, 700, 800, 900],
    },
  },
});