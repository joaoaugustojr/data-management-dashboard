<script setup lang="ts">
import type { TableColumn } from "@nuxt/ui";
import type { Payment } from "~/types/payment";

const columns: TableColumn<Payment>[] = [
  { header: "Code #", accessorKey: "id" },
  { header: "Date", accessorKey: "date" },
  { header: "Status", accessorKey: "status" },
  { header: "Name", accessorKey: "name" },
  { header: "Email", accessorKey: "email" },
  { header: "Amount", accessorKey: "amount" },
];

const data = ref<Payment[]>([
  {
    id: "4600",
    date: "2024-03-11T15:30:00",
    status: "paid",
    name: "James Anderson",
    email: "james.anderson@example.com",
    amount: 594,
  },
  {
    id: "4599",
    date: "2024-03-11T10:10:00",
    status: "failed",
    name: "Mia White",
    email: "mia.white@example.com",
    amount: 276,
  },
  {
    id: "4598",
    date: "2024-03-11T08:50:00",
    status: "refunded",
    name: "William Brown",
    email: "william.brown@example.com",
    amount: 315,
  },
  {
    id: "4597",
    date: "2024-03-10T19:45:00",
    status: "paid",
    name: "Emma Davis",
    email: "emma.davis@example.com",
    amount: 529,
  },
  {
    id: "4596",
    date: "2024-03-10T15:55:00",
    status: "paid",
    name: "Michael Smith",
    email: "ethan.harris@example.com",
    amount: 639,
  },
]);
</script>

<template>
  <NuxtLayout name="dashboard">
    <div class="flex flex-col gap-4">
      <UCard>
        <div class="flex justify-between items-center">
          <h1>Payment History</h1>
          <Icon name="hugeicons:credit-card" size="25" />
        </div>
      </UCard>
      <UCard>
        <UTable :data="data" :columns loading-animation="carousel" class="flex-1" />
      </UCard>
    </div>
  </NuxtLayout>
</template>
