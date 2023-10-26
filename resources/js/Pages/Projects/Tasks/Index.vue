<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
import TaskList from '@/Components/TaskList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

const props = defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});

const localLists = ref(props.data.task_lists);

const removeList = (taskList) => {
  let id = localLists.value.findIndex((x) => x.id === taskList.id);
  localLists.value.splice(id, 1);
};
</script>

<template>
  <Head :title="$t('All messages')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="mx-auto max-w-4xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex items-center justify-between px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('All the tasks') }}
          </h2>

          <div>
            <PrimaryLinkButton :href="data.url.create">{{ $t('Add a task list') }}</PrimaryLinkButton>
          </div>
        </div>

        <!-- blank state -->
        <div v-if="data.task_lists.length === 0" class="border-t px-4 py-6 text-center">
          <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t('No task list has been created yet.') }}</h3>
          <p class="mb-5 text-gray-500">
            {{ $t('Create a task list to begin tracking your tasks and assign them to the appropriate people.') }}
          </p>
          <img src="/img/tasks.png" class="mx-auto block h-60 w-60" alt="" />
        </div>
      </div>

      <div class="mx-auto max-w-4xl">
        <!-- list of task lists -->
        <div v-for="taskList in localLists" :key="taskList.id" class="mb-2">
          <TaskList @destroyed="removeList(taskList)" v-if="taskList.tasks.length > 0 && !taskList.name" class="mb-8" :task-list="taskList" />
          <TaskList @destroyed="removeList(taskList)" v-if="taskList.name" class="mb-8" :task-list="taskList" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
