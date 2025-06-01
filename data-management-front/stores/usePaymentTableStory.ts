import { usePaymentStore } from "./usePaymentStore";
import { refDebounced } from "@vueuse/core";

export const usePaymentTableStore = defineStore("table", () => {
  const paymentStore = usePaymentStore();

  const columnFilters = useCookie("tableFilters", {
    default: () => {
      return {
        user_name: "",
        user_email: "",
        status: "",
      };
    },
  });

  const clearFilters = () => {
    columnFilters.value.status = "";
    columnFilters.value.user_name = "";
    columnFilters.value.user_email = "";
  };

  const hasFilters = computed(() => {
    return Object.values(columnFilters.value).some((v) => v);
  });

  const columnFiltersDebounced = refDebounced(columnFilters, 200);

  watch(columnFiltersDebounced, () => {
    paymentStore.loadPayments();
  });

  return { columnFilters, clearFilters, hasFilters };
});
