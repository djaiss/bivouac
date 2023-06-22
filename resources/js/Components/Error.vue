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
    <div v-if="errors.length > 0" class="flex items-center border-red p-3 border rounded mb-3">
      <img src="/img/error.png" class="w-24 h-2w-24" alt="lumberjack being embarrassed" />

      <div class="mb-3">
        <p class="text-sm mb-4">{{ $t("We've found some errors. Sorry about that.") }}</p>
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
