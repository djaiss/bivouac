<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <section>
    <header class="w-full">
      <h2 class="border-b border-gray-200 px-4 py-2 text-lg font-medium text-gray-900">
        {{ $t('Update Password') }}
      </h2>
    </header>

    <div class="flex">
      <!-- instructions -->
      <div class="mr-8 w-96 p-4 text-sm">
        {{ $t('Ensure your account is using a long, random password to stay secure.') }}
      </div>

      <div class="p-4">
        <Error :errors="form.errors" />

        <form @submit.prevent="updatePassword" class="max-w-3xl space-y-6">
          <div>
            <InputLabel for="current_password" value="Current Password" />

            <TextInput
              id="current_password"
              ref="currentPasswordInput"
              v-model="form.current_password"
              type="password"
              class="mt-1 block w-full"
              autocomplete="current-password" />

            <InputError :message="form.errors.current_password" class="mt-2" />
          </div>

          <div>
            <InputLabel for="password" value="New Password" />

            <TextInput
              id="password"
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-full"
              autocomplete="new-password" />

            <InputError :message="form.errors.password" class="mt-2" />
          </div>

          <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              class="mt-1 block w-full"
              autocomplete="new-password" />

            <InputError :message="form.errors.password_confirmation" class="mt-2" />
          </div>

          <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
            </Transition>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
