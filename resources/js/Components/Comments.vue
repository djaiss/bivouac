<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Reactions from '@/Components/Reactions.vue';
import TextArea from '@/Components/TextArea.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  comments: {
    type: Array,
  },
  url: {
    type: Array,
  },
});

const loadingState = ref(false);
const localComments = ref(props.comments);
const formattedBody = ref('');
const activeTab = ref('write');
const editedComment = ref(null);

const form = reactive({
  body: '',
  errors: '',
});

const showPreviewTab = () => {
  preview();

  activeTab.value = 'preview';
};

const showWriteTab = () => {
  activeTab.value = 'write';
  formattedBody.value = '';
};

const preview = () => {
  axios.post(props.url.preview, form).then((response) => {
    formattedBody.value = response.data.data;
  });
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.url.store, form)
    .then((response) => {
      form.body = '';
      loadingState.value = false;
      flash(trans('The comment has been posted'));
      localComments.value.push(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};

const edit = (comment) => {
  form.body = comment.body_raw;
  editedComment.value = comment;
};

const update = (comment) => {
  loadingState.value = true;

  axios
    .put(comment.url.update, form)
    .then((response) => {
      localComments.value[localComments.value.findIndex((x) => x.id === comment.id)] = response.data.data;
      flash(trans('Changes saved'));
      editedComment.value = '';
      form.body = '';
      loadingState.value = false;
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};

const destroy = (comment) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(comment.url.destroy).then(() => {
      flash(trans('The comment has been deleted'));
      let id = localComments.value.findIndex((x) => x.id === comment.id);
      localComments.value.splice(id, 1);
    });
  }
};
</script>

<template>
  <div>
    <!-- existing comments -->
    <div v-if="localComments">
      <div v-if="localComments.length > 0">
        <ol class="relative mx-auto max-w-3xl border-l border-gray-200 dark:border-gray-700">
          <li v-for="comment in localComments" :key="comment.id" class="mb-10 ml-4">
            <div class="border-bg-900 absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border bg-gray-300 dark:border-gray-900 dark:bg-gray-700"></div>

            <!-- avatar + time -->
            <div class="mb-2 flex justify-between text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
              <div class="flex items-center">
                <div class="mr-3 flex items-center">
                  <Avatar v-if="comment.author.avatar" :data="comment.author.avatar" :url="comment.author.url" class="mr-2 w-5" />
                  <Link :href="comment.author.url" class="inline text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ comment.author.name }}
                  </Link>
                </div>

                <time>{{ comment.created_at }}</time>
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
                        <span @click="edit(comment)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                          {{ $t('Edit') }}
                        </span>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button @click="destroy(comment)" :class="[active ? 'bg-violet-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                          {{ $t('Delete') }}
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>

            <!-- comment -->
            <div v-if="editedComment != comment" class="rounded-lg bg-white shadow">
              <div class="border-b px-4 py-4">
                <div v-html="comment.body" class="prose"></div>
              </div>

              <!-- message footer -->
              <div class="rounded-b-lg bg-gray-50 p-3">
                <Reactions :reactions="comment.reactions" :url="comment.url" />
              </div>
            </div>

            <!-- edit comment -->
            <div v-else class="rounded-lg bg-white px-4 py-4 shadow">
              <form @submit.prevent="update(comment)">
                <ul v-if="form.body" class="mb-5 inline-block text-sm">
                  <li @click="showWriteTab" class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2" :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
                    {{ $t('Write') }}
                  </li>
                  <li @click="showPreviewTab" class="inline cursor-pointer rounded-r-md border-b border-r border-t px-3 py-1" :class="{ 'border-l border-blue-600 text-blue-600': activeTab === 'preview' }">
                    {{ $t('Preview') }}
                  </li>
                </ul>

                <!-- write mode -->
                <div v-if="activeTab === 'write'">
                  <TextArea @esc-key-pressed="editedComment = ''" id="description" class="block w-full" required autogrow v-model="form.body" />

                  <div v-if="form.body" class="mt-4 flex justify-start">
                    <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                      {{ $t('Save') }}
                    </PrimaryButton>

                    <span @click="editedComment = ''" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                      {{ $t('Cancel') }}
                    </span>
                  </div>
                </div>

                <!-- preview mode -->
                <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
                  <div v-html="formattedBody" class="prose"></div>
                </div>
              </form>
            </div>
          </li>
        </ol>
      </div>
    </div>

    <!-- post a comment box -->
    <div v-if="!editedComment" class="rounded-lg bg-white px-4 py-4 shadow">
      <form @submit.prevent="submit()">
        <p class="mb-2 font-bold">{{ $t('Add a comment') }}</p>
        <ul v-if="form.body" class="mb-5 inline-block text-sm">
          <li @click="showWriteTab" class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2" :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
            {{ $t('Write') }}
          </li>
          <li @click="showPreviewTab" class="inline cursor-pointer rounded-r-md border-b border-r border-t px-3 py-1" :class="{ 'border-l border-blue-600 text-blue-600': activeTab === 'preview' }">
            {{ $t('Preview') }}
          </li>
        </ul>

        <!-- write mode -->
        <div v-if="activeTab === 'write'">
          <TextArea @esc-key-pressed="form.body = ''" id="description" class="block w-full" :rows="'80px'" required v-model="form.body" />

          <div v-if="form.body" class="mt-4 flex justify-start">
            <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
              {{ $t('Save') }}
            </PrimaryButton>

            <span @click="form.body = ''" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
              {{ $t('Cancel') }}
            </span>
          </div>
        </div>

        <!-- preview mode -->
        <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
          <div v-html="formattedBody" class="prose"></div>
        </div>
      </form>
    </div>
  </div>
</template>
