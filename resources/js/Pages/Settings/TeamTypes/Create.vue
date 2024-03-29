<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Error from '@/Components/Error.vue';
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
  label: '',
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The team type has been created');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Manage team types')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link :href="data.url.breadcrumb.home" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Home') }}
                </Link>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link :href="data.url.breadcrumb.settings" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Account settings') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="mr-2 h-4 w-4 text-gray-400" />
                  <Link :href="data.url.breadcrumb.offices" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('Manage offices') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                    {{ $t('Add a new team type') }}
                  </span>
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
              <img src="/img/team_create.png" alt="logo" class="mx-auto block text-center" height="140" />
            </div>
            <h1 class="text-center text-lg font-bold">{{ $t('Add a team type') }}</h1>
          </div>

          <Error :errors="form.errors" />

          <div class="relative border-b px-6 py-4">
            <!-- Name -->
            <div class="mb-4">
              <InputLabel for="label" :value="$t('What is the name of the team type?')" />

              <TextInput id="label" type="text" autocomplete="off" class="mt-2 block w-full" v-model="form.label" autofocus required />
            </div>
          </div>

          <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
            <Link :href="data.url.breadcrumb.team_types" class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ $t('Back') }}
            </Link>

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Save') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
