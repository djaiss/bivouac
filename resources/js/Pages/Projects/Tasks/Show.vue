<script setup>
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Checkbox from '@/Components/Checkbox.vue';
import Comments from '@/Components/Comments.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Reactions from '@/Components/Reactions.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { flash } from '@/methods.js';
import ProjectHeader from '@/Pages/Projects/Partials/ProjectHeader.vue';

const props = defineProps({
  data: {
    type: Array,
  },
  menu: {
    type: Array,
  },
});

const form = reactive({
  title: '',
  body: '',
  is_completed: false,
  errors: '',
});

const loadingState = ref(false);
const task = ref(props.data.task);
const title = ref(props.data.task.title);
const description = ref(props.data.task.description_raw);
const editTitleShown = ref(false);
const editDescriptionShown = ref(false);
const formattedDescription = ref(props.data.task.description);
const activeTab = ref('write');
const bodyInput = ref(null);

const editTitle = () => {
  form.title = title.value;
  form.body = description.value;
  form.is_completed = task.value.is_completed;
  editTitleShown.value = true;
};

const editDescription = () => {
  form.title = title.value;
  form.body = description.value;
  form.is_completed = task.value.is_completed;
  editDescriptionShown.value = true;
};

const showPreviewTab = () => {
  preview();

  activeTab.value = 'preview';
};

const showWriteTab = () => {
  activeTab.value = 'write';
  formattedDescription.value = '';
};

const preview = () => {
  axios.post(task.value.url.preview, form).then((response) => {
    formattedDescription.value = response.data.data;
  });
};

const update = () => {
  loadingState.value = true;

  axios
    .put(task.value.url.update, form)
    .then((response) => {
      loadingState.value = false;
      title.value = form.title;
      task.value = response.data.data.task;
      description.value = response.data.data.task.description_raw;
      formattedDescription.value = response.data.data.task.description;
      flash(trans('Changes saved'));
      editTitleShown.value = false;
      editDescriptionShown.value = false;
    })
    .catch(() => {
      loadingState.value = false;
    });
};

const destroy = () => {
  if (confirm(trans('Are you sure? This action cannot be undone.'))) {
    axios.delete(task.value.url.destroy).then((response) => {
      localStorage.success = trans('Changes saved');
      router.visit(response.data.data);
    });
  }
};

const toggleTask = () => {
  form.title = title.value;
  form.body = description.value;
  form.is_completed = !task.is_completed;

  update();
};
</script>

<template>
  <Head :title="$t('Task detail')" />

  <AuthenticatedLayout>
    <div class="mx-auto mb-6 mt-8 max-w-7xl space-y-6 px-12 sm:px-6 lg:px-8">
      <ProjectHeader :data="data" :menu="menu" />

      <div class="grid grid-cols-[3fr_1fr] gap-4 px-4">
        <!-- left -->
        <div>
          <!-- task -->
          <div class="relative mb-4 bg-white shadow sm:rounded-lg">
            <!-- body of the task  -->
            <div class="border-b px-6 py-6">
              <!-- task header -->
              <div
                v-if="!editTitleShown"
                class="flex cursor-pointer items-start rounded-lg border border-transparent px-2 py-2 hover:border-gray-200">
                <Checkbox
                  @click="toggleTask()"
                  :checked="task.is_completed"
                  :name="'completed' + task.id"
                  class="checkbox-title relative mr-3 h-6 w-6" />
                <p class="text-xl" @click="editTitle()">{{ title }}</p>
              </div>

              <!-- edit title box -->
              <div v-if="editTitleShown">
                <form @submit.prevent="update()" class="flex items-center justify-between">
                  <TextInput
                    id="term"
                    type="text"
                    :placeholder="$t('Enter a title')"
                    class="mr-3 w-full"
                    v-model="form.title"
                    autofocus
                    @keydown.esc="editTitleShown = false"
                    required />

                  <!-- actions -->
                  <div class="flex items-center">
                    <PrimaryButton class="mr-2" :loading="loadingState" :disabled="loadingState">
                      {{ $t('Save') }}
                    </PrimaryButton>

                    <span
                      @click="editTitleShown = false"
                      class="flex cursor-pointer rounded-md bg-gray-100 px-3 py-1.5 font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 hover:bg-gray-200">
                      {{ $t('Cancel') }}
                    </span>
                  </div>
                </form>
              </div>

              <!-- description -->
              <div v-if="description && !editDescriptionShown" @click="editDescription()" v-html="formattedDescription" class="prose mx-auto"></div>
              <div
                v-if="!description && !editDescriptionShown"
                @click="editDescription()"
                class="mt-4 cursor-pointer text-sm text-gray-600 group-hover:underline">
                {{ $t('+ add description') }}
              </div>

              <!-- edit description -->
              <form v-if="editDescriptionShown" @submit.prevent="update()" class="mt-6">
                <ul v-if="form.body" class="mb-5 inline-block text-sm">
                  <li
                    @click="showWriteTab"
                    class="inline cursor-pointer rounded-l-md border px-3 py-1 pr-2"
                    :class="{ 'border-blue-600 text-blue-600': activeTab === 'write' }">
                    {{ $t('Write') }}
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
                    @esc-key-pressed="editDescriptionShown = false"
                    id="description"
                    ref="bodyInput"
                    class="block w-full"
                    required
                    autogrow
                    v-model="form.body" />

                  <div v-if="form.body" class="mt-4 flex justify-start">
                    <PrimaryButton class="" :loading="loadingState" :disabled="loadingState">
                      {{ $t('Save') }}
                    </PrimaryButton>

                    <span
                      @click="editDescriptionShown = false"
                      class="ml-2 inline-flex cursor-pointer items-center rounded-md border border-transparent bg-gray-100 px-3 py-1 text-sm font-medium leading-4 text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                      {{ $t('Cancel') }}
                    </span>
                  </div>
                </div>

                <!-- preview mode -->
                <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
                  <div v-html="formattedDescription" class="prose"></div>
                </div>
              </form>
            </div>

            <!-- message footer -->
            <div class="rounded-b-lg bg-gray-50 p-3">
              <Reactions :reactions="task.reactions" :url="task.url" />
            </div>
          </div>

          <!-- comments -->
          <Comments :comments="task.comments" :url="task.url" />
        </div>

        <!-- right -->
        <div>
          <div class="rounded-lg shadow">
            <div class="flex items-center justify-between rounded-t-lg border-b bg-white px-6 py-4">
              <Link
                :href="data.task.url.edit"
                class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                {{ $t('Edit') }}
              </Link>
            </div>

            <!-- markdown help -->
            <div class="prose rounded-b-lg bg-gray-50 px-6 py-4 text-sm">
              <span
                @click="destroy()"
                class="cursor-pointer font-medium text-red-700 underline hover:rounded-sm hover:bg-red-700 hover:text-white">
                {{ $t('Delete') }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.checkbox-title {
  top: 3px;
}
</style>
