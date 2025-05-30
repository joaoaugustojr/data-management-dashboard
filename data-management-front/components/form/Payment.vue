<script setup lang="ts">
import * as z from "zod";
import type { FormSubmitEvent } from "@nuxt/ui";
import type { AvatarProps } from "@nuxt/ui";
import { refDebounced } from "@vueuse/core";
import type { User } from "~/types/user";
import { createPayment, updatePayment } from "~/services/payment";
import type { Payment } from "~/types/payment";

const { payment, isEdit = false } = defineProps<{
  payment?: Payment;
  isEdit?: boolean;
}>();

const toast = useToast();
const userStore = useUserStore();
const paymentsStore = usePaymentStore();

const { users } = toRefs(userStore);

userStore.loadUsers();

const usersOptions = ref<any[]>([]);

watch(users, () => {
  usersOptions.value = users.value.map((user) => ({
    label: user.name,
    id: user.id,
    avatar: { src: user.image } as AvatarProps,
  }));

  console.log(usersOptions.value);
});

const searchTerm = ref("");
const searchTermDebounced = refDebounced(searchTerm, 300);

watch(searchTermDebounced, (q) => {
  if (!q) return;
  userStore.loadUsers(q);
});

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
  user_id: z.string().nonempty("User is required"),
  status: z.string().nonempty("Status is required"),
  amount: z.number().min(1, "Must be at least 1 dollar"),
});

type Schema = z.output<typeof schema>;

const state = reactive<Partial<Schema>>({
  user_id: undefined,
  status: undefined,
  amount: undefined,
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
  if (isEdit) {
    await updatePayment(event.data as Payment);
    await paymentsStore.loadPayments();

    toast.add({
      title: "Success",
      description: "The payment has been updated.",
      color: "success",
    });

    document
      .querySelectorAll<HTMLElement>(".close-modal")
      .forEach((node) => node.click());

    return;
  }

  await createPayment(event.data as Payment);
  await paymentsStore.loadPayments();

  document.querySelectorAll<HTMLElement>(".close-modal").forEach((node) => node.click());

  toast.add({
    title: "Success",
    description: "The form has been submitted.",
    color: "success",
  });
}
</script>

<template>
  <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
    <pre>{{ payment }}</pre>
    <UFormField label="User" name="user_id">
      <USelectMenu
        v-model="state.user_id"
        icon="i-lucide-user"
        value-key="id"
        size="xl"
        placeholder="Select user"
        :items="usersOptions"
        class="w-full"
        v-model:search-term="searchTerm"
      />
    </UFormField>

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
