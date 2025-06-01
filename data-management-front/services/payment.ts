import type { Payment } from "~/types/payment";

export async function getPayments(params?: any) {
  return await useApi("payments", {
    method: "GET",
    params,
  });
}

export async function deletePayment(id: string): Promise<unknown> {
  return await useApi(`payments/${id}`, {
    method: "DELETE",
  });
}

export async function createPayment(data: Payment) {
  return await useApi("payments", {
    method: "POST",
    body: data,
  });
}

export async function updatePayment(id: string, data: Payment) {
  return await useApi(`payments/${id}`, {
    method: "PUT",
    body: data,
  });
}
