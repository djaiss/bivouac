<script setup>
defineProps({
  value: {
    type: String,
  },
  required: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
  <label class="block font-medium text-sm text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>

    <span v-if="!required" class="optional-badge rounded px-[3px] py-px text-xs ml-2"> optional </span>
  </label>
</template>

<style lang="scss" scoped>
.optional-badge {
  color: #283e59;
  background-color: #edf2f9;
}
</style>
