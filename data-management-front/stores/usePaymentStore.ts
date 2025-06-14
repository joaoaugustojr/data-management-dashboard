import {
  deletePayment,
  getPayments,
  createPayment as create,
  updatePayment as update,
} from "~/services/payment";

import { usePaymentTableStore } from "./usePaymentTableStory";

import type { Payment } from "~/types/payment";

export const usePaymentStore = defineStore("payments", () => {
  const payments = ref<Payment[]>([]);
  const loading = ref(false);
  const total = ref(0);

  const statusOptions = ref([
    {
      label: "Pending",
      id: "pending",
    },
    {
      label: "Paid",
      id: "paid",
    },
    {
      label: "Failed",
      id: "failed",
    },
    {
      label: "Refunded",
      id: "refunded",
    },
  ]);

  const loadPayments = async (params?: any) => {
    const { columnFilters, page, hasFilters } = usePaymentTableStore();

    loading.value = true;

    if (hasFilters) {
      params = {
        ...params,
        ...columnFilters,
      };
    }

    if (page) {
      params = {
        ...params,
        page,
      };
    }

    try {
      const response = await getPayments(params);
      payments.value = response.data.value?.data as Payment[];
      total.value = (response.data.value?.meta?.total || 1) as number;
    } catch (error: any) {
      throw new Error("Failed to load payments");
    } finally {
      loading.value = false;
    }
  };

  const createPayment = async (payment: Payment) => {
    loading.value = true;

    try {
      await create(payment);
      await loadPayments();
    } catch (error: any) {
      throw new Error("Failed to create payment");
    } finally {
      loading.value = false;
    }
  };

  const updatePayment = async (id: string, payment: Payment) => {
    loading.value = true;

    try {
      const { data } = await update(id, payment);
      const updatedPayment = data.value.data;

      let found = payments.value.find((p) => p.id === updatedPayment.id);
      if (!found) return;

      Object.assign(found, updatedPayment);
    } catch (error: any) {
      throw new Error("Failed to update payment");
    } finally {
      loading.value = false;
    }
  };

  const removePayment = async (id: string) => {
    loading.value = true;

    try {
      await deletePayment(id);
      payments.value = payments.value.filter((payment) => payment.id !== id);

      if (!payments.value.length) {
        const { page } = toRefs(usePaymentTableStore());
        page.value = 1;
      }
    } catch (error: any) {
      throw new Error("Failed to remove payment");
    } finally {
      loading.value = false;
    }
  };

  return {
    payments,
    total,
    statusOptions,
    loading,
    loadPayments,
    createPayment,
    removePayment,
    updatePayment,
  };
});
