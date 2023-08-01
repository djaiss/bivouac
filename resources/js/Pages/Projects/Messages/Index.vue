<script setup>
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline';
import { BoltIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue';
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
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('All the messages') }}
          </h2>

          <div>
            <PrimaryLinkButton :href="data.url.create">{{ $t('Add a message') }}</PrimaryLinkButton>
          </div>
        </div>

        <!-- list of messages -->
        <ul v-if="data.messages.length > 0" class="w-full">
          <li
            v-for="message in data.messages"
            :key="message.id"
            class="flex py-4 pl-4 pr-6 hover:bg-slate-50 last:hover:rounded-b-lg">
            <!-- unread status -->
            <div v-if="!message.read" class="unread" v-tooltip="$t('The message is unread')"></div>

            <div class="ml-1">
              <Link
                :href="message.url.show"
                class="mb-2 inline-block text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                {{ message.title }}
              </Link>

              <!-- author + nb of comments -->
              <div class="flex text-sm">
                <!-- user name -->
                <div class="group mr-4 flex items-center">
                  <Avatar :data="message.author.avatar" :url="message.author.url" class="mr-2 h-4 w-4 rounded" />
                  <Link :href="message.author.url" class="text-gray-600 group-hover:underline group-hover:text-blue-700">
                    {{ message.author.name }}
                  </Link>
                </div>

                <!-- comments -->
                <div v-tooltip="$t('number of comments')" class="mr-4 flex items-center text-gray-600">
                  <ChatBubbleBottomCenterTextIcon class="mr-1 h-3 w-3" />
                  <span>{{ message.comments_count }}</span>
                </div>

                <!-- open tasks -->
                <div v-tooltip="$t('number of open tasks')" class="flex items-center text-gray-600">
                  <BoltIcon class="mr-1 h-3 w-3" />
                  <span>{{ message.tasks_count }}</span>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <!-- blank state -->
        <div v-else class="px-4 py-6 text-center">
          <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t("You haven't written a message yet.") }}</h3>
          <p class="mb-5 text-gray-500">{{ $t('Get started by adding your first message.') }}</p>
          <img src="/img/messages.png" class="mx-auto block h-60 w-60" alt="" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
