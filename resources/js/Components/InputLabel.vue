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
  <label class="block text-sm font-medium text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>

    <span v-if="!required" class="optional-badge ml-2 rounded px-[3px] py-px text-xs">{{ $t('optional') }}</span>
  </label>
</template>

<style lang="scss" scoped>
.optional-badge {
  color: #283e59;
  background-color: #edf2f9;
}
</style>
