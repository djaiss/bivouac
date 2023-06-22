<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <Link
    class="rounded-md bg-indigo-500 px-3 py-1.5 font-semibold text-white shadow-sm ring-1 ring-inset ring-indigo-600 hover:bg-indigo-700 flex">
    <slot />
  </Link>
</template>
