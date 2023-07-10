<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import Avatar from '@/Components/Avatar.vue';
import Comments from '@/Components/Comments.vue';
import Reactions from '@/Components/Reactions.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
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
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white shadow px-4 py-2">
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
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link
                  :href="data.url.breadcrumb.project"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ data.project.name }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link
                  :href="data.url.breadcrumb.messages"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">
                  {{ $t('Messages') }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ data.message.title }}</span>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-7xl overflow-hidden">
        <div class="grid grid-cols-[3fr_1fr] gap-4 px-4">
          <!-- left -->
          <div>
            <!-- message -->
            <div class="bg-white shadow sm:rounded-lg relative mb-8">
              <!-- message body -->
              <div class="px-6 py-8 border-b">
                <!-- message header -->
                <h1 class="text-center text-3xl mb-3">{{ data.message.title }}</h1>

                <!-- avatar + name -->
                <div class="flex items-center justify-center mb-8 text-sm">
                  <Avatar
                    v-if="data.message.author.avatar"
                    :data="data.message.author.avatar"
                    :url="data.message.author.url"
                    class="w-5 mr-2" />
                  <Link
                    :href="data.message.author.url"
                    class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline mr-4"
                    >{{ data.message.author.name }}</Link
                  >
                  <p>{{ data.message.created_at }}</p>
                </div>

                <!-- message body -->
                <div v-html="data.message.body" class="prose mx-auto"></div>
              </div>

              <!-- message footer -->
              <div class="p-3 bg-gray-50 rounded-b-lg">
                <Reactions :reactions="data.reactions" :url="data.url" />
              </div>
            </div>

            <!-- comments -->
            <Comments :comments="data.comments" :url="data.url" />
          </div>

          <!-- right -->
          <div>
            <div class="rounded-lg shadow">
              <div class="bg-white border-b flex items-center justify-between px-6 py-4 rounded-t-lg">
                <Link
                  :href="data.message.url.edit"
                  class="text-sm font-medium text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">
                  {{ $t('Edit') }}</Link
                >
              </div>

              <!-- markdown help -->
              <div class="bg-gray-50 rounded-b-lg px-6 py-4 prose text-sm">
                <span
                  @click="destroy()"
                  class="font-medium text-red-700 cursor-pointer hover:bg-red-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Delete') }}</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
