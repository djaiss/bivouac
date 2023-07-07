<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';

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

      <div class="max-w-2xl bg-white shadow sm:rounded-lg mx-auto">
        <!-- header -->
        <div class="px-4 py-2 flex justify-between items-center border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('All the messages') }}
          </h2>

          <div>
            <PrimaryLinkButton :href="data.url.create">{{ $t('Add a message') }}</PrimaryLinkButton>
          </div>
        </div>

        <!-- blank state -->
        <div class="px-4 py-6 text-center">
          <h3 class="text-gray-900 font-medium text-lg mb-2">{{ $t("You haven't written a message yet.") }}</h3>
          <p class="mb-5 text-gray-500">{{ $t('Get started by adding your first message.') }}</p>
          <img src="/img/messages.png" class="h-60 w-60 block mx-auto" alt="" />
        </div>
      </div>

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
