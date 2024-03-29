<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
    <div class="mt-6 w-full overflow-hidden sm:max-w-md">
      <Head title="Log in" />

      <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
      </div>

      <form @submit.prevent="submit" class="mb-6 bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
        <div class="mb-4">
          <img src="img/logo-register.png" alt="logo" class="mx-auto mb-4 block w-28 text-center" />

          <h2 class="mb-2 text-center font-bold">Welcome back to Bivouac</h2>
          <h3 class="mb-4 text-center text-sm text-gray-700">Hope you are having a great day.</h3>
        </div>

        <div>
          <InputLabel for="email" value="Email" />

          <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Password" />

          <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />

          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 block">
          <label class="flex items-center">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
          </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
          <Link v-if="canResetPassword" :href="route('password.request')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Forgot your password?</Link>

          <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Log in</PrimaryButton>
        </div>
      </form>

      <div class="mb-6 rounded-lg border bg-white py-4 text-center shadow">
        <Link :href="route('register')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Do you already have an account?</Link>
      </div>
    </div>
  </div>
</template>
