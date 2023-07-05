<script setup>
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import HelpInput from '@/Components/HelpInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
  description: props.data.project.description,
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
      <h2 class="px-4 py-2 border-b border-gray-200 text-lg font-medium text-gray-900">
        {{ $t('Edit project') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="text-sm p-4 w-96 mr-8">
        {{ $t('bla') }}
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="space-y-6 max-w-3xl">

          <!-- name -->
          <div>
            <InputLabel for="name" :value="$t('What is the name of the project?')" />

            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required />
          </div>

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Save') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
