<script setup>
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
const formattedContent = ref('');
const activeTab = ref('write');

const form = reactive({
  content: '',
  errors: '',
});

const showPreviewTab = () => {
  preview();

  activeTab.value = 'preview';
};

const showWriteTab = () => {
  activeTab.value = 'write';
  formattedContent.value = '';
};

const preview = () => {
  axios.post(props.url.preview, form).then((response) => {
    formattedContent.value = response.data.data;
  });
};

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.url.store, form)
    .then((response) => {
      form.content = '';
      loadingState.value = false;
      flash(trans('The comment has been posted'));
      localComments.value.push(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <div>
    <!-- existing comments -->
    <div v-if="localComments">
      <div v-if="localComments.length > 0">
        <ol class="relative border-l border-gray-200 dark:border-gray-700">
          <li v-for="comment in localComments" :key="comment.id" class="mb-10 ml-4">
            <div
              class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>

            <!-- avatar + time -->
            <div class="mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500 flex">
              <div class="flex mr-3">
                <Avatar
                  v-if="comment.author.avatar"
                  :data="comment.author.avatar"
                  :url="comment.author.url"
                  class="w-5 mr-2" />
                <Link
                  :href="comment.author.url"
                  class="text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ comment.author.name }}</Link
                >
              </div>

              <time>{{ comment.created_at }}</time>
            </div>

            <!-- comment -->
            <div class="bg-white rounded-lg shadow px-4 py-4">
              <div v-html="comment.content" class="prose"></div>
            </div>
          </li>
        </ol>
      </div>
    </div>

    <!-- post a comment box -->
    <div class="bg-white rounded-lg shadow px-4 py-4">
      <form @submit.prevent="submit()">
        <p class="mb-2 font-bold">{{ $t('Add a comment') }}</p>
        <ul v-if="form.content" class="mb-5 inline-block text-sm">
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
          <TextArea
            @esc-key-pressed="form.content = ''"
            id="description"
            class="block w-full"
            :rows="'80px'"
            required
            v-model="form.content" />

          <div v-if="form.content" class="flex justify-start mt-4">
            <PrimaryButton class="" :loading="loadingState" :disabled="loadingState">
              {{ $t('Save') }}
            </PrimaryButton>
          </div>
        </div>

        <!-- preview mode -->
        <div v-if="activeTab === 'preview'" class="border rounded-lg bg-gray-50 p-4 w-full">
          <div v-html="formattedContent" class="prose"></div>
        </div>
      </form>
    </div>
  </div>
</template>
