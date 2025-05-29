export const useThemeStore = defineStore("theme", () => {
  const theme = useCookie<string>("theme", { default: () => "dark" });

  const toggleTheme = (): void => {
    theme.value = theme.value == "dark" ? "light" : "dark";
  };

  return { theme, toggleTheme };
});
