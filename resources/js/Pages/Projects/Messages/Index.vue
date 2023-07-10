<script setup>
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

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

        <!-- list of messages -->
        <ul v-if="data.messages.length > 0" class="w-full">
          <li
            v-for="message in data.messages"
            :key="message.id"
            class="pl-4 pr-6 py-4 hover:bg-slate-50 last:hover:rounded-b-lg flex">
            <!-- unread status -->
            <div v-if="!message.read" class="unread" v-tooltip="$t('The message is unread')"></div>

            <div class="ml-1">
              <Link
                :href="message.url.show"
                class="inline-block mb-2 text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                >{{ message.title }}</Link
              >

              <!-- author + nb of comments -->
              <div class="flex text-sm">
                <!-- user name -->
                <div class="flex items-center mr-4">
                  <Avatar :data="message.author.avatar" :url="message.author.url" class="h-4 w-4 rounded mr-2" />
                  <Link :href="message.author.url" class="text-gray-600">{{ message.author.name }}</Link>
                </div>

                <!-- comments -->
                <div class="flex items-center text-gray-600">
                  <ChatBubbleBottomCenterTextIcon class="w-3 h-3 mr-1" />
                  <span>{{ message.comments_count }}</span>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <!-- blank state -->
        <div v-else class="px-4 py-6 text-center">
          <h3 class="text-gray-900 font-medium text-lg mb-2">{{ $t("You haven't written a message yet.") }}</h3>
          <p class="mb-5 text-gray-500">{{ $t('Get started by adding your first message.') }}</p>
          <img src="/img/messages.png" class="h-60 w-60 block mx-auto" alt="" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
