import {
  deletePayment,
  getPayments,
  updatePayment as update,
} from "~/services/payment";
import type { Payment } from "~/types/payment";

export const usePaymentStore = defineStore("payments", () => {
  const payments = ref<Payment[]>([]);
  const loading = ref(false);

  const loadPayments = async (params?: any) => {
    loading.value = true;

    try {
      const response = await getPayments(params);
      payments.value = response.data.value?.data as Payment[];
    } catch (error: any) {
      throw new Error("Failed to load payments", error);
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
      throw new Error("Failed to update payment", error);
    } finally {
      loading.value = false;
    }
  };

  const removePayment = async (id: string) => {
    loading.value = true;

    try {
      await deletePayment(id);
      payments.value = payments.value.filter((payment) => payment.id !== id);
    } catch (error: any) {
      throw new Error("Failed to remove payment", error);
    } finally {
      loading.value = false;
    }
  };

  return { payments, loading, loadPayments, removePayment, updatePayment };
});
