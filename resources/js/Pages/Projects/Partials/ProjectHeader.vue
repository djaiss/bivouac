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
    <div class="flex border-b border-gray-200 p-4">
      <div class="w-full flex items-center">
        <span v-if="!data.project.is_public" v-tooltip="$t('This project is private')"><LockClosedIcon class="w-4 h-4 mr-2 text-blue-500" /></span>
        <h1 class="font-bold text-xl">{{ data.project.name }}</h1>
      </div>
    </div>

    <!-- menu -->
    <div class="px-4">
      <div class="text-center text-gray-500 dark:text-gray-400">
        <ul class="flex flex-wrap -mb-px">
          <li class="mr-2">
            <a
              href="#"
              class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              >{{ $t('Summary') }}</a
            >
          </li>
          <li class="mr-2">
            <a href="#" class="inline-block p-3 rounded-t-lg dark:text-blue-500 dark:border-blue-500">{{
              $t('Messages')
            }}</a>
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              >{{ $t('Decisions') }}</a
            >
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              >{{ $t('Tasks') }}</a
            >
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              >{{ $t('Files') }}</a
            >
          </li>
          <li class="mr-2">
            <a
              href="#"
              class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              >{{ $t('Members') }}</a
            >
          </li>
          <li class="mr-2">
            <Link
              :href="menu.url.settings"
              class="inline-block p-3 border-b-2 rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-gray-300"
              :class="{
                'text-blue-600 border-blue-600': $page.component.startsWith('Projects/Edit'),
              }">
              {{ $t('Settings') }}</Link
            >
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
