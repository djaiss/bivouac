<script setup>
import { LinkIcon, PencilSquareIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Uploader from '@/Components/Uploader.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';
import ProjectUpdates from '@/Pages/Projects/Summary/Partials/ProjectUpdates.vue';

const props = defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});

const form = reactive({
  name: '',
  link: '',
  errors: '',
});

const localProjectResources = ref(props.data.resources);
const addResourceShown = ref(false);
const editedResourceId = ref(null);
const loadingState = ref(false);

const showAddResource = () => {
  addResourceShown.value = true;
  form.name = '';
  form.link = '';
};

const showEditResource = (projectResource) => {
  editedResourceId.value = projectResource.id;
  form.name = projectResource.name;
  form.link = projectResource.link;
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.project.url.store_resource, form)
    .then((response) => {
      localProjectResources.value.push(response.data.data);
      loadingState.value = false;
      flash(trans('The resource has been added'));
      addResourceShown.value = false;
    })
    .catch(() => {
      loadingState.value = false;
    });
};

const update = (projectResource) => {
  loadingState.value = true;

  axios.put(projectResource.url.update, form).then((response) => {
    let id = localProjectResources.value.findIndex((x) => x.id === projectResource.id);
    localProjectResources.value[id] = response.data.data;
    loadingState.value = false;
    editedResourceId.value = null;
    flash(trans('Changes saved'));
  });
};

const destroy = (projectResource) => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(projectResource.url.destroy).then(() => {
      flash(trans('The resource has been deleted'));
      let id = localProjectResources.value.findIndex((x) => x.id === projectResource.id);
      localProjectResources.value.splice(id, 1);
    });
  }
};

const onSuccess = (file) => {
  form.uuid = file.uuid;
  form.name = file.name;
  form.original_url = file.originalUrl;
  form.cdn_url = file.cdnUrl;
  form.mime_type = file.mimeType;
  form.size = file.size;

  upload();
};
</script>

<template>
  <Head :title="$t('View project')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <!-- body -->
      <div class="grid grid-cols-[2fr_1fr] gap-4">
        <!-- left -->
        <div>
          <!-- detailed description-->
          <div class="mb-6 bg-white px-4 py-4 shadow sm:rounded-lg">
            <p class="mb-4 text-sm font-bold">{{ $t('Detailed description') }}</p>

            <!-- description, if it exists -->
            <div v-if="data.project.description" v-html="data.project.description" class=""></div>

            <!-- no description -->
            <div v-else class="text-gray-400">
              {{ $t('No details yet. Consider adding some under the Settings tab.') }}
            </div>
          </div>

          <!-- resources -->
          <div class="mb-6 bg-white px-4 py-4 shadow sm:rounded-lg">
            <p class="mb-4 text-sm font-bold">{{ $t('Key resources') }}</p>
            <ul v-if="localProjectResources.length > 0" class="mb-2">
              <li
                v-for="projectResource in localProjectResources"
                :key="projectResource.id"
                class="group mb-3 flex items-center justify-between rounded-lg px-2 py-1 hover:bg-gray-100">
                <div v-if="editedResourceId !== projectResource.id" class="flex items-center">
                  <LinkIcon class="mr-2 h-4 w-4 text-blue-400" />
                  <Link
                    :href="projectResource.link"
                    class="text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ projectResource.name }}
                  </Link>
                </div>

                <div v-if="editedResourceId !== projectResource.id" class="flex">
                  <PencilSquareIcon
                    @click="showEditResource(projectResource)"
                    class="mr-2 hidden h-5 w-5 cursor-pointer rounded text-gray-400 hover:bg-gray-300 hover:text-gray-600 group-hover:block" />
                  <XMarkIcon
                    @click="destroy(projectResource)"
                    class="hidden h-5 w-5 cursor-pointer rounded text-gray-400 hover:bg-gray-300 hover:text-gray-600 group-hover:block" />
                </div>

                <!-- edit resource -->
                <form
                  @submit.prevent="update(projectResource)"
                  v-if="editedResourceId == projectResource.id"
                  class="flex justify-between">
                  <div class="mr-2 flex w-full items-center">
                    <div class="mr-3">
                      <InputLabel for="title" :value="$t('Label')" class="mb-1" />
                      <TextInput
                        id="term"
                        type="text"
                        :placeholder="$t('Label')"
                        class="w-full"
                        v-model="form.name"
                        autofocus
                        @keydown.esc="addResourceShown = false" />
                    </div>
                    <div>
                      <InputLabel for="title" :value="$t('URL/link')" class="mb-1" />
                      <TextInput
                        id="term"
                        type="text"
                        :placeholder="$t('URL/link')"
                        class="w-full"
                        v-model="form.link"
                        @keydown.esc="editedResourceId = null"
                        required />
                    </div>
                  </div>

                  <!-- actions -->
                  <div class="flex items-center pt-4">
                    <PrimaryButton class="mr-2 py-1" :loading="loadingState" :disabled="loadingState">
                      {{ $t('Save') }}
                    </PrimaryButton>

                    <span
                      @click="editedResourceId = null"
                      class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                      {{ $t('Cancel') }}
                    </span>
                  </div>
                </form>
              </li>
            </ul>

            <!-- cta to add resource-->
            <div v-if="!addResourceShown">
              <span
                @click="showAddResource"
                class="mr-2 cursor-pointer rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-1 text-sm hover:border-gray-400 hover:bg-gray-200">
                {{ $t('Add resource') }}
              </span>
            </div>

            <!-- add resource -->
            <form @submit.prevent="submit()" v-if="addResourceShown" class="flex justify-between">
              <div class="mr-2 flex w-full">
                <TextInput
                  id="term"
                  type="text"
                  :placeholder="$t('Label')"
                  class="mr-3 w-full"
                  v-model="form.name"
                  autofocus
                  @keydown.esc="addResourceShown = false" />
                <TextInput
                  id="term"
                  type="text"
                  :placeholder="$t('URL/link')"
                  class="w-full"
                  v-model="form.link"
                  @keydown.esc="addResourceShown = false"
                  required />
              </div>

              <!-- actions -->
              <div class="flex items-center">
                <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                  {{ $t('Save') }}
                </PrimaryButton>

                <span
                  @click="addResourceShown = false"
                  class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                  {{ $t('Cancel') }}
                </span>
              </div>
            </form>
          </div>

          <!-- project updates -->
          <ProjectUpdates :data="data" />
        </div>

        <!-- right -->
        <div class="bg-white shadow sm:rounded-lg"></div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
