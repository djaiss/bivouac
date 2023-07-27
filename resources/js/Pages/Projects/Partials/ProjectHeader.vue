<script setup>
import { LockClosedIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

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
  <div class="bg-white shadow sm:rounded-lg">
    <div class="border-b border-gray-200 p-4">
      <div class="mb-1 flex w-full items-center">
        <span v-if="!data.project.is_public" v-tooltip="$t('This project is private')">
          <LockClosedIcon class="mr-2 h-4 w-4 text-blue-500" />
        </span>
        <h1 class="text-xl font-bold">{{ data.project.name }}</h1>
      </div>
      <p v-if="data.project.description" class="text-sm">{{ data.project.description }}</p>
    </div>

    <!-- menu -->
    <div class="px-4">
      <div class="text-center text-gray-500 dark:text-gray-400">
        <ul class="-mb-px flex flex-wrap">
          <li class="mr-2">
            <a
              href="#"
              class="inline-block rounded-t-lg border-b-2 border-transparent p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
              {{ $t('Summary') }}
            </a>
          </li>
          <li class="mr-2">
            <Link
              :href="menu.url.messages"
              class="inline-block rounded-t-lg border-b-2 p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300"
              :class="{
                'border-blue-600 text-blue-600': $page.component.startsWith('Projects/Messages'),
                'border-transparent': !$page.component.startsWith('Projects/Messages'),
              }">
              {{ $t('Messages') }}
            </Link>
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block rounded-t-lg border-b-2 border-transparent p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
              {{ $t('Decisions') }}
            </a>
          </li>
          <li class="mr-2">
            <Link
              :href="menu.url.tasks"
              class="inline-block rounded-t-lg border-b-2 p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300"
              :class="{
                'border-blue-600 text-blue-600': $page.component.startsWith('Projects/Tasks'),
                'border-transparent': !$page.component.startsWith('Projects/Tasks'),
              }">
              {{ $t('Tasks') }}
            </Link>
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block rounded-t-lg border-b-2 border-transparent p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
              {{ $t('Files') }}
            </a>
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block rounded-t-lg border-b-2 border-transparent p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300">
              {{ $t('Members') }}
            </a>
          </li>
          <li class="mr-2">
            <Link
              :href="menu.url.settings"
              class="inline-block rounded-t-lg border-b-2 p-3 hover:border-blue-300 hover:text-blue-600 dark:hover:text-gray-300"
              :class="{
                'border-blue-600 text-blue-600': $page.component.startsWith('Projects/Edit'),
                'border-transparent': !$page.component.startsWith('Projects/Edit'),
              }">
              {{ $t('Settings') }}
            </Link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
