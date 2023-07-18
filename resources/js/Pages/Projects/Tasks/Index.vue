<script setup>
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline';
import { BoltIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import TaskList from '@/Components/TaskList.vue';
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
  <Head :title="$t('All messages')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="mx-auto max-w-2xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex items-center justify-between px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('All the tasks') }}
          </h2>

          <div>
            <PrimaryLinkButton :href="data.url.create">{{ $t('Add a task list') }}</PrimaryLinkButton>
          </div>
        </div>
      </div>

      <div class="mx-auto max-w-2xl">
        <!-- list of task lists -->
        <div v-for="taskList in data.task_lists" :key="taskList.id" class="mb-2">
          <TaskList v-if="taskList.tasks.length > 0 && !taskList.name" class="mb-8" :task-list="taskList" />
          <TaskList v-if="taskList.name" class="mb-8" :task-list="taskList" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
