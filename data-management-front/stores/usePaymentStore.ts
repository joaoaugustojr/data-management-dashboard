import { deletePayment, getPayments } from "~/services/payment";
import type { Payment } from "~/types/payment";

export const usePaymentStore = defineStore("payments", () => {
  const payments = ref<Payment[]>([]);
  const loading = ref(false);

  const loadPayments = async () => {
    loading.value = true;

    try {
      const response = await getPayments();
      payments.value = response.data.value as Payment[];
    } catch (error: any) {
      throw new Error("Failed to load payments", error.message);
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
      console.log(error);
      throw new Error("Failed to remove payment", error.message);
    } finally {
      loading.value = false;
    }
  };

  return { payments, loading, loadPayments, removePayment };
});
