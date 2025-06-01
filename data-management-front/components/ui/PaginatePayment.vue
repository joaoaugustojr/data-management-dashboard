<script setup lang="ts">
const { page } = toRefs(usePaymentTableStore());
const { total } = toRefs(usePaymentStore());
</script>

<template>
  <div class="flex justify-center mt-4">
    <UPagination
      v-model:page="page"
      :items-per-page="15"
      :sibling-count="1"
      show-edges
      :total
    />
  </div>
</template>

<style scoped></style>
