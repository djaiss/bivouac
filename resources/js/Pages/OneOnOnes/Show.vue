<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref, nextTick } from 'vue';
import { flash } from '@/methods.js';

import { ChevronUpIcon } from '@heroicons/vue/24/outline';
import Checkbox from '@/Components/Checkbox.vue';
import Avatar from '@/Components/Avatar.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  body: '',
  errors: '',
});

const addEntryShown = ref(false);
const bodyInput = ref(null);
const editedEntryId = ref(0);
const loadingState = ref(false);
const localActiveEntries = reactive(props.data.active_points_of_discussion);
const localInactiveEntries = reactive(props.data.inactive_points_of_discussion);

const showAddEntryModal = () => {
  addEntryShown.value = true;
  form.body = '';

  nextTick(() => bodyInput.value.focus());
};

const showEditModal = (entry) => {
  editedEntryId.value = entry.id;
  form.body = entry.body;
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      form.body = '';
      addEntryShown.value = false;
      loadingState.value = false;
      flash(trans('The entry has been posted'));
      localActiveEntries.push(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};

const toggle = (entry) => {
  axios.put(entry.url.toggle).then((response) => {
    if (entry.checked) {
      localActiveEntries.push(response.data.data);

      let id = localInactiveEntries.findIndex((x) => x.id === entry.id);
      localInactiveEntries.splice(id, 1);
    } else {
      localInactiveEntries.unshift(response.data.data);

      let id = localActiveEntries.findIndex((x) => x.id === entry.id);
      localActiveEntries.splice(id, 1);
    }
  });
};

const update = (entry) => {
  loadingState.value = true;

  axios.put(entry.url.update, form).then((response) => {
    loadingState.value = false;
    let id = localActiveEntries.findIndex((x) => x.id === entry.id);
    localActiveEntries[id] = response.data.data;
    editedEntryId.value = 0;
    flash(trans('Changes saved'));
  });
};

const destroy = (entry) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(entry.url.destroy).then(() => {
      flash(trans('The entry has been deleted'));
      let id = localActiveEntries.findIndex((x) => x.id === entry.id);
      localActiveEntries.splice(id, 1);
    });
  }
};
</script>

<template>
  <Head :title="$t('1:1')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <div class="flex items-center">
                  <Link :href="data.url.breadcrumb.oneonones" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('1:1s') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                    {{ $t('Detail of a 1:1') }}
                  </span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="mx-auto mb-3 max-w-lg rounded-lg bg-white shadow-md dark:bg-gray-800">
      <!-- header -->
      <div class="flex items-center justify-center rounded-t-lg border-b bg-slate-200 px-6 pb-3 pt-5">
        <div class="mr-10 flex flex-col items-center">
          <Avatar :data="data.one_on_one.user.avatar" class="mb-2 w-16 rounded-full outline outline-2 outline-white" />
          <p class="text-sm font-semibold">{{ data.one_on_one.user.name }}</p>
        </div>

        <div class="flex flex-col items-center">
          <Avatar :data="data.one_on_one.other_user.avatar" class="mb-2 w-16 rounded-full outline outline-2 outline-white" />
          <p class="text-sm font-semibold">{{ data.one_on_one.other_user.name }}</p>
        </div>
      </div>

      <!-- body -->
      <div>
        <div v-for="entry in localActiveEntries" :key="entry.id" class="flex px-6 pt-4">
          <div v-if="editedEntryId !== entry.id" class="flex">
            <!-- checkbox -->
            <Checkbox @click="toggle(entry)" :checked="entry.checked" class="checkbox-title relative mr-2" />

            <div>
              <p class="mb-2">{{ entry.body }}</p>

              <!-- information -->
              <div class="flex items-center">
                <Avatar :data="entry.user.avatar" class="mr-2 w-4" />

                <span class="mr-2 text-xs text-gray-500">{{ entry.written_at }}</span>

                <span @click="showEditModal(entry)" class="mr-2 cursor-pointer text-xs text-gray-500 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">{{ $t('Edit') }}</span>

                <span @click="destroy(entry)" class="mr-2 cursor-pointer text-xs text-gray-500 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">{{ $t('Delete') }}</span>
              </div>
            </div>
          </div>

          <!-- edit entry -->
          <form v-if="editedEntryId == entry.id" @submit.prevent="update(entry)" class="mb-4 w-full rounded border bg-gray-50">
            <div class="border-b px-6 py-4">
              <InputLabel for="role" :value="$t('What do you want to talk about?')" class="mb-2" />

              <TextArea @esc-key-pressed="editedEntryId = 0" v-model="form.body" id="description" class="block w-full" required autogrow />
            </div>

            <div class="flex items-center justify-between px-6 py-4">
              <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                {{ $t('Save') }}
              </PrimaryButton>

              <span @click="editedEntryId = 0" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                {{ $t('Cancel') }}
              </span>
            </div>
          </form>
        </div>

        <div v-if="localActiveEntries.length == 0" class="px-6 py-10 text-center">
          <p>{{ $t("Discuss your first topic. Both of you can add ideas and see what's been shared.") }}</p>
        </div>

        <!-- add point of discussion -->
        <div v-if="addEntryShown" class="px-6 py-4">
          <form @submit.prevent="submit()" class="mb-4 rounded border bg-gray-50">
            <div class="border-b px-6 py-4">
              <InputLabel for="role" :value="$t('What do you want to talk about?')" class="mb-2" />

              <TextArea @esc-key-pressed="addEntryShown = false" v-model="form.body" ref="bodyInput" id="description" class="block w-full" required autogrow />
            </div>

            <div class="flex items-center justify-between px-6 py-4">
              <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                {{ $t('Save') }}
              </PrimaryButton>

              <span @click="addEntryShown = false" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                {{ $t('Cancel') }}
              </span>
            </div>
          </form>
        </div>

        <!-- action -->
        <div v-if="!addEntryShown" class="flex justify-center pb-5 pt-4">
          <div>
            <div @click="showAddEntryModal()" class="flex cursor-pointer items-center rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm hover:border-gray-400 hover:bg-gray-200">
              <svg class="mr-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>

              <span>{{ $t('Add topic of discussion') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- older points of discussion -->
    <div class="mx-auto max-w-lg">
      <div v-for="entry in localInactiveEntries" :key="entry.id" class="flex px-6 py-1">
        <!-- checkbox -->
        <Checkbox @click="toggle(entry)" :checked="entry.checked" class="checkbox-title relative mr-2" />

        <div class="flex items-center">
          <p class="mr-3">{{ entry.body }}</p>
          <Avatar :data="entry.user.avatar" class="mr-2 w-4" />

          <span class="mr-2 text-xs text-gray-500">{{ entry.written_at }}</span>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.checkbox-title {
  top: 4px;
}
</style>
