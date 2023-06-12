<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import HelpInput from '@/Components/HelpInput.vue';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const user = usePage().props.auth.user;

const form = useForm({
  name: user.name,
  email: user.email,
});
</script>

<template>
  <section>
    <header class="w-full">
      <h2 class="px-4 py-2 border-b border-gray-200 text-lg font-medium text-gray-900">{{ $t('Profile information') }}</h2>
    </header>

    <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6 max-w-xl p-4">
      <div class="flex">
        <!-- first name -->
        <div class="mr-2 w-full">
          <InputLabel for="name" :value="$t('First name')" />

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

        <!-- last name -->
        <div class="w-full">
          <InputLabel for="name" :value="$t('Last name')" />

          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            required
            autofocus
            autocomplete="last_name"
          />

          <InputError class="mt-2" :message="form.errors.last_name" />
        </div>
      </div>

      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autocomplete="username"
        />

        <HelpInput :value="$t('We will send you a verification email to confirm that you own the email address.')" />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800">
          {{ $t('Your email address is unverified.') }}

          <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
              Click here to re-send the verification email.
          </Link>
        </p>

        <div
            v-show="status === 'verification-link-sent'"
            class="mt-2 font-medium text-sm text-green-600"
        >
            A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

          <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
          </Transition>
      </div>
    </form>
  </section>
</template>
