import { usePaymentStore } from "./usePaymentStore";
import { refDebounced } from "@vueuse/core";

export const usePaymentTableStore = defineStore("table", () => {
  const paymentStore = usePaymentStore();
  const init = ref(false);

  const columnFilters = useCookie("tableFilters", {
    default: () => {
      return {
        user_name: "",
        user_email: "",
        status: "",
      };
    },
  });

  const page = useCookie("tablePage", {
    default: () => {
      return 1;
    },
  });

  const columnFiltersDebounced = refDebounced(columnFilters, 200);

  watch(columnFiltersDebounced, () => {
    if (!init.value) {
      init.value = true;
      return;
    }

    page.value = 1;
    paymentStore.loadPayments();
  });

  watch(page, () => {
    paymentStore.loadPayments();
  });

  const clearFilters = () => {
    columnFilters.value.status = "";
    columnFilters.value.user_name = "";
    columnFilters.value.user_email = "";
    page.value = 1;
  };

  const hasFilters = computed(() => {
    return Object.values(columnFilters.value).some((v) => v);
  });

  return { columnFilters, page, clearFilters, hasFilters };
});
