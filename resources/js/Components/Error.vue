<script setup>
defineProps({
  errors: {
    type: Object,
    default: null,
  },
});
</script>

<template>
  <div v-if="errors">
    <div v-if="errors.length > 0" class="border-red mb-3 flex items-center rounded border p-3">
      <img src="/img/error.png" class="h-24 w-24" alt="lumberjack being embarrassed" />

      <div class="mb-3">
        <p class="mb-4 text-sm">{{ $t("We've found some errors. Sorry about that.") }}</p>
        <p>{{ errors.message }}</p>
      </div>
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
