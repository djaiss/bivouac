<script setup>
defineProps({
  errors: {
    type: Object,
  },
});
</script>

<template>
  <div v-if="errors">
    sds
    <div v-if="errors.errors.length > 0" class="border-red mb-3 rounded border p-3">
      <p class="mb-2">{{ $t('Oops! Something went wrong.') }}</p>
      <p v-for="error in errors.errors" :key="error.id" class="mb0 mt2 text-sm">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.border-red {
  background-color: #fff5f5;
  border-color: #fc8181;
  color: #c53030;
}

.dark .border-red {
  background-color: #333131 !important;
  border-color: #4b2626 !important;
  color: #c53030 !important;
}
</style>
