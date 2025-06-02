<script setup lang="ts">
import type { TableColumn } from "@nuxt/ui";
import type { Payment } from "~/types/payment";
import type { Column, Row } from "@tanstack/vue-table";

const { onEdit, onRemove } = defineProps<{
  onEdit: (row: Row<Payment>) => void;
  onRemove: (row: Row<Payment>) => void;
}>();

const toast = useToast();

const UButton = resolveComponent("UButton");
const UBadge = resolveComponent("UBadge");
const UDropdownMenu = resolveComponent("UDropdownMenu");

const paymentsStore = usePaymentStore();

const { payments, statusOptions } = toRefs(paymentsStore);
const { clearFilters } = usePaymentTableStore();
const { columnFilters, hasFilters } = toRefs(usePaymentTableStore());

const handleSort = async (column: Column<Payment>) => {
  const direction =
    !column.getNextSortingOrder() || column.getNextSortingOrder() === "asc"
      ? "asc"
      : "desc";

  await paymentsStore.loadPayments({
    sort: `${column.id},${direction}`,
  });

  await nextTick();

  column.toggleSorting(column.getIsSorted() === "asc");
};

const getSortedHeader = (
  column: Column<Payment>,
  label: string,
  customClass?: string
) => {
  const isSorted = column.getIsSorted();
  return h(
    "div",
    { class: customClass },
    h(UButton, {
      color: "neutral",
      variant: "ghost",
      label: label,
      icon: isSorted
        ? isSorted === "asc"
          ? "i-lucide-arrow-up-narrow-wide"
          : "i-lucide-arrow-down-wide-narrow"
        : "i-lucide-arrow-up-down",
      onClick: () => handleSort(column),
    })
  );
};

const getRowItems = (row: Row<Payment>) => {
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
      onSelect() {
        onEdit(row);
      },
    },
    {
      label: "Remove",
      onSelect() {
        onRemove(row);
      },
    },
  ];
};

const columns: TableColumn<Payment>[] = [
  { header: "#", accessorKey: "id" },
  {
    header: ({ column }) => {
      return getSortedHeader(column, "Date", "-mx-2.5");
    },
    accessorKey: "created_at",
    cell: ({ row }) => {
      return useFormatDate(row.getValue("created_at"));
    },
  },
  {
    header: ({ column }) => {
      return getSortedHeader(column, "Name", "-mx-2.5");
    },
    accessorKey: "user.name",
  },
  {
    header: ({ column }) => {
      return getSortedHeader(column, "Email", "-mx-2.5");
    },
    accessorKey: "user.email",
  },
  {
    header: ({ column }) => {
      return getSortedHeader(column, "Status", "-mx-2.5");
    },
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
    header: ({ column }) => {
      return getSortedHeader(column, "Amount", "text-right");
    },
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
  <div class="flex pb-6 border-b border-accented gap-4 justify-between">
    <div class="flex gap-4">
      <USelectMenu
        v-model="columnFilters.status"
        placeholder="Filter by status"
        value-key="id"
        :items="statusOptions"
        class="w-45"
      />
      <UInput
        v-model="columnFilters.user_name"
        class="max-w-sm"
        placeholder="Filter by name"
      />
      <UInput
        v-model="columnFilters.user_email"
        class="max-w-sm"
        placeholder="Filter by email"
      />
    </div>
    <UButton v-if="hasFilters" variant="link" @click="clearFilters">
      clear filters
      <Icon name="lucide:x" class="w-4 h-4" />
    </UButton>
  </div>
  <UTable
    :data="payments"
    :columns
    :loading="paymentsStore.loading"
    loading-animation="carousel"
    class="flex-1"
    manual-sorting
  />
</template>
