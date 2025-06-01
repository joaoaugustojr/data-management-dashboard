<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import type { Payment } from "~/types/payment";

// ADD DIALOG WHEM REMOVE DATA
// PERSIST ORDER
// TODO: BRING REDIS

const toast = useToast();

const paymentsStore = usePaymentStore();
paymentsStore.loadPayments();

const payment = ref<Payment>();
const showEditModal = ref(false);
const showRemoveModal = ref(false);

const handleEdit = (row: Row<Payment>) => {
  payment.value = row.original;
  payment.value.amount = parseFloat(row.getValue("amount"));
  showEditModal.value = true;
};

const handleRemove = (row: Row<Payment>) => {
  payment.value = row.original;
  showRemoveModal.value = true;
};

const removePayment = async () => {
  if (!payment.value) return;

  paymentsStore.removePayment(payment.value.id);
  showRemoveModal.value = false;

  toast.add({
    title: `Payment removed!`,
    color: "success",
    icon: "i-lucide-circle-check",
  });
};
</script>

<template>
  <NuxtLayout name="dashboard">
    <div class="flex flex-col gap-4">
      <UCard>
        <div class="flex justify-between items-center">
          <div class="flex gap-2">
            <Icon name="hugeicons:credit-card" size="25" />
            <h1>Payment History</h1>
          </div>
          <UModal
            title="Create Payment"
            :close="{
              class: 'close-modal',
            }"
          >
            <UButton @click.stop="">Create Payment</UButton>
            <template #body>
              <FormPayment />
            </template>
          </UModal>
        </div>
      </UCard>
      <UCard>
        <TablePayment @edit="handleEdit" @remove="handleRemove" />
        <UiPaginatePayment />
      </UCard>
    </div>
    <UModal
      v-model:open="showEditModal"
      title="Edit Payment"
      :close="{
        class: 'close-modal',
      }"
    >
      <template #body>
        <FormPayment is-edit :payment />
      </template>
    </UModal>
    <UModal
      v-model:open="showRemoveModal"
      title="Remove Payment"
      :close="{
        class: 'close-modal',
      }"
    >
      <template #body>
        <p>Are you sure you want to remove this payment?</p>
        <small class="opacity-70">This action cannot be undone.</small>

        <div class="flex justify-end gap-2">
          <UButton @click="showRemoveModal = false"> Cancel </UButton>
          <UButton variant="solid" color="error" @click="removePayment"> Remove </UButton>
        </div>
      </template>
    </UModal>
  </NuxtLayout>
</template>
