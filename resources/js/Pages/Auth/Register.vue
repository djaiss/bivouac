<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import HelpInput from '@/Components/HelpInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  first_name: '',
  last_name: '',
  organization_name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <div class="mb-4">
        <img src="img/logo-login.png" alt="logo" class="text-center mx-auto mb-4 block w-28" />

        <h2 class="font-bold text-center mb-2">Welcome to Bivouac</h2>
        <h3 class="text-sm text-gray-700 mb-4 text-center">Create your account now for free.</h3>
      </div>

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
            autocomplete="first_name"
          />

          <InputError class="mt-2" :message="form.errors.first_name" />
        </div>

        <div>
          <InputLabel for="last_name" value="Your last name" />

          <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required
            autocomplete="last_name"
          />

          <InputError class="mt-2" :message="form.errors.last_name" />
        </div>
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Your valid email address" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <HelpInput value="We will send you a verification email, and won't spam you." />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Choose a strong password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <HelpInput value="Minimum 8 characters." />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
          <InputLabel for="organization" value="Your organization's name" />

          <TextInput
            id="organization"
            type="text"
            class="mt-1 block w-full"
            v-model="form.organization_name"
            required
          />

          <InputError class="mt-2" :message="form.errors.organization_name" />
        </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
          Already registered?
        </Link>

        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :loading="form.processing" :disabled="form.processing">
          Register
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
