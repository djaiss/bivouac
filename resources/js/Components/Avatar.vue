<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  data: Object,
  imgClasses: String,
  url: String,
});
</script>

<template>
  <div v-if="url">
    <Link :href="url">
      <div v-if="data.type === 'svg'" v-html="data.content" />
      <img v-else :class="imgClasses" :src="data.content" alt="avatar" />
    </Link>
  </div>
  <div v-else>
    <div v-if="data.type === 'svg'" v-html="data.content" />
    <img v-else :class="imgClasses" :src="data.content" alt="avatar" />
  </div>
</template>
