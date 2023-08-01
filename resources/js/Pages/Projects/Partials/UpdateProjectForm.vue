<script setup>
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import { flash } from '@/methods.js';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);
const form = reactive({
  name: props.data.project.name,
  short_description: props.data.project.short_description,
  description: props.data.project.description,
  is_public: props.data.project.is_public,
  errors: null,
});

const update = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.update, form)
    .then(() => {
      flash(trans('Changes saved'));
      loadingState.value = false;
      form.errors = null;
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <section>
    <header class="w-full">
      <h2 class="border-b border-gray-200 px-4 py-2 text-lg font-medium text-gray-900">
        {{ $t('Edit project') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="mr-8 w-96 p-4 text-sm">
        {{
          $t(
            "A project title is a concise phrase that identifies a project, providing a brief overview of its subject or purpose. A project description expands on the title and provides a more detailed explanation of the project's goals, objectives, scope, and expected outcomes.",
          )
        }}
      </div>

      <div class="w-full p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="max-w-5xl space-y-4">
          <!-- name -->
          <div>
            <InputLabel for="name" :value="$t('What is the name of the project?')" class="mb-1" />

            <TextInput id="name" type="text" class="block w-full" v-model="form.name" autofocus required />
          </div>

          <!-- short description -->
          <div>
            <InputLabel
              for="short_description"
              :value="$t('Summarize the project in one line.')"
              class="mb-1"
              :required="false" />

            <TextInput id="short_description" type="text" class="block w-full" v-model="form.short_description" />
          </div>

          <!-- full description -->
          <div>
            <InputLabel for="description" :value="$t('Project description')" :required="false" />

            <TextArea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
          </div>

          <div class="space-y-2">
            <div class="flex items-center gap-x-2">
              <input
                id="hidden"
                v-model="form.is_public"
                value="true"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="hidden" class="block text-sm font-medium leading-6 text-gray-900">
                {{ $t('Public: everyone can see and participate') }}
              </label>
            </div>
            <div class="flex items-center gap-x-2">
              <input
                id="month_day"
                v-model="form.is_public"
                value="false"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="month_day" class="block text-sm font-medium leading-6 text-gray-900">
                {{ $t('Private: only selected users can access this project') }}
              </label>
            </div>
          </div>

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Save') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
