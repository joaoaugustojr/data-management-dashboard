<script setup lang="ts">
import { h, resolveComponent } from "vue";
import type { TableColumn } from "@nuxt/ui";
import type { Payment } from "~/types/payment";
import type { Row } from "@tanstack/vue-table";

const UBadge = resolveComponent("UBadge");
const UButton = resolveComponent("UButton");
const UDropdownMenu = resolveComponent("UDropdownMenu");

const paymentsStore = usePaymentStore();
const { payments } = toRefs(paymentsStore);

paymentsStore.loadPayments();

const toast = useToast();

function getRowItems(row: Row<Payment>) {
  return [
    {
      type: "label",
      label: "Actions",
    },
    {
      label: "Copy payment ID",
      onSelect() {
        navigator.clipboard.writeText(row.original.id);

        toast.add({
          title: "Payment ID copied to clipboard!",
          color: "info",
          icon: "i-lucide-circle-check",
        });
      },
    },
    {
      type: "separator",
    },
    {
      label: "Edit",
    },
    {
      label: "Remove",
      onSelect() {
        paymentsStore.removePayment(row.original.id);
        toast.add({
          title: `Payment removed!`,
          color: "success",
          icon: "i-lucide-circle-check",
        });
      },
    },
  ];
}

const columns: TableColumn<Payment>[] = [
  { header: "#", accessorKey: "id" },
  {
    header: "Date",
    accessorKey: "created_at",
    cell: ({ row }) => {
      return useFormatDate(row.getValue("created_at"));
    },
  },
  { header: "Name", accessorKey: "user.name" },
  { header: "Email", accessorKey: "user.email" },
  {
    header: "Status",
    accessorKey: "status",
    cell: ({ row }) => {
      const color = {
        paid: "success" as const,
        pending: "info" as const,
        failed: "error" as const,
        refunded: "neutral" as const,
      }[row.getValue("status") as string];

      return h(UBadge, { class: "capitalize", variant: "subtle", color }, () =>
        row.getValue("status")
      );
    },
  },
  {
    header: () => h("div", { class: "text-right" }, "Amount"),
    accessorKey: "amount",
    cell: ({ row }) => {
      return h(
        "div",
        { class: "text-right font-medium" },
        useFormatCurrency(Number.parseFloat(row.getValue("amount")))
      );
    },
  },
  {
    id: "actions",
    cell: ({ row }) => {
      return h(
        "div",
        { class: "text-right" },
        h(
          UDropdownMenu,
          {
            content: {
              align: "end",
            },
            items: getRowItems(row),
            "aria-label": "Actions dropdown",
          },
          () =>
            h(UButton, {
              icon: "i-lucide-ellipsis-vertical",
              color: "neutral",
              variant: "ghost",
              class: "ml-auto",
              "aria-label": "Actions dropdown",
            })
        )
      );
    },
  },
];
</script>

<template>
  <NuxtLayout name="dashboard">
    <div class="flex flex-col gap-4">
      <UCard>
        <div class="flex justify-between items-center">
          <div class="flex gap-2">
            <h1>Payment History</h1>
            <Icon name="hugeicons:credit-card" size="25" />
          </div>
          <UModal title="Create Payment">
            <UButton>Create Payment</UButton>
            <template #body>
              <FormPayment />
            </template>
          </UModal>
        </div>
      </UCard>
      <UCard>
        <UTable
          :data="payments"
          :columns
          :loading="paymentsStore.loading"
          loading-animation="carousel"
          class="flex-1"
        />
      </UCard>
    </div>
  </NuxtLayout>
</template>
