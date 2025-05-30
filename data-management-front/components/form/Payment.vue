<script setup lang="ts">
import * as z from "zod";
import type { FormSubmitEvent } from "@nuxt/ui";

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

const schema = z.object({
  status: z.string().nonempty("Status is required"),
  amount: z.number().min(1, "Must be at least 1 dollar"),
});

type Schema = z.output<typeof schema>;

const state = reactive<Partial<Schema>>({
  status: undefined,
  amount: undefined,
});

const toast = useToast();

async function onSubmit(event: FormSubmitEvent<Schema>) {
  toast.add({
    title: "Success",
    description: "The form has been submitted.",
    color: "success",
  });
  console.log(event.data);
}
</script>

<template>
  <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
    <UFormField label="Amount" name="amount">
      <UInputNumber
        v-model="state.amount"
        :format-options="{
          style: 'currency',
          currency: 'USD',
          currencySign: 'accounting',
        }"
      />
    </UFormField>

    <UFormField label="Status" name="status">
      <USelectMenu
        v-model="state.status"
        value-key="id"
        size="xl"
        :items="statusOptions"
        class="w-48"
      />
    </UFormField>

    <div class="flex items-center justify-end">
      <UButton type="submit"> Create </UButton>
    </div>
  </UForm>
</template>
