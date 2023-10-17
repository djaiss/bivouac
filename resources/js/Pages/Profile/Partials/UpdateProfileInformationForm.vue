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
  first_name: props.data.user.first_name,
  last_name: props.data.user.last_name,
  email: props.data.user.email,
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
        {{ $t('Profile information') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="mr-8 w-96 p-4 text-sm">
        {{ $t('This information is publicly available within the organization. Everyone can read it.') }}
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="max-w-3xl space-y-6">
          <div class="flex">
            <!-- first name -->
            <div class="mr-4 w-full">
              <InputLabel for="first_name" :value="$t('First name')" />

              <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
            </div>

            <!-- last name -->
            <div class="w-full">
              <InputLabel for="last_name" :value="$t('Last name')" />

              <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="last_name" />
            </div>
          </div>

          <!-- email -->
          <div>
            <InputLabel for="email" :value="$t('Email')" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />

            <HelpInput :value="$t('We will send you a verification email to confirm that you own the email address.')" />
          </div>

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Save') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
