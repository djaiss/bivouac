<script setup>
import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DestroyProjectForm from '@/Pages/Projects/Partials/DestroyProjectForm.vue';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';
import UpdateProjectForm from '@/Pages/Projects/Partials/UpdateProjectForm.vue';

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
  <Head :title="$t('Edit project')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="bg-white shadow sm:rounded-lg">
        <UpdateProjectForm :data="data" />
      </div>

      <div class="bg-white shadow sm:rounded-lg">
        <DestroyProjectForm :data="data" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
