import type { Pagination } from "~/types/pagination";
import type { Payment } from "~/types/payment";

export async function getPayments() {
  return await useApi("payments", {
    method: "GET",
  });
}

export async function deletePayment(id: string): Promise<unknown> {
  return await useApi(`payments/${id}`, {
    method: "DELETE",
  });
}
