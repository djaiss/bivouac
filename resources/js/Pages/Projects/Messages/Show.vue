<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import Avatar from '@/Components/Avatar.vue';
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
            <div class="bg-white shadow sm:rounded-lg px-6 py-4 relative">
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
              <div v-html="data.message.body" class="prose"></div>
            </div>
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
                <p>{{ $t('We support Markdown, which lets you add formatting to your message.') }}</p>
                <p>{{ $t('Quick reference:') }}</p>
                <ul>
                  <li><code># H1</code></li>
                  <li><code>## H2</code></li>
                  <li>
                    <code>**{{ $t('bold text') }}**</code>
                  </li>
                  <li>
                    <code>*{{ $t('italicized text') }}*</code>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
