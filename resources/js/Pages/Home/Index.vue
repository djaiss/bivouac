<script setup>
import { Head, Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Avatar from '@/Components/Avatar.vue';
import Checkbox from '@/Components/Checkbox.vue';

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
          <div class="bg-white shadow sm:rounded-lg mb-6 px-6 py-4">
            <h2>{{ $t('Assigned tasks') }}</h2>
            <div v-for="task in props.data.tasks" :key="task.id" class="mb-3 last:mb-0">
              <div class="mb-2 relative flex w-full items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border hover:border-gray-200 hover:bg-white">
                <!-- title and checkbox -->
                <div class="flex items-center">
                  <Checkbox @click="toggleTask(task)" :checked="task.is_completed" :name="'completed' + task.id" class="mr-2" />
                  <Link :href="task.url.show" class="hover:underline">{{ task.title }}</Link>
                </div>

                <!-- options and assignees -->
                <div class="flex items-center">
                  <!-- assignees -->
                  <div v-if="task.assignees.length > 0">
                    <div class="flex -space-x-3">
                      <Avatar v-for="assignee in task.assignees" :key="assignee.id" v-tooltip="assignee.name" :data="assignee.avatar" :url="assignee.url" class="h-6 w-6 cursor-pointer rounded-full border-2 border-white dark:border-gray-800" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="text-xs text-gray-700 flex items-center ml-7">
                <svg class="w-4 h-4 text-gray-300 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>

                <Link :href="task.project.url.show" class="hover:underline">{{ task.project.name }}</Link>
              </div>
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
