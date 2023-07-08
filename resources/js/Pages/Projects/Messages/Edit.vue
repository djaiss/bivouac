<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { onMounted, reactive, ref } from 'vue';

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

onMounted(() => {
  form.title = props.data.message.title;
  form.body = props.data.message.body_raw;
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
    .put(props.data.url.update, form)
    .then((response) => {
      localStorage.success = trans('Changes saved');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Edit message')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white shadow px-4 py-2">
        <!-- Breadcrumb -->
        <nav class="flex py-3 text-gray-700">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
              <div class="flex items-center">
                <Link
                  :href="data.url.breadcrumb.projects"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Projects') }}</Link
                >
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link
                  :href="data.url.breadcrumb.project"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ data.project.name }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link
                  :href="data.url.breadcrumb.messages"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">
                  {{ $t('Messages') }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                <Link
                  :href="data.message.url.show"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline">
                  {{ data.message.title }}
                </Link>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{ $t('Edit the message') }}</span>
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
            <div class="bg-white shadow sm:rounded-lg px-6 py-4 relative">
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
                    class="px-3 py-1 border rounded-l-md cursor-pointer inline pr-2"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'write' }">
                    {{ $t('Write') }}
                  </li>
                  <li
                    @click="showPreviewTab"
                    class="px-3 py-1 border-t border-b border-r rounded-r-md cursor-pointer inline"
                    :class="{ 'text-blue-600 border-blue-600 border-l': activeTab === 'preview' }">
                    {{ $t('Preview') }}
                  </li>
                </ul>

                <!-- write mode -->
                <div v-if="activeTab === 'write'">
                  <TextArea id="description" class="block w-full" required v-model="form.body" />
                </div>

                <!-- preview mode -->
                <div v-if="activeTab === 'preview'" class="border rounded-lg bg-gray-50 p-4 w-full">
                  <div v-html="formattedBody" class="prose"></div>
                </div>
              </div>

              <Error :errors="form.errors" />
            </div>
          </div>

          <!-- right -->
          <div>
            <div class="rounded-lg shadow">
              <div class="bg-white border-b flex items-center justify-between px-6 py-4 rounded-t-lg">
                <Link
                  :href="data.message.url.show"
                  class="text-sm font-medium text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Back') }}</Link
                >

                <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
                  {{ $t('Save') }}
                </PrimaryButton>
              </div>

              <!-- markdown help -->
              <div class="bg-gray-50 rounded-b-lg px-6 py-4 prose text-sm">
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
