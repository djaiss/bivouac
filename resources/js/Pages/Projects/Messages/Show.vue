<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import Avatar from '@/Components/Avatar.vue';
import Comments from '@/Components/Comments.vue';
import Reactions from '@/Components/Reactions.vue';
import TaskList from '@/Components/TaskList.vue';
import FileList from '@/Components/FileList.vue';
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

const destroy = () => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(props.data.message.url.destroy).then((response) => {
      localStorage.success = trans('The message has been deleted');
      router.visit(response.data.data);
    });
  }
};
</script>

<template>
  <Head :title="$t('Show message')" />

  <AuthenticatedLayout>
    <div class="pb-12">
      <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
        <ProjectHeader :data="data" :menu="menu" />

        <div class="grid grid-cols-[3fr_1fr] gap-4">
          <!-- left -->
          <div>
            <!-- message -->
            <div class="relative mb-4 bg-white shadow sm:rounded-lg">
              <!-- message body -->
              <div class="border-b px-6 py-8">
                <!-- message header -->
                <h1 class="mb-3 text-center text-3xl">{{ data.message.title }}</h1>

                <!-- avatar + name -->
                <div class="mb-8 flex items-center justify-center text-sm">
                  <Avatar v-if="data.message.author.avatar" :data="data.message.author.avatar" :url="data.message.author.url" class="mr-2 w-5" />
                  <Link :href="data.message.author.url" class="mr-4 text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ data.message.author.name }}
                  </Link>
                  <p>{{ data.message.created_at }}</p>
                </div>

                <!-- message body -->
                <div v-html="data.message.body" class="prose mx-auto"></div>
              </div>

              <!-- message footer -->
              <div class="border-b bg-gray-50 p-3">
                <Reactions :reactions="data.reactions" :url="data.url" />
              </div>

              <!-- files -->
              <div class="rounded-b-lg bg-gray-50 p-3">
                <FileList :files="data.files" :url-index="data.url.files_index" :url-upload="data.url.upload" />
              </div>
            </div>

            <!-- tasks -->
            <TaskList class="mb-8" :task-list="data.task_list" :context="'message'" />

            <!-- comments -->
            <Comments :comments="data.comments" :url="data.url" />
          </div>

          <!-- right -->
          <div>
            <div class="rounded-lg shadow">
              <div class="flex items-center justify-between rounded-t-lg border-b bg-white px-6 py-4">
                <Link :href="data.message.url.edit" class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Edit') }}
                </Link>
              </div>

              <!-- markdown help -->
              <div class="prose rounded-b-lg bg-gray-50 px-6 py-4 text-sm">
                <span @click="destroy()" class="cursor-pointer font-medium text-red-700 underline hover:rounded-sm hover:bg-red-700 hover:text-white">
                  {{ $t('Delete') }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
