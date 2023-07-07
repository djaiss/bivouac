<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: '',
  },
  rows: {
    type: String,
    default: '200px',
  },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  input.value.style.height = props.rows;

  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

const resize = (event) => {
  input.value.style.height = props.rows;
  input.value.style.height = input.value.scrollHeight + 'px';

  emit('update:modelValue', event.target.value);
};
</script>

<template>
  <textarea
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    :value="modelValue"
    :placeholder="placeholder"
    @input="resize($event)"
    ref="input" />
</template>
