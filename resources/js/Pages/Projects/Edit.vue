<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

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
  <Head :title="$t('Create a project')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-8">
      <div class="bg-white shadow px-4 py-2">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <div class="flex items-center">
                  <Link
                    :href="data.url.breadcrumb.projects"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Projects') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ data.project.name }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="px-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
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
