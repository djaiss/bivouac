<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { trans } from 'laravel-vue-i18n';
import { flash } from '@/methods.js';

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

const localFiles = ref(props.data.files);

const download = (file) => {
  window.open(file.url.download, '_blank');
};

const destroy = (file) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(file.url.destroy).then(() => {
      flash(trans('The file has been deleted'));
      let id = localFiles.value.findIndex((x) => x.id === file.id);
      localFiles.value.splice(id, 1);
    });
  }
};
</script>

<template>
  <Head :title="$t('All files')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="mx-auto max-w-2xl bg-white shadow sm:rounded-lg">
        <!-- header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2">
          <h2 class="text-lg font-medium text-gray-900">
            {{ $t('All the files') }}
          </h2>
        </div>

        <!-- list of files -->
        <ul v-if="localFiles.length > 0" class="w-full">
          <li v-for="file in localFiles" :key="file.id" class="flex items-center justify-between py-4 pl-4 pr-6 hover:bg-slate-50 last:hover:rounded-b-lg">
            <div class="flex items-center">
              <!-- icon -->
              <div class="mr-2">
                <div class="rounded-lg bg-cyan-100 px-2 py-2 text-xs">{{ file.extension }}</div>
              </div>

              <!-- name + date + file size -->
              <div>
                <p class="text-sm font-bold">{{ file.name }}</p>
                <p class="text-xs text-gray-600">{{ file.uploaded_at }} - {{ file.size }}</p>
              </div>
            </div>

            <!-- actions -->
            <Menu as="div" class="relative text-left">
              <MenuButton class="">
                <EllipsisHorizontalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
              </MenuButton>

              <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <span @click="download(file)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                        {{ $t('Download') }}
                      </span>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <button @click="destroy(file)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                        {{ $t('Delete') }}
                      </button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </li>
        </ul>

        <!-- blank state -->
        <div v-else class="px-4 py-6 text-center">
          <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $t('There are no files yet in this project.') }}</h3>
          <p class="mb-5 text-gray-500">
            {{ $t('Files can be added on messages and tasks.') }}
          </p>
          <img src="/img/files.png" class="mx-auto block h-60 w-60" alt="" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
