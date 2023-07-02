<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});
</script>

<template>
  <Head :title="$t('Projects')" />

  <AuthenticatedLayout>
    <div class="px-12 pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mb-6">
      <div class="bg-white shadow sm:rounded-lg">
        <!-- menu -->
        <div class="px-4">
          <div class="font-medium text-center text-gray-500 dark:text-gray-400 flex items-center justify-between">
            <ul class="flex flex-wrap -mb-px">
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('Your projects') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500"
                  >{{ $t('Starred projects') }}</a
                >
              </li>
              <li class="mr-2">
                <a
                  href="#"
                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
                  >{{ $t('All projects') }}</a
                >
              </li>
            </ul>

            <div>
              <PrimaryLinkButton :href="data.url.create">{{ $t('Create project') }}</PrimaryLinkButton>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="w-full">
          <div class="bg-white shadow sm:rounded-lg">
            <!-- list of projects -->
            <div class="flex">
              <ul class="w-full">
                <li
                  v-for="project in props.data.projects"
                  :key="project.id"
                  class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 first:hover:rounded-t-lg last:hover:rounded-b-lg">
                  <!-- project information -->
                  <div class="flex items-center">
                    <div class="flex flex-col mr-6">
                      <!-- project name -->
                      <div>
                        <Link
                          :href="project.url.show"
                          class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                          >{{ project.name }}</Link
                        >
                      </div>

                      <!-- description -->
                      <p v-if="project.description" class="text-sm text-gray-600">{{ project.description }}</p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
