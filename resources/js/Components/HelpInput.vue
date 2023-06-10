<script setup>
defineProps({
  value: {
    type: String,
    required: null,
  },
});
</script>

<template>
  <p v-if="value" class="mb-3 mt-1 text-xs">
    {{ value }}
  </p>
</template>
