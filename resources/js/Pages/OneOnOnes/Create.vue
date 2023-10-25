<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { reactive, ref } from 'vue';

import Avatar from '@/Components/Avatar.vue';
import Error from '@/Components/Error.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  data: {
    type: Array,
  },
});

const loadingState = ref(false);
const chosenUser = ref(null);

const form = reactive({
  user_id: 0,
  errors: '',
});

const setUser = (user) => {
  chosenUser.value = user;
};

const submit = () => {
  loadingState.value = true;
  form.user_id = chosenUser.value.id;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The 1:1 has been created');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Create a 1:1')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white px-4 py-2 shadow">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <div class="flex items-center">
                  <Link :href="data.url.breadcrumb.oneonones" class="text-sm text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                    {{ $t('1:1s') }}
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 dark:text-gray-400 md:ml-2">
                    {{ $t('Create a 1:1') }}
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
              <img src="/img/project_create.png" alt="logo" class="mx-auto block text-center" />
            </div>
            <h1 class="mb-2 text-center text-lg font-bold">{{ $t('Create a 1:1') }}</h1>
            <h3 class="mb-4 text-center text-sm text-gray-700">
              {{ $t('A good relationship is all about communication.') }}
            </h3>
          </div>

          <div class="relative px-6 py-4">
            <!-- Title -->
            <div>
              <InputLabel for="title" :value="$t('Who should this one-on-one be with?')" class="mb-1" />

              <!-- list of users -->
              <ul v-if="!chosenUser" class="overflow-auto rounded-lg border bg-white dark:bg-gray-900" :class="data.users.length > 0 ? 'max-h-80' : ''">
                <li v-for="user in data.users" :key="user.id" class="flex cursor-pointer items-center justify-between border-b border-gray-200 px-3 py-2 last:border-0 hover:bg-slate-50 dark:border-gray-700 dark:bg-slate-900 hover:dark:bg-slate-800">
                  <div class="flex items-center">
                    <Avatar :data="user.avatar" :url="user.url" class="mr-2 w-5" />

                    {{ user.name }}
                  </div>

                  <span @click="setUser(user)" class="flex cursor-pointer rounded-md border border-gray-300 bg-gray-100 px-3 py-1 font-semibold text-gray-700 hover:border-solid hover:border-gray-500 hover:bg-gray-200">
                    {{ $t('Choose') }}
                  </span>
                </li>
              </ul>

              <!-- a user has been chosen -->
              <div v-else class="mt-4 flex items-center justify-between">
                <div class="flex items-center">
                  <Avatar :data="chosenUser.avatar" :url="chosenUser.url" class="mr-2 w-5" />

                  {{ chosenUser.name }}
                </div>

                <span @click="chosenUser = null" class="cursor-pointer text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
                  {{ $t('Change') }}
                </span>
              </div>
            </div>

            <Error :errors="form.errors" />
          </div>

          <!-- action -->
          <div class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
            <Link :href="data.url.breadcrumb.oneonones" class="text-sm font-medium text-blue-700 underline hover:rounded-sm hover:bg-blue-700 hover:text-white">
              {{ $t('Back') }}
            </Link>

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState || !chosenUser">
              {{ $t('Save') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
