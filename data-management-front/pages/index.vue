<script setup lang="ts">
import type { Row } from "@tanstack/vue-table";
import type { Payment } from "~/types/payment";

const paymentsStore = usePaymentStore();
paymentsStore.loadPayments();

const payment = ref<Payment>();
const showEditModal = ref(false);

const handleEdit = (row: Row<Payment>) => {
  payment.value = row.original;
  payment.value.amount = parseFloat(row.getValue("amount"));
  showEditModal.value = true;
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
        <TablePayment @edit="handleEdit" />
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
  </NuxtLayout>
</template>
