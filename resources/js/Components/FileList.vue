<script setup>
import { ref } from 'vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid';
import { trans } from 'laravel-vue-i18n';
import { flash } from '@/methods.js';
import FileUpload from '@/Components/FileUpload.vue';
import axios from 'axios';

const props = defineProps({
  data: {
    type: Array,
  },
});

const localFiles = ref(props.data.files);

const download = (file) => {
  window.open(file.url.download, '_blank');
};

const refresh = () => {
  flash(trans('The file has been uploaded'));

  axios.get(props.data.url.files_index).then((response) => {
    localFiles.value = response.data.data;
  });
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
  <div>
    <FileUpload :url="data.url.upload" @file-uploaded="refresh()" />

    <ul v-if="localFiles.length > 0" class="mt-3">
      <li v-for="file in localFiles" :key="file.id" class="flex items-center justify-between border-b px-3 py-2 last:border-0 hover:bg-slate-100">
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
  </div>
</template>
