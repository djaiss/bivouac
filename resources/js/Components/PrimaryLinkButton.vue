<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <Link class="flex rounded-md bg-indigo-500 px-3 py-1 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700">
    <slot />
  </Link>
</template>
