<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Error from '@/Components/Error.vue';
import { useForm } from '@inertiajs/vue3';
import HelpInput from '@/Components/HelpInput.vue';
import { flash } from '@/methods.js';
import { trans } from 'laravel-vue-i18n';
import { ref, reactive } from 'vue';

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
      <h2 class="px-4 py-2 border-b border-gray-200 text-lg font-medium text-gray-900">
        {{ $t('Profile information') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="text-sm p-4 w-96 mr-8">
        Only your nickname will be visible to everyone. Your name will stay private unless you make it public within an
        organization – in that case, it will be visible within that organization.
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="update" class="space-y-6 max-w-3xl">
          <div class="flex">
            <!-- first name -->
            <div class="mr-4 w-full">
              <InputLabel for="first_name" :value="$t('First name')" />

              <TextInput
                id="first_name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.first_name"
                required
                autofocus
                autocomplete="first_name" />
            </div>

            <!-- last name -->
            <div class="w-full">
              <InputLabel for="last_name" :value="$t('Last name')" />

              <TextInput
                id="last_name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.last_name"
                required
                autocomplete="last_name" />
            </div>
          </div>

          <!-- email -->
          <div>
            <InputLabel for="email" :value="$t('Email')" />

            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.email"
              required
              autocomplete="email" />

            <HelpInput
              :value="$t('We will send you a verification email to confirm that you own the email address.')" />
          </div>

          <PrimaryButton :loading="loadingState" :disabled="loadingState">{{ $t('Save') }}</PrimaryButton>
        </form>
      </div>
    </div>
  </section>
</template>
