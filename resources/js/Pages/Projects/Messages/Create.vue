<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);
const formattedBody = ref('');
const activeTab = ref('write');

const form = reactive({
  title: '',
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
  axios.post(props.data.url.preview, form).then((response) => {
    formattedBody.value = response.data.data;
  });
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The message has been added');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Create message')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <!-- Breadcrumb -->
        <nav class="flex py-3 text-gray-700">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
              <div class="flex items-center">
                <Link
                  :href="data.url.breadcrumb.projects"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Projects') }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                <Link
                  :href="data.url.breadcrumb.project"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ data.project.name }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                <Link
                  :href="data.url.breadcrumb.messages"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Messages') }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{ $t('Create a message') }}</span>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-6xl overflow-hidden">
        <form @submit.prevent="submit" class="grid grid-cols-[2fr_1fr] gap-4 px-4">
          <!-- left -->
          <div>
            <div class="relative bg-white px-6 py-4 shadow sm:rounded-lg">
              <!-- Title -->
              <div class="mb-8">
                <InputLabel for="title" :value="$t('Title of the message')" class="mb-1" />

                <TextInput id="title" type="text" class="block w-full" v-model="form.title" autofocus required />
              </div>

              <!-- Description -->
              <div class="mb-4">
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
                  <TextArea id="description" class="block w-full" required v-model="form.body" />
                </div>

                <!-- preview mode -->
                <div v-if="activeTab === 'preview'" class="w-full rounded-lg border bg-gray-50 p-4">
                  <div v-html="formattedBody" class="prose"></div>
                </div>
              </div>

              <Error :errors="form.errors" />
            </div>
          </div>

          <!-- right -->
          <div>
            <div class="rounded-lg shadow">
              <div class="flex items-center justify-between rounded-t-lg border-b bg-white px-6 py-4">
                <Link
                  :href="data.url.breadcrumb.messages"
                  class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Back') }}
                </Link>

                <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
                  {{ $t('Save') }}
                </PrimaryButton>
              </div>

              <!-- markdown help -->
              <div class="prose rounded-b-lg bg-gray-50 px-6 py-4 text-sm">
                <p>{{ $t('We support Markdown, which lets you add formatting to your message.') }}</p>
                <p>{{ $t('Quick reference:') }}</p>
                <ul>
                  <li><code># H1</code></li>
                  <li><code>## H2</code></li>
                  <li><code>**bold text**</code></li>
                  <li><code>*italicized text*</code></li>
                </ul>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
