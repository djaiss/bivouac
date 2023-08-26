<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  body: '',
  errors: '',
});

const localProjectUpdates = ref(props.data.updates);
const editedUpdateId = ref(null);
const loadingState = ref(false);
const addUpdateShown = ref(false);
const activeTab = ref('write');
const formattedBody = ref('');

const showPreviewTab = () => {
  preview();

  activeTab.value = 'preview';
};

const showWriteTab = () => {
  activeTab.value = 'write';
  formattedBody.value = '';
};

const preview = () => {
  axios.post(props.data.project.url.preview, form).then((response) => {
    formattedBody.value = response.data.data;
  });
};

const showAddModal = () => {
  addUpdateShown.value = true;
  form.body = '';
};

const showEditUpdate = (projectUpdate) => {
  editedUpdateId.value = projectUpdate.id;
  form.body = projectUpdate.content_raw;
};

const store = () => {
  loadingState.value = true;

  axios.post(props.data.project.url.store_update, form).then((response) => {
    localProjectUpdates.value.unshift(response.data.data);
    flash(trans('The update has been posted'));
    addUpdateShown.value = false;
    loadingState.value = false;
  });
};

const update = (projectUpdate) => {
  loadingState.value = true;

  axios.put(projectUpdate.url.update, form).then((response) => {
    let id = localProjectUpdates.value.findIndex((x) => x.id === projectUpdate.id);
    localProjectUpdates.value[id] = response.data.data;
    flash(trans('Changes saved'));
    editedUpdateId.value = 0;
    loadingState.value = false;
  });
};

const destroy = (projectUpdate) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(projectUpdate.url.destroy).then(() => {
      flash(trans('The update has been deleted'));
      let id = localProjectUpdates.value.findIndex((x) => x.id === projectUpdate.id);
      localProjectUpdates.value.splice(id, 1);
    });
  }
};
</script>

<template>
  <div>
    <div class="mb-4 bg-white px-4 py-4 shadow sm:rounded-lg">
      <p class="mb-4 text-sm font-bold">{{ $t('Project updates') }}</p>

      <!-- cta -->
      <div v-if="!addUpdateShown">
        <p class="mb-4">
          {{
            $t(
              "Are there any updates to share about the project? Let project members and followers know what's happening.",
            )
          }}
        </p>

        <PrimaryButton class="mr-2" @click="showAddModal()">
          {{ $t('Write update') }}
        </PrimaryButton>
      </div>

      <!-- add an update -->
      <form @submit.prevent="store()" v-else>
        <div class="mb-4">
          <ul class="mb-5 inline-block text-sm">
            <li
              @click="showWriteTab"
              class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2"
              :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
              {{ $t('Post') }}
            </li>
            <li
              @click="showPreviewTab"
              class="inline cursor-pointer rounded-r-md border-b border-r border-t px-3 py-1"
              :class="{ 'border-l border-blue-600 text-blue-600': activeTab === 'preview' }">
              {{ $t('Preview') }}
            </li>
          </ul>

          <!-- write mode -->
          <div v-if="activeTab === 'write'">
            <TextArea
              id="description"
              class="block w-full"
              required
              v-model="form.body"
              @esc-key-pressed="addUpdateShown = false" />
          </div>

          <!-- preview mode -->
          <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
            <div v-html="formattedBody" class="prose"></div>
          </div>
        </div>

        <div class="mt-4 flex justify-start">
          <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
            {{ $t('Save') }}
          </PrimaryButton>

          <span
            @click="addUpdateShown = false"
            class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
            {{ $t('Cancel') }}
          </span>
        </div>
      </form>
    </div>

    <!-- list of updates -->
    <div v-for="projectUpdate in localProjectUpdates" :key="projectUpdate.id">
      <div class="mb-4 bg-white shadow sm:rounded-lg">
        <!-- avatar and date -->
        <div class="flex items-center justify-between border-b px-4 py-4">
          <div class="group flex items-center">
            <Avatar
              v-tooltip="projectUpdate.author.name"
              :data="projectUpdate.author.avatar"
              :url="projectUpdate.author.url"
              class="mr-4 h-10 w-10 rounded" />

            <div>
              <Link
                :href="projectUpdate.author.url"
                class="text-sm text-gray-600 group-hover:text-blue-700 group-hover:underline">
                {{ projectUpdate.author.name }}
              </Link>
              <p class="text-sm text-gray-500">{{ projectUpdate.created_at }}</p>
            </div>
          </div>

          <!-- actions -->
          <Menu as="div" class="relative text-left">
            <MenuButton class="">
              <EllipsisVerticalIcon class="h-5 w-5 cursor-pointer hover:text-gray-500" />
            </MenuButton>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0">
              <MenuItems
                class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <span
                      @click="showEditUpdate(projectUpdate)"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]">
                      {{ $t('Edit') }}
                    </span>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <button
                      @click="destroy(projectUpdate)"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]">
                      {{ $t('Delete') }}
                    </button>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </div>

        <!-- content -->
        <div v-if="editedUpdateId !== projectUpdate.id" v-html="projectUpdate.content" class="prose px-4 py-4"></div>

        <!-- edit form -->
        <div v-else class="px-4 py-4">
          <form @submit.prevent="update(projectUpdate)">
            <div class="mb-4">
              <ul class="mb-5 inline-block text-sm">
                <li
                  @click="showWriteTab"
                  class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2"
                  :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
                  {{ $t('Post') }}
                </li>
                <li
                  @click="showPreviewTab"
                  class="inline cursor-pointer rounded-r-md border-b border-r border-t px-3 py-1"
                  :class="{ 'border-l border-blue-600 text-blue-600': activeTab === 'preview' }">
                  {{ $t('Preview') }}
                </li>
              </ul>

              <!-- write mode -->
              <div v-if="activeTab === 'write'">
                <TextArea
                  id="description"
                  class="block w-full"
                  required
                  v-model="form.body"
                  @esc-key-pressed="editedUpdateId = 0" />
              </div>

              <!-- preview mode -->
              <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
                <div v-html="formattedBody" class="prose"></div>
              </div>
            </div>

            <div class="mt-4 flex justify-start">
              <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                {{ $t('Save') }}
              </PrimaryButton>

              <span
                @click="editedUpdateId = 0"
                class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                {{ $t('Cancel') }}
              </span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
