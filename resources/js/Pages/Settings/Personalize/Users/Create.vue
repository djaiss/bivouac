<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
import HelpInput from '@/Components/HelpInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);

const form = reactive({
  email: '',
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.invite_store, form)
    .then((response) => {
      localStorage.success = trans('The user has been invited');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Manage users')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  :href="data.url.breadcrumb.home"
                  class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                  >{{ $t('Home') }}</Link
                >
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link
                    :href="data.url.breadcrumb.settings"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                    >{{ $t('Account settings') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link
                    :href="data.url.breadcrumb.users"
                    class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
                    >{{ $t('Manage users') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">{{
                    $t('Invite a new user')
                  }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="pb-12">
      <div class="mx-auto max-w-lg overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <form @submit.prevent="submit">
          <div class="relative border-b px-6 py-4">
            <div class="h-3w-32 relative mx-auto mb-4 w-32 overflow-hidden rounded-full">
              <img src="/img/invite.png" alt="logo" class="mx-auto block text-center" />
            </div>
            <h1 class="mb-2 text-center text-lg font-bold">{{ $t('Invite a new user') }}</h1>
            <h3 class="mb-4 text-center text-sm text-gray-700">{{ $t("We'll email this person an invitation.") }}</h3>
          </div>

          <div class="relative border-b px-6 py-4">
            <!-- Name -->
            <div class="mb-4">
              <InputLabel
                for="email"
                :value="$t('What is the email address of the person you would like to invite?')" />

              <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" autofocus required />

              <HelpInput :value="$t('This should be a valid email address.')" />
            </div>

            <Error :errors="form.errors" />
          </div>

          <div class="relative px-6 py-4">
            <div class="space-y-2">
              <p class="mb-2 text-sm font-bold">{{ $t('What happens next?') }}</p>
              <p>
                {{
                  $t(
                    'The person will receive an email with instructions to setup the account. The invitation will remain valid for three days.',
                  )
                }}
              </p>
            </div>
          </div>

          <div class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white"
              >{{ $t('Back') }}</Link
            >

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Send') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
