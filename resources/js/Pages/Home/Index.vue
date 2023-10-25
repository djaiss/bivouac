<script setup>
import { LockClosedIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Dashboard')" />

  <AuthenticatedLayout>
    <div class="pb-12 mt-10">
      <div class="mx-auto flex max-w-3xl sm:px-6 lg:px-8">
        <div class="w-full">
          <h1 class="text-2xl mb-6">Good day, Regis!</h1>

          <!-- assigned tasks -->
          <div class="bg-white shadow sm:rounded-lg mb-6">
            <h2>{{ $t('Assigned tasks') }}</h2>
            <div v-for="task in props.data.tasks" :key="task.id">
              {{ task.title }}
            </div>
          </div>

          <!-- visits -->
          <div class="bg-white shadow sm:rounded-lg">
            <h2>{{ $t('Latest visits') }}</h2>
            <div v-for="visit in props.data.latest_visits" :key="visit.id">
              {{ visit.project.name }}
            </div>
          </div>
        </div>
      </div>
  </div>
</AuthenticatedLayout></template>
