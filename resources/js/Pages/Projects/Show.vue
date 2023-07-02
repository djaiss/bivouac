<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  data: {
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
      <div class="bg-white shadow sm:rounded-lg">
        <div class="flex border-b border-gray-200 p-4">
          <div class="w-full">
            <h1 class="font-bold text-xl">{{ data.project.name }}</h1>
          </div>
        </div>

        <!-- menu -->
        <div class="px-4">
          <div class="text-center text-gray-500 dark:text-gray-400">
            <ul class="flex flex-wrap -mb-px">
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Summary') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                  >{{ $t('Messages') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                  >{{ $t('Decisions') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Tasks') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Files') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Members') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Settings') }}</a
                >
              </li>
            </ul>
          </div>
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
