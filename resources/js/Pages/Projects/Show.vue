<script setup>
import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Create a project')" />

  <AuthenticatedLayout>
    <div class="mt-8 px-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <ProjectHeader :data="data" :menu="menu" />

      <!-- body -->
      <div class="grid grid-cols-[2fr_1fr] gap-4">
        <!-- left -->
        <div class="bg-white shadow sm:rounded-lg"></div>

        <!-- right -->
        <div class="bg-white shadow sm:rounded-lg"></div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
