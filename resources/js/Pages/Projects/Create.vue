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
  name: '',
  description: '',
  is_public: true,
  errors: '',
});

const submit = () => {
  loadingState.value = true;

  axios
    .post(props.data.url.store, form)
    .then((response) => {
      localStorage.success = trans('The project has been created');
      router.visit(response.data.data);
    })
    .catch((error) => {
      loadingState.value = false;
      form.errors = error.response.data;
    });
};
</script>

<template>
  <Head :title="$t('Create a project')" />

  <AuthenticatedLayout>
    <!-- header -->
    <div class="mb-12">
      <div class="bg-white shadow px-4 py-2">
        <div class="">
          <!-- Breadcrumb -->
          <nav class="flex py-3 text-gray-700">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li>
                <div class="flex items-center">
                  <Link
                    :href="data.url.breadcrumb.projects"
                    class="text-sm text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
                    >{{ $t('Projects') }}</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                  <span class="ml-1 text-sm text-gray-500 md:ml-2 dark:text-gray-400">{{
                    $t('Create a project')
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
            <div class="mx-auto mb-4 relative w-32 h-3w-32 overflow-hidden rounded-full">
              <img src="/img/project_create.png" alt="logo" class="text-center mx-auto block" />
            </div>
            <h1 class="font-bold text-lg text-center mb-2">{{ $t('Create a project') }}</h1>
            <h3 class="text-sm text-gray-700 mb-4 text-center">
              {{ $t('Projects are at the heart of everything in the organization.') }}
            </h3>
          </div>

          <div class="px-6 py-4 relative border-b">
            <!-- Title -->
            <div class="mb-4">
              <InputLabel for="title" :value="$t('What is the name of the project?')" />

              <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.name" autofocus required />
            </div>

            <Error :errors="form.errors" />
          </div>

          <div class="px-6 py-4 space-y-2">
            <div class="flex items-center gap-x-2">
              <input
                id="hidden"
                v-model="form.is_public"
                value="true"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="hidden" class="block text-sm font-medium leading-6 text-gray-900">{{
                $t('Public: everyone can see and participate')
              }}</label>
            </div>
            <div class="flex items-center gap-x-2">
              <input
                id="month_day"
                v-model="form.is_public"
                value="false"
                name="public"
                type="radio"
                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label for="month_day" class="block text-sm font-medium leading-6 text-gray-900">{{
                $t('Private: only selected users can access this project')
              }}</label>
            </div>
          </div>

          <div class="border-t flex items-center justify-between px-6 py-4 bg-gray-50">
            <Link
              :href="data.url.breadcrumb.users"
              class="text-sm font-medium text-blue-700 hover:bg-blue-700 hover:text-white hover:rounded-sm underline"
              >{{ $t('Back') }}</Link
            >

            <PrimaryButton class="ml-4" :loading="loadingState" :disabled="loadingState">
              {{ $t('Create') }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
