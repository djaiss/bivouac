<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import HelpInput from '@/Components/HelpInput.vue';
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import Error from '@/Components/Error.vue';
import { trans } from 'laravel-vue-i18n';

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
      console.log(error);
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
      <div class="bg-white shadow px-4 py-2">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  :href="data.url.breadcrumb.home"
                  class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                  >{{ $t('Home') }}</Link
                >
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                  <Link
                    :href="data.url.breadcrumb.settings"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Account settings') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400 mr-2" />
                  <Link
                    :href="data.url.breadcrumb.users"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Manage users') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{
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
      <div class="mx-auto max-w-lg bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <form @submit.prevent="submit">
          <div class="px-6 py-4 relative border-b">
            <div
              class="mx-auto mb-4 relative w-24 h-24 overflow-hidden rounded-full ring-2 ring-gray-500 dark:ring-gray-500 dark:bg-gray-600">
              <img src="/img/invite.png" alt="logo" class="text-center mx-auto mb-4 block w-28" />
            </div>
            <h1 class="font-bold text-lg text-center mb-2">{{ $t('Invite a new user') }}</h1>
            <h3 class="text-sm text-gray-700 mb-4 text-center">{{ $t("We'll email this person an invitation.") }}</h3>
          </div>

          <div class="px-6 py-4 relative border-b">
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

          <div class="px-6 py-4 relative">
            <div class="space-y-2">
              <p class="font-bold mb-2 text-sm">{{ $t('What happens next?') }}</p>
              <p>
                {{
                  $t(
                    'The person will receive an email with instructions to setup the account. The invitation will remain valid for three days.',
                  )
                }}
              </p>
            </div>
          </div>

          <div class="border-t flex items-center justify-between px-6 py-4 bg-gray-50">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
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
