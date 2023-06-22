<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import HelpInput from '@/Components/HelpInput.vue';
import { Head, router } from '@inertiajs/vue3';
import Error from '@/Components/Error.vue';
import { ref, reactive } from 'vue';
import { trans } from 'laravel-vue-i18n';

const props = defineProps({
  data: {
    type: Array,
  },
});

const form = reactive({
  first_name: '',
  last_name: '',
  password: '',
  errors: null,
});

const loadingState = ref(false);

const update = () => {
  loadingState.value = true;

  axios
    .put(props.data.url.store, form)
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
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="update">
      <div class="mb-4">
        <img src="/img/confirm-invite.png" alt="logo" class="text-center mx-auto mb-4 block w-28" />

        <h2 class="font-bold text-center mb-2">{{ $t('Welcome to Bivouac') }}</h2>
        <h3 class="text-sm text-gray-700 mb-8 text-center">
          {{ $t('Please fill in these fields to finalize your account.') }}
        </h3>
      </div>

      <Error :errors="form.errors" />

      <!-- first name and last name -->
      <div class="flex justify-between">
        <div class="mr-4">
          <InputLabel for="name" value="Your first name" />

          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.first_name"
            required
            autofocus
            autocomplete="first_name" />
        </div>

        <div>
          <InputLabel for="last_name" value="Your last name" />

          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required
            autocomplete="last_name" />
        </div>
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Choose a strong password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password" />

        <HelpInput value="Minimum 8 characters." />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton
          class="ml-4"
          :class="{ 'opacity-25': form.processing }"
          :loading="form.processing"
          :disabled="form.processing">
          {{ $t('Create your account') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
